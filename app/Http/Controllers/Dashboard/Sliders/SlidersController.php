<?php

namespace App\Http\Controllers\Dashboard\Sliders;

use App\Http\Controllers\Controller;
// Request
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\Sliders\CreateRequest;
use App\Http\Requests\Dashboard\Sliders\UpdateRequest;
// Models
use App\Models\Banner;

class SlidersController extends Controller
{
    public function index(Request $request) {
        $breadcrumb = [
            'title' =>  __("Sliders Lists"),
            'items' =>  [
                [
                    'title' =>  __("Sliders Lists"),
                    'url'   =>  route('admin.sliders.index'),
                ]
            ],
        ];
        $lists = Banner::orderBy('id','desc')->paginate();
        return view('admin.pages.sliders.index',[
            'breadcrumb'    =>  $breadcrumb,
            'lists'         =>  $lists,
        ]);
    }

    public function create() {
        $breadcrumb = [
            'title' =>  __("create new slider"),
            'items' =>  [
                [
                    'title' =>  __("Sliders Lists"),
                    'url'   =>  route('admin.sliders.index'),
                ],
                [
                    'title' =>  __("create new slider"),
                    'url'   =>  '#!',
                ]
            ],
        ];
        return view('admin.pages.sliders.edit',[
            'breadcrumb'    =>  $breadcrumb,
            'activities'    =>  \App\Models\Activity::all(),
        ]);
    }

    public function store(CreateRequest $request) {
        $request            = $request->all();
        $request["image"]   = (new \App\Support\Image)->FileUpload($request['image'],"sliders");
        Banner::create($request);
        return redirect()->route('admin.sliders.index')->with('success', __("This row has been created."));
    }

    public function edit(Banner $slider) {
        $breadcrumb = [
            'title' =>  __("edit slider information"),
            'items' =>  [
                [
                    'title' =>  __("Sliders Lists"),
                    'url'   =>  route('admin.sliders.index'),
                ],
                [
                    'title' =>  __("edit slider information"),
                    'url'   =>  '#!',
                ]
            ],
        ];
        return view('admin.pages.sliders.edit',[
            'breadcrumb'    =>  $breadcrumb,
            'slider'        =>  $slider,
            'activities'    =>  \App\Models\Activity::all(),
        ]);
    }

    public function Update(UpdateRequest $request, Banner $slider) {
        $request            = $request->all();
        if(request()->hasFile("image")) {
            $request["image"]   = (new \App\Support\Image)->FileUpload($request['image'],"sliders");
        } else {
            if(isset($request["image"])) {
                unset($request["image"]);
            }
        }
        $slider->update($request);
        return redirect()->route('admin.sliders.index')->with('success', __("This row has been updated."));
    }

    public function destroy(Request $request, Banner $slider) {
        $slider->delete();
        return redirect()->route('admin.sliders.index')->with('success', __("This row has been deleted."));
    }
}
