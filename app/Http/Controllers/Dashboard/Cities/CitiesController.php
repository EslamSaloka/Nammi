<?php

namespace App\Http\Controllers\Dashboard\Cities;

use App\Http\Controllers\Controller;
// Requests
use App\Http\Requests\Dashboard\Cities\CreateRequest;
use App\Http\Requests\Dashboard\Cities\UpdateRequest;
// Models
use App\Models\Country;
use App\Models\City;

class CitiesController extends Controller
{

    public function index(Country $country)
    {
        $breadcrumb = [
            'title' =>  __("Cities Lists"),
            'items' =>  [
                [
                    'title' =>  __("Countries Lists"),
                    'url'   =>  route("admin.countries.index"),
                ],
                [
                    'title' =>  $country->name." ".__("Cities Lists"),
                    'url'   =>  '#!',
                ]
            ],
        ];
        $lists = $country->cities()->latest()->paginate();
        return view('admin.pages.cities.index',[
            'breadcrumb'    => $breadcrumb,
            'lists'         => $lists,
            'country'       => $country
        ]);
    }

    public function create(Country $country) {
        $breadcrumb = [
            'title' =>  __("create new city"),
            'items' =>  [
                [
                    'title' =>  __("Countries Lists"),
                    'url'   =>  route("admin.countries.index"),
                ],
                [
                    'title' =>  $country->name." ".__("Cities Lists"),
                    'url'   =>  route("admin.countries.cities.index",$country->id),
                ],
                [
                    'title' =>  __("create new city"),
                    'url'   =>  '#!',
                ],
            ],
        ];
        return view('admin.pages.cities.edit',[
            "breadcrumb"    => $breadcrumb,
            'country'       => $country
        ]);
    }

    public function store(CreateRequest $request,Country $country) {
        $country->cities()->create($request->validated());
        return redirect()->route('admin.countries.cities.index',$country->id)->with('success', __(":item has been created.", ['item' => __('City')]));
    }

    public function edit(Country $country,City $city) {
        $breadcrumb = [
            'title' =>  __("edit city information"),
            'items' =>  [
                [
                    'title' =>  __("Countries Lists"),
                    'url'   =>  route("admin.countries.index"),
                ],
                [
                    'title' =>  $country->name." ".__("Cities Lists"),
                    'url'   =>  route("admin.countries.cities.index",$country->id),
                ],
                [
                    'title' =>  __("edit city information"),
                    'url'   =>  '#!',
                ],
            ],
        ];
        return view('admin.pages.cities.edit',[
            "breadcrumb"    =>  $breadcrumb,
            "city"          =>  $city,
            "country"       =>  $country,
        ]);
    }

    public function update(UpdateRequest $request, Country $country,City $city) {
        $city->update($request->validated());
        return redirect()->route('admin.countries.cities.index',$country->id)->with('success', __(":item has been updated.", ['item' => __('City')]));
    }

    public function destroy(Country $country,City $city) {
        $city->delete();
        return redirect()->route('admin.countries.cities.index',$country->id)->with('success', __(":item has been deleted.",['item' => __('City')]) );
    }

    public function getCities() {
        $menus = City::where([
            'country_id'   => request("country"),
        ])->get();
        $data = [
            'label'     => "City",
            'name'      => 'city_id',
            'required'  => true,
            'data'      => $menus,
            'keyV'      => "name",
            'value'     => null
        ];
        return view("admin.component.ajaxSelect",["data"=>$data])->render();
    }
}
