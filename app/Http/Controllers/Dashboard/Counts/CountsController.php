<?php

namespace App\Http\Controllers\Dashboard\Counts;

use App\Http\Controllers\Controller;
// Request
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\Counts\CreateRequest;
use App\Http\Requests\Dashboard\Counts\UpdateRequest;
// Models
use App\Models\Landing\Count;

class CountsController extends Controller
{
    public function index(Request $request) {
        $breadcrumb = [
            'title' =>  __("Counts Lists"),
            'items' =>  [
                [
                    'title' =>  __("Counts Lists"),
                    'url'   =>  "#!",
                ]
            ],
        ];
        $lists = Count::orderBy('id','desc')->paginate();
        return view('admin.pages.counts.index',[
            'breadcrumb'    =>  $breadcrumb,
            'lists'         =>  $lists,
        ]);
    }

    public function create() {
        if(Count::count() >= 4) {
            return redirect()->route('admin.counts.index')->with('danger', __("It is allowed to add only four elements"));
        }
        $breadcrumb = [
            'title' =>  __("create new count"),
            'items' =>  [
                [
                    'title' =>  __("Counts lists"),
                    'url'   =>  route('admin.counts.index'),
                ],
                [
                    'title' =>  __("create new count"),
                    'url'   =>  '#!',
                ]
            ],
        ];
        return view('admin.pages.counts.edit',[
            'breadcrumb'=>$breadcrumb,
        ]);
    }

    public function store(CreateRequest $request) {
        if(Count::count() >= 4) {
            return redirect()->route('admin.counts.index')->with('danger', __("It is allowed to add only four elements"));
        }
        $request            = $request->all();
        $request["image"]   = (new \App\Support\Image)->FileUpload($request['image'],"Counts");
        Count::create($request);
        return redirect()->route('admin.counts.index')->with('success', __("This row has been created."));
    }

    public function edit(Count $count) {
        $breadcrumb = [
            'title' =>  __("edit count information"),
            'items' =>  [
                [
                    'title' =>  __("Counts Lists"),
                    'url'   =>  route('admin.counts.index'),
                ],
                [
                    'title' =>  __("edit count information"),
                    'url'   =>  '#!',
                ]
            ],
        ];
        return view('admin.pages.counts.edit',[
            'breadcrumb'    =>  $breadcrumb,
            'count'         =>  $count,
        ]);
    }

    public function Update(UpdateRequest $request, Count $count) {
        $request            = $request->all();
        if(request()->has("image")) {
            $request["image"]   = (new \App\Support\Image)->FileUpload($request['image'],"Counts");
        }
        $count->update($request);
        return redirect()->route('admin.counts.index')->with('success', __("This row has been updated."));
    }

    public function destroy(Request $request, Count $count) {
        $count->delete();
        return redirect()->route('admin.counts.index')->with('success', __("This row has been deleted."));
    }
}
