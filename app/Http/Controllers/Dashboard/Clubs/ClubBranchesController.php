<?php

namespace App\Http\Controllers\Dashboard\Clubs;

use App\Exports\BranchesExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\Clubs\Branches\CreateRequest;
use App\Http\Requests\Dashboard\Clubs\Branches\UpdateRequest;
use App\Models\User;
use App\Models\Club\Branch;
use App\Models\Country;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ClubBranchesController extends Controller
{

    public function index(Request $request)
    {
        $breadcrumb = [
            'title' =>  (isAdmin()) ? __("Club Branches") : __("My Branches"),
            'items' =>  [
                [
                    'title' =>  (isAdmin()) ? __("Club Branches") : __("My Branches"),
                    'url'   => "#!",
                ]
            ],
        ];

        if(!isAdmin()) {
            if (isBranchMaster()) {
                $lists = Branch::where('user_id',Auth::user()->id);
            } else {
                $lists = Auth::user()->clubBranches();
            }
        } else {
            $lists = new Branch;
        }

        $clubs = [];
        if(isAdmin()) {
            if(request()->has("country_id") && request("country_id") != "-1") {
                $lists = $lists->where("country_id",request("country_id"));
            }

            if(request()->has("city_id") && request("city_id") != "-1") {
                $lists = $lists->where("city_id",request("city_id"));
            }

            if(request()->has("name") && request("name") != "") {
                $lists = $lists->whereTranslationLike("name","%".request("name")."%");
            }

            if(request()->has("club_id") && request("club_id") != "-1") {
                $lists = $lists->where("club_id",request("club_id"));
            }


            $clubs = \App\Models\User::whereHas("roles",function($q){
                return $q->where("name",'=',\App\Models\User::TYPE_CLUB);
            })->whereNotNull("accepted_at")->where('suspend', 0)->get();

        }

        $lists = $lists->latest()->paginate();
        return view('admin.pages.clubs.branches.index',compact('breadcrumb', 'lists',"clubs"));
    }

    public function create()
    {
        $breadcrumb = [
            'title' =>  __("create new branch"),
            'items' =>  [
                [
                    'title' =>  (isAdmin()) ? __("Club Branches") : __("My Branches"),
                    'url'   => route('admin.clubs.branches.index'),
                ],
                [
                    'title' =>  __("create new branch"),
                    'url'   =>  '#!',
                ],
            ],
        ];
        if(isAdmin()) {
            $clubs = User::whereHas('roles', function($q) {
                return $q->where('name', '=', User::TYPE_CLUB);
            })->whereNotNull("accepted_at")->where('suspend', 0)->get();
        }

        $countries = Country::all();

        return view('admin.pages.clubs.branches.edit',get_defined_vars());
    }

    public function store(CreateRequest $request)
    {
        $request = $request->validated();
        if(!isAdmin()) {
            $request["club_id"] = Auth::user()->id;
        }
        Branch::create($request);
        return redirect()->route('admin.clubs.branches.index')->with('success', __(":item has been created.", ['item' => __('Branches')]));
    }

    public function edit(Branch $branch)
    {
        if(!isAdmin()) {
            if(isBranchMaster()) {
                if($branch->user_id != Auth::user()->id) {
                    abort(403);
                }
            }
        }
        $breadcrumb = [
            'title' =>  __("edit branch information"),
            'items' =>  [
                [
                    'title' =>  (isAdmin()) ? __("Club Branches") : __("My Branches"),
                    'url'   => route('admin.clubs.branches.index'),
                ],
                [
                    'title' =>  __("edit branch information"),
                    'url'   =>  '#!',
                ],
            ],
        ];
        if(isAdmin()) {
            $clubs = User::whereHas('roles', function($q) {
                return $q->where('name', '=', User::TYPE_CLUB);
            })->whereNotNull("accepted_at")->where('suspend', 0)->get();
        }

        $countries = Country::all();
        return view('admin.pages.clubs.branches.edit', get_defined_vars());
    }

    public function update(UpdateRequest $request, Branch $branch)
    {
        if(!isAdmin()) {
            if(isBranchMaster()) {
                if($branch->user_id != Auth::user()->id) {
                    abort(403);
                }
            }
        }
        $request = $request->validated();
        if(!isAdmin()) {
            $request["club_id"] = Auth::user()->id;
        }
        $branch->update($request);
        return redirect()->route('admin.clubs.branches.index')->with('success', __(":item has been updated.", ['item' => __('Branches')]));
    }

    public function destroy(Request $request,Branch $branch) {
        $branch->delete();
        return redirect()->route('admin.clubs.branches.index')->with('success', __(":item has been deleted.",['item' => __('Branches')]) );
    }

    public function getAll() {
        $menus = Branch::where([
            'club_id'   => request("club"),
        ])->get();
        $data = [
            'label'     => "Branch",
            'name'      => 'branch_id',
            'required'  => true,
            'data'      => $menus,
            'keyV'      => "name",
            'value'     => null
        ];
        return view("admin.component.ajaxSelect",["data"=>$data])->render();
    }

    public function exportPdf(Request $request) {
        $lists = Branch::search($request->all())->latest()->get();
        $html = view('admin.pages.clubs.branches.export', compact('lists'));
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
        return Excel::download(new BranchesExport($request->all()), "{$this->fileName()}.xlsx");
    }

    private function fileName() {
        return "club_branches_".Carbon::now()->format("Y-m-d");
    }
}
