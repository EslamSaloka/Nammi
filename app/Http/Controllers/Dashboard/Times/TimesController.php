<?php

namespace App\Http\Controllers\Dashboard\Times;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\Times\CreateRequest;
use App\Http\Requests\Dashboard\Times\UpdateRequest;
use App\Models\Time;

class TimesController extends Controller
{

    public function index(Request $request)
    {
        $breadcrumb = [
            'title' =>  __("Times Lists"),
            'items' =>  [
                [
                    'title' =>  __("Times Lists"),
                    'url'   =>  '#!',
                ]
            ],
        ];
        $lists = Time::latest()->paginate();
        return view('admin.pages.times.index',[
            'breadcrumb'    => $breadcrumb,
            'lists'         => $lists
        ]);
    }

    public function create() {
        $breadcrumb = [
            'title' =>  __("create new times"),
            'items' =>  [
                [
                    'title' =>  __("Times Lists"),
                    'url'   => route('admin.times.index'),
                ],
                [
                    'title' =>  __("create new times"),
                    'url'   =>  '#!',
                ],
            ],
        ];
        return view('admin.pages.times.edit',[
            "breadcrumb"    => $breadcrumb
        ]);
    }

    public function store(CreateRequest $request) {
        Time::create($request->validated());
        return redirect()->route('admin.times.index')->with('success', __(":item has been created.", ['item' => __('Time')]));
    }

    public function edit(Time $time) {
        $breadcrumb = [
            'title' =>  __("edit time information"),
            'items' =>  [
                [
                    'title' =>  __("Times Lists"),
                    'url'   => route('admin.times.index'),
                ],
                [
                    'title' =>  __("edit time information"),
                    'url'   =>  '#!',
                ],
            ],
        ];
        return view('admin.pages.times.edit',[
            "breadcrumb"    =>  $breadcrumb,
            "time"      =>  $time,
        ]);
    }

    public function update(UpdateRequest $request, Time $time) {
        $time->update($request->validated());
        return redirect()->route('admin.times.index', ['page' => $request->page ?? 1])->with('success', __(":item has been updated.", ['item' => __('Time')]));
    }

    public function destroy(Request $request,Time $time) {
        $time->delete();
        return redirect()->route('admin.times.index')->with('success', __(":item has been deleted.",['item' => __('Time')]) );
    }
}
