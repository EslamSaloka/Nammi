<?php

namespace App\Http\Controllers\Dashboard\Pages;

use App\Http\Controllers\Controller;
// Request
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\Pages\CreateOrUpdateRequest;
// Models
use App\Models\Page;

class PagesController extends Controller
{
    public function index() {
        $breadcrumb = [
            'title' =>  __("Pages Lists"),
            'items' =>  [
                [
                    'title' =>  __("Pages Lists"),
                    'url'   =>  route("admin.pages.index"),
                ]
            ],
        ];
        $lists = Page::latest()->paginate();
        return view("admin.pages.pages.index",get_defined_vars());
    }

    public function create(Request $request) {
        $breadcrumb = [
            'title' =>  __("Create New Page"),
            'items' =>  [
                [
                    'title' =>  __("Pages Lists"),
                    'url'   =>  route("admin.pages.index"),
                ],
                [
                    'title' =>  __("Create New Page"),
                    'url'   =>  '#!',
                ],
            ],
        ];
        return view("admin.pages.pages.edit",get_defined_vars());
    }

    public function store(CreateOrUpdateRequest $request) {
        Page::create($request->all());
        return redirect()->route('admin.pages.index')->with('success', __(":item has been created.",['item' => __('Page')]) );
    }

    public function edit(Request $request,Page $page) {
        $breadcrumb = [
            'title' =>  __("Edit Page Information"),
            'items' =>  [
                [
                    'title' =>  __("Pages Lists"),
                    'url'   =>  route("admin.pages.index"),
                ],
                [
                    'title' =>  __("Edit Page Information"),
                    'url'   =>  '#!',
                ],
            ],
        ];
        return view("admin.pages.pages.edit",get_defined_vars());
    }

    public function update(CreateOrUpdateRequest $request,Page $page) {
        $page->update($request->all());
        return redirect()->route('admin.pages.index')->with('success', __(":item has been updated.",['item' => __('Page')]) );
    }

    public function destroy(Request $request,Page $page) {
        $page->delete();
        return redirect()->route('admin.pages.index')->with('success', __(":item has been deleted.",['item' => __('Page')]) );
    }

}
