<?php

namespace App\Http\Controllers\Dashboard\Clubs;

use App\Exports\ClubsExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\Clubs\CreateClubsRequest;
use App\Http\Requests\Dashboard\Clubs\UpdateClubsRequest;
use App\Models\Club\Image;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;

class ClubsController extends Controller
{

    public function index(Request $request)
    {
        $breadcrumb = [
            'title' =>  (isAdmin()) ? __("Clubs Lists") : __("My Club"),
            'items' =>  [
                [
                    'title' =>  (isAdmin()) ? __("Clubs Lists") : __("My Club"),
                    'url'   =>  '#!',
                ]
            ],
        ];
        $lists = User::whereHas('roles', function($q) {
            return $q->where('name', '=', User::TYPE_CLUB);
        });

        if(isAdmin()) {
            if(request()->has("suspend") && request("suspend") != "-1") {
                $lists = $lists->where("suspend",request("suspend"));
            }
            if(request()->has("name") && request("name") != "") {
                if(is_numeric(request("name"))) {
                    $lists = $lists->where("phone","like","%".request("name")."%");
                } else if(filter_var(request("name"), FILTER_VALIDATE_EMAIL)) {
                    $lists = $lists->where("email","like","%".request("name")."%");
                } else {
                    if(App::getLocale() == "ar") {
                        $lists = $lists->where("name","LIKE","%".request("name")."%");
                    } else {
                        $lists = $lists->where("name_en","LIKE","%".request("name")."%");
                    }
                }
            }
        } else {
            $lists = $lists->where("id",Auth::user()->id);
        }

        $lists = $lists->whereNotNull("accepted_at")->latest()->paginate();
        return view('admin.pages.clubs.index',compact('breadcrumb', 'lists'));
    }

    public function create()
    {
        $breadcrumb = [
            'title' =>  __("create new club"),
            'items' =>  [
                [
                    'title' =>  __("Clubs Lists"),
                    'url'   => route('admin.clubs.index'),
                ],
                [
                    'title' =>  __("create new club"),
                    'url'   =>  '#!',
                ],
            ],
        ];
        return view('admin.pages.clubs.edit', compact('breadcrumb'));
    }

    public function store(CreateClubsRequest $request)
    {
        $data                   = $request->all();
        $data['password']       = Hash::make($request->password);
        $data['suspend']        = 0;
        $data['avatar']         = (new \App\Support\Image)->FileUpload(request("logo"),"clubs");
        $data['accepted_at']    = Carbon::now();
        $club                   = User::create($data);
        foreach(request("images",[]) as $image) {
            $club->clubImages()->create([
                "image" => (new \App\Support\Image)->FileUpload($image,"clubs/$club->id")
            ]);
        }
        $club->categories()->sync(request("categories",[]));
        $club->assignRole(User::TYPE_CLUB);
        return Redirect::route('admin.clubs.index')->with('success', __(":item has been created.", ['item' => __('Clubs')]));
    }

    public function edit(User $club)
    {
        $breadcrumb = [
            'title' =>  __("edit club information"),
            'items' =>  [
                [
                    'title' =>  __("Clubs Lists"),
                    'url'   => route('admin.clubs.index'),
                ],
                [
                    'title' =>  __("edit club information"),
                    'url'   =>  '#!',
                ],
            ],
        ];
        return view('admin.pages.clubs.edit', compact('breadcrumb', 'club'));
    }

    public function update(UpdateClubsRequest $request, User $club)
    {
        $data = $request->all();
        if(request()->has('password') && !is_null(request('password'))) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }
        if(request()->has('logo') && !is_null(request('logo'))) {
            $data['avatar']   = (new \App\Support\Image)->FileUpload(request("logo"),"clubs");
        } else {
            unset($data['logo']);
        }
        $club->update($data);
        foreach(request("images",[]) as $image) {
            $club->clubImages()->create([
                "image" => (new \App\Support\Image)->FileUpload($image,"clubs/$club->id")
            ]);
        }

        $club->categories()->sync(request("categories",[]));

        if(isAdmin()) {
            return Redirect::route('admin.clubs.index')->with('success', __(":item has been updated.", ['item' => __('Clubs')]));
        } else {
            if(is_null(Auth::user()->rejected_at)) {
                return Redirect::route('admin.clubs.index')->with('success', __(":item has been updated.", ['item' => __('Clubs')]));
            } else {
                $club->update([
                    "accepted_at"   => null,
                    "rejected_at"   => null,
                ]);
                return Redirect::route('admin.home')->with('success', __(":item has been updated.", ['item' => __('Clubs')]));
            }
        }
    }

    public function destroy(Request $request,User $club) {
        $club->clubActivities()->delete();
        $club->clubBranches()->delete();
        $club->clubDues()->delete();
        $club->clubImages()->delete();
        $club->clubRates()->delete();
        $club->delete();
        return Redirect::route('admin.clubs.index')->with('success', __(":item has been deleted.",['item' => __('Clubs')]) );
    }

    public function imageDestroy(Request $request,User $club,Image $image) {
        $image->delete();
        return redirect()->back()->with('success', __(":item has been deleted.",['item' => __('Image')]) );
    }

    public function exportPdf(Request $request) {
        $lists = User::search($request->all())->whereHas('roles', function($q) {
                    return $q->where('name', User::TYPE_CLUB);
        })->whereNotNull("accepted_at")->latest()->get();
        $html = view('admin.pages.clubs.export', compact('lists'));
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
        return Excel::download(new ClubsExport($request->all()), "{$this->fileName()}.xlsx");
    }

    private function fileName() {
        return "clubs_".Carbon::now()->format("Y-m-d");
    }
}
