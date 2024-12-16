<?php

namespace App\Http\Controllers\Dashboard\Categories;

use App\Exports\CategoriesExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\Categories\CreateRequest;
use App\Http\Requests\Dashboard\Categories\UpdateRequest;
use App\Models\Category;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class CategoriesController extends Controller
{

    public function index(Request $request)
    {
        $breadcrumb = [
            'title' =>  __("Categories Lists"),
            'items' =>  [
                [
                    'title' =>  __("Categories Lists"),
                    'url'   =>  '#!',
                ]
            ],
        ];
        $lists = Category::search($request->all())->latest()->paginate();
        return view('admin.pages.categories.index',[
            'breadcrumb'    => $breadcrumb,
            'lists'         => $lists
        ]);
    }

    public function create() {
        $breadcrumb = [
            'title' =>  __("create new category"),
            'items' =>  [
                [
                    'title' =>  __("Categories Lists"),
                    'url'   => route('admin.categories.index'),
                ],
                [
                    'title' =>  __("create new category"),
                    'url'   =>  '#!',
                ],
            ],
        ];
        return view('admin.pages.categories.edit',[
            "breadcrumb"    => $breadcrumb
        ]);
    }

    public function store(CreateRequest $request) {
        $data = $request->validated();
        if($request->hasFile('image')){
            $data['image'] = (new \App\Support\Image)->FileUpload($request->image, 'categories/images');
        }
        if($request->has('hexacode_color')){
            $data['hexacode_color'] = $request->hexacode_color;
        }
        Category::create($data);
        return redirect()->route('admin.categories.index')->with('success', __(":item has been created.", ['item' => __('Category')]));
    }

    public function edit(Category $category) {
        $breadcrumb = [
            'title' =>  __("edit category information"),
            'items' =>  [
                [
                    'title' =>  __("Categories Lists"),
                    'url'   => route('admin.categories.index'),
                ],
                [
                    'title' =>  __("edit category information"),
                    'url'   =>  '#!',
                ],
            ],
        ];
        return view('admin.pages.categories.edit',[
            "breadcrumb"    =>  $breadcrumb,
            "category"      =>  $category,
        ]);
    }

    public function update(UpdateRequest $request, Category $category) {
        $data = $request->validated();
        if($request->hasFile('image')){
            $data['image'] = (new \App\Support\Image)->FileUpload($request->image, 'categories/images');
        }
        if($request->has('hexacode_color')){
            $data['hexacode_color'] = $request->hexacode_color;
        }
        $category->update($data);
        return redirect()->route('admin.categories.index', ['page' => $request->page ?? 1])->with('success', __(":item has been updated.", ['item' => __('Category')]));
    }

    public function destroy(Category $category) {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', __(":item has been deleted.",['item' => __('Category')]) );
    }

    public function getAll() {
        $club = request('club');
        $menus = Category::whereHas("users",function($q)use($club){
            return $q->where("user_categories_pivot.user_id",$club);
        })->get();
        $data = [
            'label'     => "Main Category",
            'name'      => 'main_category',
            'required'  => true,
            'data'      => $menus,
            'value'     => null
        ];
        return view("admin.component.ajaxSelect",["data"=>$data])->render();
    }

    public function getChild() {
        $menus = Category::where("parent_id",request('cat'))->get();
        $data = [
            'label'     => "Sub Category",
            'name'      => 'sub_category',
            'required'  => true,
            'data'      => $menus,
            'value'     => null
        ];
        return view("admin.component.ajaxSelect",["data"=>$data])->render();
    }

    public function exportPdf(Request $request) {
        $lists = Category::search($request->all())->latest()->get();
        $html = view('admin.pages.categories.export', compact('lists'));
        $mpdf = new \Mpdf\Mpdf(['autoArabic' => true]);
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont = true;
        $mpdf->autoArabic = true;
        $mpdf->writeHtml($html);
        $mpdf->SetDirectionality('rtl');
        return $mpdf->Output("{$this->fileName()}.pdf",'D');
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new CategoriesExport(request()->all(),null), "{$this->fileName()}.xlsx");
    }

    private function fileName() {
        return "categories_".Carbon::now()->format("Y-m-d");
    }
}
