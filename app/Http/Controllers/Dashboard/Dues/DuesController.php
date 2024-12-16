<?php

namespace App\Http\Controllers\Dashboard\Dues;

use App\Exports\DuesClubsExport;
use App\Exports\DuesExport;
use App\Http\Controllers\Controller;
use App\Models\Order;
// Http
use Illuminate\Http\Request;
// Models
use App\Models\Order\Due;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class DuesController extends Controller
{

    public function index()
    {
        $breadcrumb = [
            'title' =>  __("Dues lists"),
            'items' =>  [
                [
                    'title' =>  __("Dues lists"),
                    'url'   =>  '#!',
                ]
            ],
        ];
        $clubs = [];
        $lists = User::with("clubDues")->whereHas('roles', function($q) {
            return $q->where('name', '=', User::TYPE_CLUB);
        })->whereNotNull("accepted_at");

        if(!isAdmin()) {
            $lists = $lists->where("id",Auth::user()->id);
        } else {
            // get Clubs
            $clubs = User::whereHas('roles', function($q) {
                return $q->where('name', '=', User::TYPE_CLUB);
            })->whereNotNull("accepted_at")->where('suspend', 0)->get();
            // ======================================= //
        }

        if (request()->has('club_id') && request('club_id') != '-1') {
            $lists = $lists->where("id",request("club_id"));
        }
        $lists = $lists->latest()->paginate();
        // statistic
        $statistic = $this->getStatistic();
        return view('admin.pages.dues.index',compact("breadcrumb", "lists","clubs","statistic"));
    }

    public function show(User $club)
    {
        $breadcrumb = [
            'title' =>  ((App::getLocale() == "ar") ? $club->name ?? '' : $club->name_en ?? '')." ".__("Dues"),
            'items' =>  [
                [
                    'title' =>  __("Dues lists"),
                    'url'   =>  route('admin.dues.index'),
                ],
                [
                    'title' =>  ((App::getLocale() == "ar") ? $club->name ?? '' : $club->name_en ?? '')." ".__("Dues"),
                    'url'   =>  "#!",
                ],
            ],
        ];
        if(!isAdmin()) {
            if($club->id != Auth::user()->id) {
                abort(403);
            }
        }
        $lists = Due::whereHas("club",function($q)use($club){
            return $q->where("club_id",$club->id);
        })->where('confirmed',0)->where('order_by','cod');

        if (request()->has('from_date') && request('from_date')) {
            $lists = $lists->whereDate('created_at', '>=', request('from_date'));
        }
        if (request()->has('to_date') && request('to_date')) {
            $lists = $lists->whereDate('created_at', '<=', request('to_date'));
        }
        if (request()->has('payment_type') && request('payment_type') != '-1') {
            $lists  = $lists->whereHas('order',function($q){
                return $q->where('orders.payment_type',request('payment_type'));
            });
        }

        $lists = $lists->latest()->paginate();
        // statistic
        $statistic = $this->getClubStatistic($club);
        return view('admin.pages.dues.show',compact("breadcrumb", "lists","statistic","club"));
    }

    public function confirmed(User $club) {
        if(!isAdmin()) {
            abort(403);
        }
        Due::where([
            "club_id"    => $club->id,
            "confirmed"  => 0,
            "order_by"   => "cod"
        ])->update([
            "confirmed"   => 1
        ]);
        (new \App\Support\Notification)->setTo($club->id)->setTarget($club->id)->setTargetType("due_completed")->setBody(__(":item has been completed.",['item' => __('Dues')]))->build();
        return redirect()->back()->with('success', __(":item has been completed.", ['item' => __('Dues')]));
    }

    private function getClubStatistic($club) {
        $total          = Due::select("price")->where("club_id",$club->id)->where('order_by','cod');
        $confirmed      = Due::select("price")->where("club_id",$club->id)->where("confirmed",1)->where('order_by','cod');
        $un_confirmed   = Due::select("price")->where("club_id",$club->id)->where("confirmed",0)->where('order_by','cod');
        // ========================================================== //
        if (request()->has('from_date') && request('from_date') != '') {
            $total          = $total->whereDate('created_at', '>=', Carbon::parse(request('from_date')));
            $confirmed      = $confirmed->whereDate('created_at', '>=', Carbon::parse(request('from_date')));
            $un_confirmed   = $un_confirmed->whereDate('created_at', '>=', Carbon::parse(request('from_date')));
        }
        if (request()->has('to_date') && request('to_date') != '') {
            $total          = $total->whereDate('created_at', '<=', Carbon::parse(request('to_date')));
            $confirmed      = $confirmed->whereDate('created_at', '<=', Carbon::parse(request('to_date')));
            $un_confirmed   = $un_confirmed->whereDate('created_at', '<=', Carbon::parse(request('to_date')));
        }
        if (request()->has('payment_type') && request('payment_type') != '-1') {
            $total          = $total->whereHas('order',function($q){
                return $q->where('orders.payment_type',request('payment_type'));
            });
            $confirmed      = $confirmed->whereHas('order',function($q){
                return $q->where('orders.payment_type',request('payment_type'));
            });
            $un_confirmed   = $un_confirmed->whereHas('order',function($q){
                return $q->where('orders.payment_type',request('payment_type'));
            });
        }

        $dd = [];
        foreach(Order::orderStatus() as $v) {
            $x = Order::where("club_id",$club->id)->where("order_status",$v->id);
            if (request()->has('from_date') && request('from_date') != '') {
                $x  = $x->whereDate('created_at', '>=', Carbon::parse(request('from_date')));
            }
            if (request()->has('to_date') && request('to_date') != '') {
                $x  = $x->whereDate('created_at', '<=', Carbon::parse(request('to_date')));
            }
            if (request()->has('payment_type') && request('payment_type') != '-1') {
                $x   = $x->where('orders.payment_type',request('payment_type'));
            }
            $dd[$v->name] = $x->count();
        }

        return [
            "total"                 => $total->sum("price"),
            "confirmed"             => $confirmed->sum("price"),
            "un_confirmed"          => $un_confirmed->sum("price"),
            "ordersStatistic"        => $dd,
        ];
    }

    private function getStatistic() {
        $total          = null;
        $confirmed      = null;
        $un_confirmed   = null;
        if(!isAdmin()) {
            $total          = Due::select("price")->where("club_id",Auth::user()->id)->where('order_by','cod');
            $confirmed      = Due::select("price")->where("club_id",Auth::user()->id)->where("confirmed",1)->where('order_by','cod');
            $un_confirmed   = Due::select("price")->where("club_id",Auth::user()->id)->where("confirmed",0)->where('order_by','cod');
        } else {
            $total          = Due::select("price")->where('order_by','cod');
            $confirmed      = Due::select("price")->where("confirmed",1)->where('order_by','cod');
            $un_confirmed   = Due::select("price")->where("confirmed",0)->where('order_by','cod');
        }

        if (request()->has('payment_type') && request('payment_type') != '-1') {
            $total          = $total->whereHas('order',function($q){
                return $q->where('orders.payment_type',request('payment_type'));
            });
            $confirmed      = $confirmed->whereHas('order',function($q){
                return $q->where('orders.payment_type',request('payment_type'));
            });
            $un_confirmed   = $un_confirmed->whereHas('order',function($q){
                return $q->where('orders.payment_type',request('payment_type'));
            });
        }

        if(isAdmin()) {
            if (request()->has('club_id') && request('club_id') != '-1') {
                $total          = $total->where("club_id",request("club_id"))->where('order_by','cod');
                $confirmed      = $confirmed->where("club_id",request("club_id"))->where('order_by','cod');
                $un_confirmed   = $un_confirmed->where("club_id",request("club_id"))->where('order_by','cod');
            }
        }

        return [
            "total"         => (is_null($total)) ? 0 : $total->sum("price"),
            "confirmed"     => (is_null($confirmed)) ? 0 : $confirmed->sum("price"),
            "un_confirmed"  => (is_null($un_confirmed)) ? 0 : $un_confirmed->sum("price"),
        ];
    }

    public function exportAll() {
        $lists = User::with("clubDues")->whereHas('roles', function($q) {
            return $q->where('name', '=', User::TYPE_CLUB);
        })->whereNotNull("accepted_at");

        if(!isAdmin()) {
            $lists = $lists->where("id",Auth::user()->id);
        } else {
            if (request()->has('club_id') && request('club_id') != '-1') {
                $lists   = $lists->where("id",request("club_id"));
            }
        }
        // if (request()->has('from_date') && request('from_date') != '') {
        //     $lists = $lists->whereDate('created_at', '>=', request('from_date'));
        // }
        // if (request()->has('to_date') && request('to_date') != '') {
        //     $lists = $lists->whereDate('created_at', '<=', request('to_date'));
        // }
        $lists = $lists->latest()->get();
        $data = Carbon::now()->format('Y-m-d');
        return Excel::download(new DuesClubsExport($lists), "clubs_dues_{$data}.xlsx");
    }

    public function exportClub(User $club) {
        if(!isAdmin()) {
            if($club->id != Auth::user()->id) {
                abort(403);
            }
        }
        $lists = Due::whereHas("club",function($q)use($club){
            return $q->where("club_id",$club->id);
        });
        if (request()->has('from_date') && request('from_date')) {
            $lists = $lists->whereDate('created_at', '>=', request('from_date'));
        }
        if (request()->has('to_date') && request('to_date')) {
            $lists = $lists->whereDate('created_at', '<=', request('to_date'));
        }
        if (request()->has('payment_type') && request('payment_type') != '-1') {
            $lists  = $lists->whereHas('order',function($q){
                return $q->where('orders.payment_type',request('payment_type'));
            });
        }
        $lists = $lists->where('order_by','cod')->latest()->paginate();
        $data = Carbon::now()->format('Y-m-d');
        return Excel::download(new DuesExport($lists), "clubs_dues_{$data}.xlsx");
    }

}
