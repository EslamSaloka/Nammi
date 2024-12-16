<?php

namespace App\Http\Controllers\Dashboard\Coupons;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Coupons\CreateRequest;
use App\Http\Requests\Dashboard\Coupons\UpdateRequest;
use App\Models\Coupon;

class CouponsController extends Controller
{

    public function index()
    {
        $breadcrumb = [
            'title' =>  __("Coupons Lists"),
            'items' =>  [
                [
                    'title' =>  __("Coupons Lists"),
                    'url'   =>  '#!',
                ]
            ],
        ];
        $lists = new Coupon;
        if (request()->has("name") && request("name") != "") {
            $lists = $lists->where('name',"like","%".request("name")."%");
        }
        $lists = $lists->latest()->paginate();
        return view('admin.pages.coupons.index',[
            'breadcrumb'    => $breadcrumb,
            'lists'         => $lists
        ]);
    }

    public function create() {
        $breadcrumb = [
            'title' =>  __("create new coupon"),
            'items' =>  [
                [
                    'title' =>  __("Coupons Lists"),
                    'url'   => route('admin.coupons.index'),
                ],
                [
                    'title' =>  __("create new coupon"),
                    'url'   =>  '#!',
                ],
            ],
        ];
        return view('admin.pages.coupons.edit',[
            "breadcrumb"    => $breadcrumb
        ]);
    }

    public function store(CreateRequest $request) {
        Coupon::create($request->validated());
        return redirect()->route('admin.coupons.index')->with('success', __(":item has been created.", ['item' => __('Coupon')]));
    }

    public function edit(Coupon $coupon) {
        $breadcrumb = [
            'title' =>  __("edit coupon information"),
            'items' =>  [
                [
                    'title' =>  __("Coupons Lists"),
                    'url'   => route('admin.coupons.index'),
                ],
                [
                    'title' =>  __("edit coupon information"),
                    'url'   =>  '#!',
                ],
            ],
        ];
        return view('admin.pages.coupons.edit',[
            "breadcrumb"    =>  $breadcrumb,
            "coupon"        =>  $coupon,
        ]);
    }

    public function update(UpdateRequest $request, Coupon $coupon) {
        $coupon->update($request->validated());
        return redirect()->route('admin.coupons.index', ['page' => $request->page ?? 1])->with('success', __(":item has been updated.", ['item' => __('Coupon')]));
    }

    public function destroy(Coupon $coupon) {
        $coupon->delete();
        return redirect()->route('admin.coupons.index')->with('success', __(":item has been deleted.",['item' => __('Coupon')]) );
    }
}
