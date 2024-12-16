<?php

namespace App\Http\Controllers\Dashboard\Screens;

use App\Http\Controllers\Controller;
// Request
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\Screen\CreateRequest;
// Models
use App\Models\Landing\Screen as ScreenModel;

class ScreensController extends Controller
{
    public function index()
    {
        $breadcrumb = [
            'title' =>  __("Screens Lists"),
            'items' =>  [
                [
                    'title' =>  __("Screens Lists"),
                    'url'   =>  '#!',
                ]
            ],
        ];
        return view('admin.pages.screens.index',[
            'breadcrumb' => $breadcrumb,
            'lists'      => ScreenModel::all(),
        ]);
    }

    public function store(CreateRequest $request) {
        foreach($request->images as $image) {
            ScreenModel::create([
                "image" => (new \App\Support\Image)->FileUpload($image,"screens")
            ]);
        }
        return redirect()->route('admin.screens.index')->with('success', __("This row has been created."));
    }

    public function destroy(Request $request,ScreenModel $screen) {
        $screen->delete();
        return redirect()->route('admin.screens.index')->with('success', __(":item has been deleted.",['item' => __('Screens')]) );
    }
}
