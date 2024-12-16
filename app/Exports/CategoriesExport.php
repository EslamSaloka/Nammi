<?php

namespace App\Exports;

use App\Models\Category;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CategoriesExport implements FromView
{
    private $filter     = [];
    private $category   = null;

    public function __construct($filter,$category)
    {
        $this->filter   = $filter;
        $this->category = $category;
    }

    public function view(): View
    {
        if(is_null($this->category)) {
            $lists = Category::search($this->filter)->latest()->get();
        } else {
            $lists = $this->category->children()->latest()->get();
        }
        return view('admin.pages.categories.export',compact('lists'));
    }
}
