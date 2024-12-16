<?php

namespace App\Http\Controllers\Dashboard\Home;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
// Models
use App\Models\User;

class HomeController extends Controller
{
    public function index() {

        $breadcrumb = [
            'title' =>  __("Welcome To Dashboard"),
            'items' =>  [
                [
                    'title' =>  __("Welcome To Dashboard"),
                    'url'   =>  route('admin.home.index'),
                ]
            ],
        ];


        $rolesArray = Auth::user()->roles()->pluck("name")->toArray();
        if(in_array(User::TYPE_ADMIN,$rolesArray)) {
            $statistic  = $this->adminStatistic();
            $orders     = $this->adminOrders();
        } else {
            $statistic  = $this->clubStatistic();
            $orders     = $this->adminOrders();
        }
        return view('admin.pages.home.index', [
            "statistic"     => $statistic,
            "orders"        => $orders,
            "breadcrumb"    => $breadcrumb,
        ]);
    }

    private function adminOrders() {
        $data = [];
        foreach(Order::orderStatus() as $v) {
            $data[] = [
                "title" => __($v->name)." ".__(""),
                "count" => (isAdmin()) ? Order::where("order_status",$v->id)->count() : Order::where("club_id",Auth::user()->id)->where("order_status",$v->id)->count(),
                "icon"   => 'fa fa-list',
                "route"  => "#!",
            ];
        }
        return $data;
    }

    private function adminStatistic() {
        return [
            [
                "title" => __("Admin Count"),
                "count" => \App\Models\User::where("id","!=",1)->whereHas("roles",function($q){
                    return $q->where("name","=",\App\Models\User::TYPE_ADMIN);
                })->count(),
                "icon"   => 'fa fa-user-shield',
                "route"  => route('admin.admins.index'),
            ],
            [
                "title" => __("Clubs Count"),
                "count" => \App\Models\User::where("id","!=",1)->whereHas("roles",function($q){
                    return $q->where("name","=",\App\Models\User::TYPE_CLUB);
                })->count(),
                "icon"  => 'fa fa-store',
                "route"  => route('admin.clubs.index'),
            ],
            [
                "title" => __("Customers Count"),
                "count" => \App\Models\User::where("id","!=",1)->whereHas("roles",function($q){
                    return $q->where("name","=",\App\Models\User::TYPE_CUSTOMER);
                })->count(),
                "icon"  => 'fa fa-user-friends',
                "route"  => route('admin.customers.index'),
            ],
            [
                "title" => __("Categories Count"),
                "count" => \App\Models\Category::count(),
                "icon"  => 'fa fa-boxes',
                "route"  => route('admin.categories.index'),
            ],
            [
                "title" => __("Times Count"),
                "count" => \App\Models\Time::count(),
                "icon"  => 'fa fa-clock-o',
                "route"  => route('admin.times.index'),
            ],
            [
                "title" => __("Countries Count"),
                "count" => \App\Models\Country::count(),
                "icon"  => 'fa fa-flag',
                "route"  => route('admin.times.index'),
            ],
            [
                "title" => __("Cities Count"),
                "count" => \App\Models\City::count(),
                "icon"  => 'fa fa-flag',
                "route"  => "#!",
            ],
            [
                "title" => __("Banner Count"),
                "count" => \App\Models\Banner::count(),
                "icon"  => 'fa fa-images',
                "route"  => "#!",
            ],
            [
                "title" => __("New Messages Count"),
                "count" => \App\Models\Contact::where("seen",0)->count(),
                "icon"  => 'fa fa-comment-dots',
                "route"  => "#!",
            ],
            [
                "title" => __("Coupons Count"),
                "count" => \App\Models\Coupon::count(),
                "icon"  => 'fa fa-ticket-alt',
                "route"  => "#!",
            ],
        ];
    }

    private function clubStatistic() {
        return [
            [
                "title" => __("Branches Count"),
                "count" => \App\Models\Club\Branch::where("club_id",Auth::user()->id)->count(),
                "icon"  => 'fa fa-project-diagram',
                "route"  => "#!",
            ],
            [
                "title" => __("Activities Count"),
                "count" => \App\Models\Activity::where("club_id",Auth::user()->id)->count(),
                "icon"  => 'fa fa-spa',
                "route"  => "#!",
            ],
        ];
    }

    public function logout() {
        Auth::logout();
        return redirect()->back();
    }
}
