<?php

namespace App\Http\Controllers\Dashboard\Orders;

use App\Exports\OrdersExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Orders\OrdersRequest;
// Requests
// Models
use App\Models\Order;
use App\Models\Order\Due;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class OrdersController extends Controller
{

    public function index()
    {
        $breadcrumb = [
            'title' =>  __("Orders Lists"),
            'items' =>  [
                [
                    'title' =>  __("Orders Lists"),
                    'url'   =>  route("admin.orders.index"),
                ],
            ],
        ];
        $lists = Order::search(request()->all());
        if(!isAdmin()) {
            if (isBranchMaster()) {
                $lists = $lists->where("club_id",Auth::user()->getStaffClub->club_id)->whereHas('branch',function($q){
                    return $q->where('user_id',Auth::user()->id);
                });
            } else {
                $lists = $lists->where("club_id",Auth::user()->id);
            }
        }
        $lists = $lists->latest()->paginate();
        return view('admin.pages.orders.index',[
            'breadcrumb'    => $breadcrumb,
            'lists'         => $lists,
        ]);
    }

    public function show(Order $order) {
        if(!isAdmin()) {
            if (isBranchMaster()) {
                if($order->branch->user_id != Auth::user()->id) {
                    return redirect()->route('admin.orders.index')->with('danger', __("You Don't have access to this order.") );
                }
            } else {
                if($order->club_id != Auth::user()->id) {
                    return redirect()->route('admin.orders.index')->with('danger', __("You Don't have access to this order.") );
                }
            }
        }
        $breadcrumb = [
            'title' =>  __("Show Order"),
            'items' =>  [
                [
                    'title' =>  __("Orders Lists"),
                    'url'   =>  route("admin.orders.index"),
                ],
                [
                    'title' =>  __("Show Order"),
                    'url'   =>  '#!',
                ],
            ],
        ];
        return view('admin.pages.orders.show',[
            "breadcrumb"    =>  $breadcrumb,
            "order"         =>  $order,
        ]);
    }

    public function changeStatus(OrdersRequest $request,Order $order) {
        $OrderStatus = ($request->order_status == Order::STATUS_COMPLETED) ? Order::STATUS_WAITING_CUSTOMER_COMPLETED : $request->order_status;
        if($OrderStatus == Order::STATUS_TIME_CHANGE) {
            $order->update([
                "order_status"  => request("order_status"),
                "date"          => request("date"),
                "time"          => request("time")
            ]);
        } else {
            $order->update([
                "order_status"  => $OrderStatus
            ]);
        }
        $order->histories()->create([
            "order_status"  => $OrderStatus
        ]);

        if($OrderStatus == Order::STATUS_REJECTED) {
            $order->update([
                "cancel_by" => Auth::user()->roles()->first()->name
            ]);
        }
        if($OrderStatus == Order::STATUS_WAITING_CUSTOMER_COMPLETED) {
            // Due::create([
            //     'club_id'            => $order->club_id,
            //     'order_id'           => $order->id,
            //     "price"              => ($order->total * $order->club->vat) / 100,
            //     "order_by"           => $order->payment_type,
            //     "order_total_price"  => $order->total,
            // ]);
            GenerateAndSendOTPForOrder($order);
        }
        (new \App\Support\Notification)->setPushNotification(true)->setTo($order->customer_id)->setTarget($order->id)->setTargetType("order_change_status")->setBody(__(":item has been status updated.",['item' => __('Order')]))->build();
        return redirect()->route('admin.orders.show',$order->id)->with('success', __(":item has been updated.",['item' => __('Order')]) );
    }

    public function destroy(Order $order) {
        if(!isAdmin()) {
            if($order->club_id != Auth::user()->id) {
                return redirect()->route('admin.orders.index')->with('danger', __("You Don't have access to this order.") );
            }
        }
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', __(":item has been deleted.",['item' => __('Order')]) );
    }

    public function exportPdf() {
        $lists = Order::search(request()->all())->latest()->get();
        $html = view('admin.pages.orders.export', compact('lists'));
        $mpdf = new \Mpdf\Mpdf(['autoArabic' => true]);
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont = true;
        $mpdf->autoArabic = true;
        $mpdf->writeHtml($html);
        $mpdf->SetDirectionality('rtl');
        return $mpdf->Output("{$this->fileName()}.pdf",'D');
    }

    public function exportExcel()
    {
        return Excel::download(new OrdersExport(request()->all(),null), "{$this->fileName()}.xlsx");
    }

    private function fileName() {
        return "orders_".Carbon::now()->format("Y-m-d");
    }

    public function customerOtp(Order $order) {
        if($order->order_status != Order::STATUS_WAITING_CUSTOMER_COMPLETED) {
            return redirect()->route('admin.orders.show',$order->id)->with('danger', __("This order not ".Order::STATUS_WAITING_CUSTOMER_COMPLETED) );
        }
        if(!is_null($order->otp_verified_at)) {
            return redirect()->route('admin.orders.show',$order->id)->with('danger', __("This order verified before") );
        }

        if(!request()->has("otp") || !is_numeric(request("otp"))) {
            return redirect()->route('admin.orders.show',$order->id)->with('danger', __("OTP is required and most be number") );
        }

        if($order->otp != request("otp")) {
            return redirect()->route('admin.orders.show',$order->id)->with('danger', __("Oops, this code not correct") );
        }
        $order->update([
            "otp"               => (env('SMS_LIVE') == false) ? 1234 :  rand(1000,9999),
            "otp_verified_at"   => Carbon::now(),
            "order_status"      => Order::STATUS_COMPLETED
        ]);
        $order->histories()->create([
            "order_status"  => Order::STATUS_COMPLETED
        ]);

        // ======================================== //
        Due::create([
            'club_id'            => $order->club_id,
            'order_id'           => $order->id,
            "price"              => ($order->total * $order->club->vat) / 100,
            "order_by"           => $order->payment_type,
            "order_total_price"  => $order->total,
        ]);
        // ========================================== //
        return redirect()->route('admin.orders.show',$order->id)->with('success', __("Thanks") );
    }


    public function reSendOtp(Order $order) {
        if($order->order_status == Order::STATUS_COMPLETED) {
            if(is_null($order->otp_verified_at)) {
                GenerateAndSendOTPForOrder($order);
            }
        }
        return redirect()->route('admin.orders.show',$order->id)->with('success', __("OTP resen Successfully") );
    }
}
