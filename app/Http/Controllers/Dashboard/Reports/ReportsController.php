<?php

namespace App\Http\Controllers\Dashboard\Reports;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportsController extends Controller
{

    public function index(Request $request)
    {
        $breadcrumb = [
            'title' =>  __("Reports"),
            'items' =>  [
                [
                    'title' =>  __("Reports"),
                    'url'   =>  '#!',
                ]
            ],
        ];

        $clubsBar    = $this->GetClubsBarNumbers();
        $customerBar = $this->GetCustomersBarNumbers();
        $ordersBar   = $this->GetOrdersBarNumbers();
        // ============================ //
        $orderStatus        = [];
        $orderStatusFData   = [];
        $year = Carbon::parse(request('year',date('Y')))->format('Y');
        foreach( (new Order)->orderStatus() as $v) {
            $orderStatus[]        = $v->name;
            $orderStatusFData[]   = Order::select("id")->whereYear("created_at",$year)->where("order_status",$v->id)->count();
        }
        $orderStatus      = json_encode($orderStatus);
        $orderStatusFData = json_encode($orderStatusFData);
        return view('admin.pages.reports.index',get_defined_vars());
    }

    private function month() {
        return ['01','02','03','04','05','06','07','08','09','10','11','12'];
    }

    private function GetClubsBarNumbers() {
        $data = [];
        $year = Carbon::parse(request('year',date('Y')))->format('Y');
        foreach($this->month() as $m) {
            $data[] = User::whereHas('roles', function($q) {
                return $q->where('name', '=', User::TYPE_CLUB);
            })->whereYear("created_at",$year)->whereMonth("created_at",$m)->count();
        }
        return json_encode($data);
    }

    private function GetCustomersBarNumbers() {
        $data = [];
        $year = Carbon::parse(request('year',date('Y')))->format('Y');
        foreach($this->month() as $m) {
            $data[] = User::whereHas('roles', function($q) {
                return $q->where('name', '=', User::TYPE_CUSTOMER);
            })->whereYear("created_at",$year)->whereMonth("created_at",$m)->count();
        }
        return json_encode($data);
    }

    private function GetOrdersBarNumbers() {
        $data = [];
        $year = Carbon::parse(request('year',date('Y')))->format('Y');
        foreach($this->month() as $m) {
            $data[] = Order::whereYear("created_at",$year)->whereMonth("created_at",$m)->count();
        }
        return json_encode($data);
    }


}
