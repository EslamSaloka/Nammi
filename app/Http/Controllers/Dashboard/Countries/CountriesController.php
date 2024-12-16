<?php

namespace App\Http\Controllers\Dashboard\Countries;

use App\Http\Controllers\Controller;
// Requests
use App\Http\Requests\Dashboard\Countries\CreateRequest;
use App\Http\Requests\Dashboard\Countries\UpdateRequest;
// Models
use App\Models\Country;

class CountriesController extends Controller
{

    public function index()
    {
        $breadcrumb = [
            'title' =>  __("Countries Lists"),
            'items' =>  [
                [
                    'title' =>  __("Countries Lists"),
                    'url'   =>  '#!',
                ]
            ],
        ];
        $lists = Country::latest()->paginate();
        return view('admin.pages.countries.index',[
            'breadcrumb'    => $breadcrumb,
            'lists'         => $lists
        ]);
    }

    public function create() {
        $breadcrumb = [
            'title' =>  __("create new country"),
            'items' =>  [
                [
                    'title' =>  __("Countries Lists"),
                    'url'   => route('admin.countries.index'),
                ],
                [
                    'title' =>  __("create new country"),
                    'url'   =>  '#!',
                ],
            ],
        ];
        return view('admin.pages.countries.edit',[
            "breadcrumb"    => $breadcrumb
        ]);
    }

    public function store(CreateRequest $request) {
        Country::create($request->validated());
        return redirect()->route('admin.countries.index')->with('success', __(":item has been created.", ['item' => __('Country')]));
    }

    public function edit(Country $country) {
        $breadcrumb = [
            'title' =>  __("edit country information"),
            'items' =>  [
                [
                    'title' =>  __("Countries Lists"),
                    'url'   => route('admin.countries.index'),
                ],
                [
                    'title' =>  __("edit country information"),
                    'url'   =>  '#!',
                ],
            ],
        ];
        return view('admin.pages.countries.edit',[
            "breadcrumb"    =>  $breadcrumb,
            "country"       =>  $country,
        ]);
    }

    public function update(UpdateRequest $request, Country $country) {
        $country->update($request->validated());
        return redirect()->route('admin.countries.index', ['page' => $request->page ?? 1])->with('success', __(":item has been updated.", ['item' => __('Country')]));
    }

    public function destroy(Country $country) {
        if($country->cities()->count() > 0)  {
            return redirect()->route('admin.countries.index')->with('danger', __("Oops, you can't delete this item.") );
        }
        $country->delete();
        return redirect()->route('admin.countries.index')->with('success', __(":item has been deleted.",['item' => __('Country')]) );
    }
}
