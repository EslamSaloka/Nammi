<?php

namespace App\Http\Controllers\Dashboard\Customers;

use App\Exports\CustomersExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\Customers\CreateCustomersRequest;
use App\Http\Requests\Dashboard\Customers\UpdateCustomersRequest;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;

class CustomersController extends Controller
{

    public function index(Request $request)
    {
        $breadcrumb = [
            'title' =>  __("Customers Lists"),
            'items' =>  [
                [
                    'title' =>  __("Customers Lists"),
                    'url'   =>  '#!',
                ]
            ],
        ];
        $lists = new User;
        if(request()->has("suspend") && request("suspend") != "-1") {
            $lists = $lists->where("suspend",request("suspend"));
        }
        if(request()->has("role_id") && request("role_id") != "-1") {
            $lists = $lists->whereHas('roles', function($q) {
                return $q->where('id', request("role_id"));
            });
        } else {
            $lists = $lists->whereHas('roles', function($q) {
                return $q->where('name', '=', User::TYPE_CUSTOMER);
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

        $lists = $lists->where("id","!=",1)->latest()->paginate();
        return view('admin.pages.customers.index',compact('breadcrumb', 'lists'));
    }

    public function create()
    {
        $breadcrumb = [
            'title' =>  __("create new customer"),
            'items' =>  [
                [
                    'title' =>  __("Customers Lists"),
                    'url'   => route('admin.customers.index'),
                ],
                [
                    'title' =>  __("create new customer"),
                    'url'   =>  '#!',
                ],
            ],
        ];
        return view('admin.pages.customers.edit', compact('breadcrumb'));
    }

    public function store(CreateCustomersRequest $request)
    {
        $data = $request->all();
        $data['password']           = Hash::make($request->password);
        $data['phone_verified_at']  = Carbon::now();
        $data['completed_at']       = Carbon::now();
        $data['suspend']            = 0;
        if($request->hasFile('avatar')) {
            $data['avatar'] = (new \App\Support\Image)->FileUpload($request->avatar, 'customers');
        }
        $customer = User::create($data);
        $customer->assignRole(User::TYPE_CUSTOMER);
        return Redirect::route('admin.customers.index')->with('success', __(":item has been created.", ['item' => __('customers')]));
    }

    public function edit(User $customer)
    {
        if($customer->id == Auth::user()->id) {
            return redirect()->route('admin.profile.index');
        }
        $breadcrumb = [
            'title' =>  __("edit customer information"),
            'items' =>  [
                [
                    'title' =>  __("Customers Lists"),
                    'url'   => route('admin.customers.index'),
                ],
                [
                    'title' =>  __("edit customer information"),
                    'url'   =>  '#!',
                ],
            ],
        ];
        return view('admin.pages.customers.edit', compact('breadcrumb', 'customer'));
    }

    public function update(UpdateCustomersRequest $request, User $customer)
    {
        $data = $request->all();
        if(request()->has('password') && !is_null(request('password'))) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }
        if( $request->hasFile('avatar') ) {
            $data['avatar'] = (new \App\Support\Image)->FileUpload($request->avatar, 'customers');
        }
        $customer->update($data);
        return Redirect::route('admin.customers.index', ['page' => $request->page ?? 1])->with('success', __(":item has been updated.", ['item' => __('customers')]));
    }

    public function destroy(Request $request,User $customer) {
        $customer->delete();
        return Redirect::route('admin.customers.index')->with('success', __(":item has been deleted.",['item' => __('customers')]) );
    }

    public function exportPdf(Request $request) {
        $lists = User::search($request->all())->whereHas('roles', function($q) {
                    return $q->where('name', User::TYPE_CUSTOMER);
        })->latest()->get();
        $html = view('admin.pages.customers.export', compact('lists'));
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
        return Excel::download(new CustomersExport($request->all()), "{$this->fileName()}.xlsx");
    }

    private function fileName() {
        return "customers_".Carbon::now()->format("Y-m-d");
    }
}
