<?php

namespace App\Http\Controllers\Dashboard\Feedbacks;

use App\Http\Controllers\Controller;
// Request
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\Feedbacks\CreateRequest;
use App\Http\Requests\Dashboard\Feedbacks\UpdateRequest;
// Models
use App\Models\Landing\FeedBack;

class FeedbacksController extends Controller
{
    public function index(Request $request) {
        $breadcrumb = [
            'title' =>  __("Feedbacks"),
            'items' =>  [
                [
                    'title' =>  __("Feedbacks"),
                    'url'   =>  route('admin.feedbacks.index'),
                ]
            ],
        ];
        $lists = FeedBack::orderBy('id','desc')->paginate();
        return view('admin.pages.feedbacks.index',[
            'breadcrumb'    =>  $breadcrumb,
            'lists'         =>  $lists,
        ]);
    }

    public function create() {
        $breadcrumb = [
            'title' =>  __("create new feedback"),
            'items' =>  [
                [
                    'title' =>  __("Feedbacks"),
                    'url'   =>  route('admin.feedbacks.index'),
                ],
                [
                    'title' =>  __("create new feedback"),
                    'url'   =>  '#!',
                ]
            ],
        ];
        return view('admin.pages.feedbacks.edit',[
            'breadcrumb'=>$breadcrumb,
        ]);
    }

    public function store(CreateRequest $request) {
        FeedBack::create($request->validated());
        return redirect()->route('admin.feedbacks.index')->with('success', __("This row has been created."));
    }

    public function edit(FeedBack $feedback) {
        $breadcrumb = [
            'title' =>  __("edit feedback information"),
            'items' =>  [
                [
                    'title' =>  __("Feedbacks"),
                    'url'   =>  route('admin.feedbacks.index'),
                ],
                [
                    'title' =>  __("edit feedback information"),
                    'url'   =>  '#!',
                ]
            ],
        ];
        return view('admin.pages.feedbacks.edit',[
            'breadcrumb'    =>  $breadcrumb,
            'feedback'      =>  $feedback,
        ]);
    }

    public function Update(UpdateRequest $request, FeedBack $feedback) {
        $feedback->update($request->validated());
        return redirect()->route('admin.feedbacks.index')->with('success', __("This row has been updated."));
    }

    public function destroy(Request $request, FeedBack $feedback) {
        $feedback->delete();
        return redirect()->route('admin.feedbacks.index')->with('success', __("This row has been deleted."));
    }
}
