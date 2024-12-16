<?php

namespace App\Http\Controllers\Dashboard\Clubs;

use App\Exports\ActivitiesExport;
use App\Http\Controllers\Controller;
// Requests
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\Clubs\Activities\CreateRequest;
use App\Http\Requests\Dashboard\Clubs\Activities\UpdateRequest;
// Models
use App\Models\User;
use App\Models\Activity;
use Carbon\Carbon;
// Support
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ClubActivitiesController extends Controller
{

    public function index(Request $request)
    {
        $breadcrumb = [
            'title' =>  (isAdmin()) ? __("Club Activities") : __("My Activities"),
            'items' =>  [
                [
                    'title' =>  (isAdmin()) ? __("Club Activities") : __("My Activities"),
                    'url'   => "#!",
                ]
            ],
        ];
        $lists = (isAdmin()) ? new Activity : Auth::user()->clubActivities();


        if(request()->has("country_id") && request("country_id") != "-1") {
            $lists = $lists->whereHas("branch",function($q){
                return $q->where("country_id",request("country_id"));
            });
        }

        if(request()->has("disabilities") && request("disabilities") != "-1") {
            $lists = $lists->where("disabilities",request("disabilities"));
        }
        if(request()->has("order_one_time") && request("order_one_time") != "-1") {
            $lists = $lists->where("order_one_time",request("order_one_time"));
        }

        if(request()->has("name") && request("name") != "") {
            $lists = $lists->whereTranslationLike("name","%".request("name")."%");
        }


        $clubs = [];
        if(isAdmin()) {
            $clubs = User::whereHas('roles', function($q) {
                return $q->where('name', '=', User::TYPE_CLUB);
            })->whereNotNull("accepted_at")->where('suspend', 0)->get();

            if(request()->has("club_id") && request("club_id") != "-1") {
                $lists = $lists->where("club_id",request("club_id"));
            }
        }

        $lists = $lists->latest()->paginate();
        return view('admin.pages.clubs.activities.index',compact('breadcrumb', 'lists',"clubs"));
    }

    public function create()
    {
        $breadcrumb = [
            'title' =>  __("create new activity"),
            'items' =>  [
                [
                    'title' =>  (isAdmin()) ? __("Club Activities") : __("My Activities"),
                    'url'   => route('admin.clubs.activities.index'),
                ],
                [
                    'title' =>  __("create new activity"),
                    'url'   =>  '#!',
                ],
            ],
        ];
        if(isAdmin()) {
            $clubs = User::whereHas('roles', function($q) {
                return $q->where('name', '=', User::TYPE_CLUB);
            })->whereNotNull("accepted_at")->where('suspend', 0)->get();
            $branches   = [];
            $categories = [];
        } else {
            $branches   = Auth::user()->clubBranches()->get();
            $categories = Auth::user()->categories()->get();
        }
        return view('admin.pages.clubs.activities.edit',get_defined_vars());
    }

    public function store(CreateRequest $request)
    {
        $request = $request->validated();
        if(!isAdmin()) {
            $request["club_id"] = Auth::user()->id;
        }
        if(request()->hasFile('image')) {
            $request['image'] = (new \App\Support\Image)->FileUpload($request['image'], 'activities');
        }
        $request["customer_count"] = (request()->has("customer_count") && !is_null(request("customer_count"))) ? $request['customer_count'] : 0;
        $activity = Activity::create($request);
        $activity->categories()->sync([
            request("main_category",0),
            request("sub_category",0),
        ]);
        return redirect()->route('admin.clubs.activities.index')->with('success', __(":item has been created.", ['item' => __('Activities')]));
    }

    public function edit(Activity $activity)
    {
        $breadcrumb = [
            'title' =>  __("edit activity information"),
            'items' =>  [
                [
                    'title' =>  (isAdmin()) ? __("Club Activities") : __("My Activities"),
                    'url'   => route('admin.clubs.activities.index'),
                ],
                [
                    'title' =>  __("edit activity information"),
                    'url'   =>  '#!',
                ],
            ],
        ];
        $branches = [];
        if(isAdmin()) {
            $clubs = User::whereHas('roles', function($q) {
                return $q->where('name', '=', User::TYPE_CLUB);
            })->whereNotNull("accepted_at")->where('suspend', 0)->get();
        } else {
            $branches = Auth::user()->clubBranches()->get();
            $categories = Auth::user()->categories()->get();
        }
        return view('admin.pages.clubs.activities.edit', get_defined_vars());
    }

    public function update(UpdateRequest $request, Activity $activity)
    {
        $request = $request->validated();
        if(request()->hasFile('image')) {
            $request['image'] = (new \App\Support\Image)->FileUpload($request['image'], 'activities');
        }
        $activity->update($request);
        $activity->categories()->sync([
            request("main_category",0),
            request("sub_category",0),
        ]);
        return redirect()->route('admin.clubs.activities.index')->with('success', __(":item has been updated.", ['item' => __('Activities')]));
    }

    public function destroy(Request $request,Activity $activity) {
        $activity->delete();
        return redirect()->route('admin.clubs.activities.index')->with('success', __(":item has been deleted.",['item' => __('Activities')]) );
    }

    public function exportPdf(Request $request) {
        $lists = Activity::search($request->all())->latest()->get();
        $html = view('admin.pages.clubs.activities.export', compact('lists'));
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
        return Excel::download(new ActivitiesExport($request->all()), "{$this->fileName()}.xlsx");
    }

    private function fileName() {
        return "club_activities_".Carbon::now()->format("Y-m-d");
    }
}
