<?php

namespace App\Http\Controllers\Dashboard\Categories;

use App\Exports\CategoriesExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Categories\CreateRequest;
use App\Http\Requests\Dashboard\Categories\UpdateRequest;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ChidersController extends Controller
{

    public function index(Category $category)
    {
        $breadcrumb = [
            'title' =>  __("Sub Categories List"),
            'items' =>  [
                [
                    'title' =>  __("Categories Lists"),
                    'url'   =>  route("admin.categories.index"),
                ],
                [
                    'title' =>  $category->name." ".__("Sub Categories List"),
                    'url'   =>  '#!',
                ],
            ],
        ];
        $lists = $category->children()->latest()->paginate();
        return view('admin.pages.categories.chiders.index',[
            'breadcrumb'    => $breadcrumb,
            'lists'         => $lists,
            'category'      => $category,
        ]);
    }

    public function create(Category $category) {
        $breadcrumb = [
            'title' =>  __("create new sub category"),
            'items' =>  [
                [
                    'title' =>  __("Categories Lists"),
                    'url'   =>  route("admin.categories.index"),
                ],
                [
                    'title' =>  $category->name." ".__("Sub Categories List"),
                    'url'   =>  route("admin.categories.chiders.index",$category->id),
                ],
                [
                    'title' =>  __("create new sub category"),
                    'url'   =>  '#!',
                ],
            ],
        ];
        return view('admin.pages.categories.chiders.edit',[
            "breadcrumb"    => $breadcrumb,
            'category'      => $category,
        ]);
    }

    public function store(CreateRequest $request,Category $category) {
        $data = $request->validated();
        if($request->hasFile('image')){
            $data['image'] = (new \App\Support\Image)->FileUpload($request->image, 'categories/chider/images');
        }

        if($request->has('hexacode_color')){
            $data['hexacode_color'] = $request->hexacode_color;
        }

        $category->children()->create($data);
        return redirect()->route('admin.categories.chiders.index',$category->id)->with('success', __(":item has been created.", ['item' => __('sub category')]));
    }

    public function edit(Category $category,Category $chider) {
        $breadcrumb = [
            'title' =>  __("edit sub category information"),
            'items' =>  [
                [
                    'title' =>  __("Categories Lists"),
                    'url'   =>  route("admin.categories.index"),
                ],
                [
                    'title' =>  $category->name." ".__("Sub Categories List"),
                    'url'   =>  route("admin.categories.chiders.index",$category->id),
                ],
                [
                    'title' =>  __("edit banner information"),
                    'url'   =>  '#!',
                ],
            ],
        ];
        return view('admin.pages.categories.chiders.edit',[
            "breadcrumb"    =>  $breadcrumb,
            "category"      =>  $category,
            "chider"        =>  $chider,
        ]);
    }

    public function update(UpdateRequest $request, Category $category,Category $chider) {
        $data = $request->validated();
        if($request->hasFile('image')){
            $data['image'] = (new \App\Support\Image)->FileUpload($request->image, 'categories/chider/images');
        }
        if($request->has('hexacode_color')){
            $data['hexacode_color'] = $request->hexacode_color;
        }
        $chider->update($data);
        return redirect()->route('admin.categories.chiders.index',$category->id)->with('success', __(":item has been updated.", ['item' => __('sub category')]));
    }

    public function destroy(Category $category,Category $chider) {
        $chider->delete();
        return redirect()->route('admin.categories.chiders.index',$category->id)->with('success', __(":item has been deleted.",['item' => __('sub category')]) );
    }


    public function exportPdf(Request $request,Category $category) {
        $lists = $category->children()->latest()->get();
        $html = view('admin.pages.categories.export', compact('lists'));
        $mpdf = new \Mpdf\Mpdf(['autoArabic' => true]);
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont = true;
        $mpdf->autoArabic = true;
        $mpdf->writeHtml($html);
        $mpdf->SetDirectionality('rtl');
        return $mpdf->Output("{$this->fileName()}.pdf",'D');
    }

    public function exportExcel(Request $request,Category $category)
    {
        return Excel::download(new CategoriesExport(request()->all(),$category), "{$this->fileName()}.xlsx");
    }

    private function fileName() {
        return "sub-category".Carbon::now()->format("Y-m-d");
    }
}
