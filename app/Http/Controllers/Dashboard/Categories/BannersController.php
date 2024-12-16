<?php

namespace App\Http\Controllers\Dashboard\Categories;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Categories\Banners\CreateRequest;
use App\Http\Requests\Dashboard\Categories\Banners\UpdateRequest;
use App\Models\Category;
use App\Models\Category\Image;

class BannersController extends Controller
{

    public function index(Category $category)
    {
        $breadcrumb = [
            'title' =>  __("Banners Lists"),
            'items' =>  [
                [
                    'title' =>  __("Categories Lists"),
                    'url'   =>  route("admin.categories.index"),
                ],
                [
                    'title' =>  $category->name." ".__("Banners Lists"),
                    'url'   =>  '#!',
                ],
            ],
        ];
        $lists = $category->banners()->latest()->paginate();
        return view('admin.pages.categories.banners.index',[
            'breadcrumb'    => $breadcrumb,
            'lists'         => $lists,
            'category'      => $category,
        ]);
    }

    public function create(Category $category) {
        $breadcrumb = [
            'title' =>  __("create new banner"),
            'items' =>  [
                [
                    'title' =>  __("Categories Lists"),
                    'url'   =>  route("admin.categories.index"),
                ],
                [
                    'title' =>  $category->name." ".__("Banners Lists"),
                    'url'   =>  route("admin.categories.banners.index",$category->id),
                ],
                [
                    'title' =>  __("create new banner"),
                    'url'   =>  '#!',
                ],
            ],
        ];
        return view('admin.pages.categories.banners.edit',[
            "breadcrumb"    => $breadcrumb,
            'category'      => $category,
        ]);
    }

    public function store(CreateRequest $request,Category $category) {
        $data = $request->validated();
        if($request->hasFile('image')){
            $data['image'] = (new \App\Support\Image)->FileUpload($request->image, 'categories/images');
        }
        $category->banners()->create($data);
        return redirect()->route('admin.categories.banners.index',$category->id)->with('success', __(":item has been created.", ['item' => __('Banner')]));
    }

    public function edit(Category $category,Image $banner) {
        $breadcrumb = [
            'title' =>  __("edit banner information"),
            'items' =>  [
                [
                    'title' =>  __("Categories Lists"),
                    'url'   =>  route("admin.categories.index"),
                ],
                [
                    'title' =>  $category->name." ".__("Banners Lists"),
                    'url'   =>  route("admin.categories.banners.index",$category->id),
                ],
                [
                    'title' =>  __("edit banner information"),
                    'url'   =>  '#!',
                ],
            ],
        ];
        return view('admin.pages.categories.banners.edit',[
            "breadcrumb"    =>  $breadcrumb,
            "category"      =>  $category,
            "banner"      =>  $banner,
        ]);
    }

    public function update(UpdateRequest $request, Category $category,Image $banner) {
        $data = $request->validated();
        if($request->hasFile('image')){
            $data['image'] = (new \App\Support\Image)->FileUpload($request->image, 'categories/images');
        }
        $banner->update($data);
        return redirect()->route('admin.categories.banners.index',$category->id)->with('success', __(":item has been updated.", ['item' => __('Banner')]));
    }

    public function destroy(Category $category,Image $banner) {
        $banner->delete();
        return redirect()->route('admin.categories.banners.index',$category->id)->with('success', __(":item has been deleted.",['item' => __('Banner')]) );
    }
}
