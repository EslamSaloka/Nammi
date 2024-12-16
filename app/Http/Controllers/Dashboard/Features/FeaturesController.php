<?php

namespace App\Http\Controllers\Dashboard\Features;

use App\Http\Controllers\Controller;
// Request
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\Features\CreateRequest;
use App\Http\Requests\Dashboard\Features\UpdateRequest;
// Models
use App\Models\Landing\Features;

class FeaturesController extends Controller
{
    public function index(Request $request) {
        $breadcrumb = [
            'title' =>  __("Features"),
            'items' =>  [
                [
                    'title' =>  __("Features Lists"),
                    'url'   =>  route('admin.features.index'),
                ]
            ],
        ];
        $lists = Features::orderBy('id','desc')->paginate();
        return view('admin.pages.features.index',[
            'breadcrumb'    =>  $breadcrumb,
            'lists'         =>  $lists,
        ]);
    }

    public function create() {
        $breadcrumb = [
            'title' =>  __("create new features"),
            'items' =>  [
                [
                    'title' =>  __("Features Lists"),
                    'url'   =>  route('admin.features.index'),
                ],
                [
                    'title' =>  __("create new features"),
                    'url'   =>  '#!',
                ]
            ],
        ];
        return view('admin.pages.features.edit',[
            'breadcrumb'=>$breadcrumb,
        ]);
    }

    public function store(CreateRequest $request) {
        $request            = $request->all();
        $request["image"]   = (new \App\Support\Image)->FileUpload($request['image'],"features");
        Features::create($request);
        return redirect()->route('admin.features.index')->with('success', __("This row has been created."));
    }

    public function edit(Features $feature) {
        $breadcrumb = [
            'title' =>  __("edit features information"),
            'items' =>  [
                [
                    'title' =>  __("Features Lists"),
                    'url'   =>  route('admin.features.index'),
                ],
                [
                    'title' =>  __("Edit Features"),
                    'url'   =>  '#!',
                ]
            ],
        ];
        return view('admin.pages.features.edit',[
            'breadcrumb'    =>  $breadcrumb,
            'feature'       =>  $feature,
        ]);
    }

    public function Update(UpdateRequest $request, Features $feature) {
        $request            = $request->all();
        if(request()->has("image")) {
            $request["image"]   = (new \App\Support\Image)->FileUpload($request['image'],"features");
        }
        $feature->update($request);
        return redirect()->route('admin.features.index')->with('success', __("This row has been updated."));
    }

    public function destroy(Request $request, Features $feature) {
        $feature->delete();
        return redirect()->route('admin.features.index')->with('success', __("This row has been deleted."));
    }
}
