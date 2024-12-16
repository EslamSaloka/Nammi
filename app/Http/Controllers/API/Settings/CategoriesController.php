<?php

namespace App\Http\Controllers\API\Settings;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\Categories\CategoriesPluckResource;
use App\Http\Resources\API\Categories\CategoriesResource;
use App\Http\Resources\API\Categories\CategoriesBannerResource;
use App\Models\Category;

class CategoriesController extends Controller
{
    public function index() {
        return (new \App\Support\API)->isOk(__("Categories Lists"))->setData(CategoriesResource::collection(getParentCategories()))->build();
    }

    public function getAllSubCategories() {
        return (new \App\Support\API)->isOk(__("Get All Sub Categories Lists"))->setData(CategoriesResource::collection(Category::where("parent_id","!=",0)->get()))->build();
    }

    public function show(Category $category) {
        return (new \App\Support\API)->isOk(__("Children Category Lists"))->setData(CategoriesResource::collection($category->children))->build();
    }

    public function getCategoryBanners(Category $category) {
        return (new \App\Support\API)->isOk(__("Category Banners Lists"))->setData(CategoriesBannerResource::collection($category->banners))->build();
    }

    public function showAllPluck() {
        return (new \App\Support\API)->isOk(__("Pluck Lists"))->setData(CategoriesPluckResource::collection(getParentCategories()))->build();
    }
}
