<?php

namespace App\Http\Controllers\Dashboard\Admins;

use App\Exports\AdminsExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\Admins\CreateAdminsRequest;
use App\Http\Requests\Dashboard\Admins\UpdateAdminsRequest;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;

class AdminsController extends Controller
{

    public function index(Request $request)
    {
        $breadcrumb = [
            'title' =>  __("Admins lists"),
            'items' =>  [
                [
                    'title' =>  __("Admins lists"),
                    'url'   =>  '#!',
                ]
            ],
        ];
        $lists = new User;
        if(request()->has("suspend") && request("suspend") != "-1") {
            $lists = $lists->where("suspend",request("suspend"));
        }
        if(request()->has("role") && request("role") != "-1") {
            $lists = $lists->whereHas('roles', function($q) {
                return $q->where('id', request("role"));
            });
        } else {
            $lists = $lists->whereHas('roles', function($q) {
                return $q->where('name', '!=', User::TYPE_CLUB)->where("name",'!=',User::TYPE_CUSTOMER)->where("name",'!=',User::TYPE_BRANCH);
            });
        }

        if(request()->has("name") && request("name") != "") {
            if(is_numeric(request("name"))) {
                $lists = $lists->where("phone","like","%".request("name")."%");
            } else if(filter_var(request("name"), FILTER_VALIDATE_EMAIL)) {
                $lists = $lists->where("email","like","%".request("name")."%");
            } else {
                $lists = $lists->where("name","like","%".request("name")."%");
            }
        }

        if(request()->has("from_date") && request("from_date") != "") {
            $lists = $lists->whereDate('created_at', '>=', request('from_date'));
        }
        if(request()->has("to_date") && request("to_date") != "") {
            $lists = $lists->whereDate('created_at', '<=', request('to_date'));
        }

        $lists = $lists->where("id","!=",1)->latest()->paginate();
        $roles = Role::where('name', '!=', User::TYPE_CLUB)->where("name",'!=',User::TYPE_CUSTOMER)->where("name",'!=',User::TYPE_BRANCH)->get();
        return view('admin.pages.admins.index',compact('breadcrumb', 'lists',"roles"));
    }

    public function create()
    {
        $breadcrumb = [
            'title' =>  __("create new admin"),
            'items' =>  [
                [
                    'title' =>  __("Admins lists"),
                    'url'   => route('admin.admins.index'),
                ],
                [
                    'title' =>  __("create new admin"),
                    'url'   =>  '#!',
                ],
            ],
        ];
        $roles = Role::where('name', '!=', User::TYPE_CLUB)->where("name",'!=',User::TYPE_CUSTOMER)->where("name",'!=',User::TYPE_BRANCH)->get();
        return view('admin.pages.admins.edit', get_defined_vars());
    }

    public function store(CreateAdminsRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($request->password);
        $data['email_verified_at'] = Carbon::now();
        if($request->hasFile('avatar')) {
            $data['avatar'] = (new \App\Support\Image)->FileUpload($request->avatar, 'users');
        }
        $admin = User::create($data);
        $admin->assignRole(Role::find($request->role)->name);
        return redirect()->route('admin.admins.index')->with('success', __(":item has been created.", ['item' => __('Admin')]));
    }

    public function edit(User $admin)
    {
        if($admin->id == Auth::user()->id) {
            return redirect()->route('admin.profile.index');
        }
        $breadcrumb = [
            'title' =>  __("edit admin information"),
            'items' =>  [
                [
                    'title' =>  __("Admins lists"),
                    'url'   => route('admin.admins.index'),
                ],
                [
                    'title' =>  __("edit admin information"),
                    'url'   =>  '#!',
                ],
            ],
        ];
        $roles = Role::where('name', '!=', User::TYPE_CLUB)->where("name",'!=',User::TYPE_CUSTOMER)->where("name",'!=',User::TYPE_BRANCH)->get();
        return view('admin.pages.admins.edit', compact('breadcrumb', 'admin',"roles"));
    }

    public function update(UpdateAdminsRequest $request, User $admin)
    {
        $data = $request->validated();
        if(request()->has('password') && !is_null(request('password'))) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }
        if( $request->hasFile('avatar') ) {
            $data['avatar'] = (new \App\Support\Image)->FileUpload($request->avatar, 'users', $admin->avatar);
        }
        $admin->update($data);
        $admin->assignRole(Role::find($request->role)->name);
        return redirect()->route('admin.admins.index', ['page' => $request->page ?? 1])->with('success', __(":item has been updated.", ['item' => __('Admin')]));
    }

    public function destroy(Request $request,User $admin)
    {
        if($admin->id != 1) {
            $admin->delete();
        }
        return redirect()->route('admin.admins.index')->with('success', __(":item has been deleted.",['item' => __('Admin')]) );
    }

    public function exportPdf(Request $request) {
        $lists = User::search($request->all())->where("id","!=",1)->whereHas('roles', function($q) {
                    return $q->where('name', User::TYPE_ADMIN);
        })->latest()->get();
        $html = view('admin.pages.admins.export', compact('lists'));
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
        return Excel::download(new AdminsExport($request->all()), "{$this->fileName()}.xlsx");
    }

    private function fileName() {
        return "admins_".Carbon::now()->format("Y-m-d");
    }

}
