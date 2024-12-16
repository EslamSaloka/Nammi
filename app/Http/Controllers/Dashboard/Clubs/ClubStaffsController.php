<?php

namespace App\Http\Controllers\Dashboard\Clubs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\Clubs\Staffs\CreateRequest;
use App\Http\Requests\Dashboard\Clubs\Staffs\UpdateRequest;
use App\Models\Club\Staff;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ClubStaffsController extends Controller
{

    public function index(Request $request)
    {
        $breadcrumb = [
            'title' =>  (isAdmin()) ? __("Club Staffs") : __("My Staffs"),
            'items' =>  [
                [
                    'title' =>  (isAdmin()) ? __("Club Staffs") : __("My Staffs"),
                    'url'   => "#!",
                ]
            ],
        ];


        if(isAdmin()) {
            $lists = User::whereHas("roles",function($q){
                return $q->where("name",'=',User::TYPE_BRANCH);
            });
        } else {
            $ids    = Staff::where("club_id",Auth::user()->id)->pluck("user_id")->toArray();
            $lists  = User::whereIn("id",$ids);
        }


        if(request()->has("name") && request("name") != "") {
            if(is_numeric(request("name"))) {
                $lists = $lists->where("phone","like","%".request("name")."%");
            } else if(filter_var(request("name"), FILTER_VALIDATE_EMAIL)) {
                $lists = $lists->where("email","like","%".request("name")."%");
            } else {
                $lists = $lists->where("name","LIKE","%".request("name")."%");
            }
        }

        $clubs = [];
        if(isAdmin()) {
            if(request()->has("club_id") && request("club_id") != "-1") {
                $lists = $lists->where("club_id",request("club_id"));
            }

            $clubs = User::whereHas("roles",function($q){
                return $q->where("name",'=',User::TYPE_CLUB);
            })->whereNotNull("accepted_at")->where('suspend', 0)->get();

        }

        $lists = $lists->latest()->paginate();
        return view('admin.pages.clubs.staffs.index',compact('breadcrumb', 'lists',"clubs"));
    }

    public function create()
    {
        $breadcrumb = [
            'title' =>  __("create new staff"),
            'items' =>  [
                [
                    'title' =>  (isAdmin()) ? __("Club Staffs") : __("My Staffs"),
                    'url'   => route('admin.clubs.staffs.index'),
                ],
                [
                    'title' =>  __("create new staff"),
                    'url'   =>  '#!',
                ],
            ],
        ];
        if(isAdmin()) {
            $clubs = User::whereHas('roles', function($q) {
                return $q->where('name', '=', User::TYPE_CLUB);
            })->whereNotNull("accepted_at")->where('suspend', 0)->get();
        }
        return view('admin.pages.clubs.staffs.edit',get_defined_vars());
    }

    public function store(CreateRequest $request)
    {
        $request                   = $request->validated();
        $request['suspend']        = 0;
        $request['avatar']         = (new \App\Support\Image)->FileUpload(request("avatar"),"staff");
        $request['accepted_at']    = Carbon::now();
        $request['password']       = Hash::make($request['password']);
        $st = User::create($request);
        $st->assignRole(User::TYPE_BRANCH);
        Staff::create([
            "club_id"   => (!isAdmin()) ? Auth::user()->id : $request['club_id'],
            "user_id"   => $st->id,
        ]);
        return redirect()->route('admin.clubs.staffs.index')->with('success', __(":item has been created.", ['item' => __('Branches')]));
    }

    private function checkClubStaff(User $staff) {
        $check = Staff::where([
            "club_id"   => Auth::user()->id,
            "user_id"   => $staff->id,
        ])->first();
        if(is_null($check)) {
            return false;
        }
        return true;
    }

    public function edit(User $staff)
    {
        if(!isAdmin()) {
            if($this->checkClubStaff($staff) === false) {
                abort(403);
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
        return view('admin.pages.clubs.staffs.edit', get_defined_vars());
    }

    public function update(UpdateRequest $request, User $staff)
    {
        if(!isAdmin()) {
            if($this->checkClubStaff($staff) === false) {
                abort(403);
            }
        }
        $request = $request->validated();
        if(request()->has('password') && !is_null(request('password'))) {
            $request['password'] = Hash::make($request['password']);
        } else {
            unset($request['password']);
        }
        if(request()->has('avatar') && !is_null(request('avatar'))) {
            $request['avatar']   = (new \App\Support\Image)->FileUpload(request("avatar"),"staff");
        } else {
            unset($request['avatar']);
        }
        $staff->update($request);
        if(Staff::where(["user_id"=>$staff->id])->count() > 0) {
            Staff::where(["user_id"=>$staff->id])->update([
                "club_id"   => (!isAdmin()) ? Auth::user()->id : $request['club_id'],
            ]);
        } else {
            Staff::create([
                "club_id"   => (!isAdmin()) ? Auth::user()->id : $request['club_id'],
                "user_id"   => $staff->id,
            ]);
        }
        return redirect()->route('admin.clubs.staffs.index')->with('success', __(":item has been updated.", ['item' => __('Branches')]));
    }

    public function destroy(Request $request,User $staff) {
        if(!isAdmin()) {
            if($this->checkClubStaff($staff) === false) {
                abort(403);
            }
        }
        $staff->delete();
        Staff::where(["user_id"=>$staff->id])->delete();
        return redirect()->route('admin.clubs.staffs.index')->with('success', __(":item has been deleted.",['item' => __('Branches')]) );
    }

    public function getAll() {
        $ids    = Staff::where("club_id",request('club_id'))->pluck("user_id")->toArray();
        $menus  = User::whereIn("id",$ids)->get();
        $data = [
            'label'     => "Branch Master",
            'name'      => 'user_id',
            'required'  => true,
            'data'      => $menus,
            'keyV'      => "name",
            'value'     => null
        ];
        return view("admin.component.ajaxSelect",["data"=>$data])->render();
    }
}
