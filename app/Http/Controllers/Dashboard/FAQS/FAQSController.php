<?php

namespace App\Http\Controllers\Dashboard\FAQS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\FAQS\CreateRequest;
use App\Http\Requests\Dashboard\FAQS\UpdateRequest;
use App\Models\FAQ;

class FAQSController extends Controller
{

    public function index(Request $request)
    {
        $breadcrumb = [
            'title' =>  __("FAQ Lists"),
            'items' =>  [
                [
                    'title' =>  __("FAQ Lists"),
                    'url'   =>  '#!',
                ]
            ],
        ];
        $lists = FAQ::latest()->paginate();
        return view('admin.pages.faqs.index',[
            'breadcrumb'    => $breadcrumb,
            'lists'         => $lists
        ]);
    }

    public function create() {
        $breadcrumb = [
            'title' =>  __("create new faq"),
            'items' =>  [
                [
                    'title' =>  __("FAQ Lists"),
                    'url'   => route('admin.faqs.index'),
                ],
                [
                    'title' =>  __("create new faq"),
                    'url'   =>  '#!',
                ],
            ],
        ];
        return view('admin.pages.faqs.edit',[
            "breadcrumb"    => $breadcrumb
        ]);
    }

    public function store(CreateRequest $request) {
        FAQ::create($request->validated());
        return redirect()->route('admin.faqs.index')->with('success', __(":item has been created.", ['item' => __('FAQ')]));
    }

    public function edit(FAQ $faq) {
        $breadcrumb = [
            'title' =>  __("edit faq information"),
            'items' =>  [
                [
                    'title' =>  __("FAQ Lists"),
                    'url'   => route('admin.faqs.index'),
                ],
                [
                    'title' =>  __("edit faq information"),
                    'url'   =>  '#!',
                ],
            ],
        ];
        return view('admin.pages.faqs.edit',[
            "breadcrumb"    =>  $breadcrumb,
            "faq"      =>  $faq,
        ]);
    }

    public function update(UpdateRequest $request, FAQ $faq) {
        $faq->update($request->validated());
        return redirect()->route('admin.faqs.index', ['page' => $request->page ?? 1])->with('success', __(":item has been updated.", ['item' => __('FAQ')]));
    }

    public function destroy(Request $request,FAQ $faq) {
        $faq->delete();
        return redirect()->route('admin.faqs.index')->with('success', __(":item has been deleted.",['item' => __('FAQ')]) );
    }
}
