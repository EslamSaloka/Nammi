<?php

use App\Http\Controllers\Dashboard\Dues\RevenueController;
use Illuminate\Support\Facades\Route;

// Home
use App\Http\Controllers\Dashboard\Home\HomeController;
// Profile
use App\Http\Controllers\Dashboard\Profile\ProfileController;
// Settings
use App\Http\Controllers\Dashboard\Settings\SettingsController;
use App\Http\Controllers\Dashboard\Pages\PagesController;
use App\Http\Controllers\Dashboard\FAQS\FAQSController;
use App\Http\Controllers\Dashboard\Times\TimesController;
// Users Area
use App\Http\Controllers\Dashboard\Roles\RoleController;
use App\Http\Controllers\Dashboard\Admins\AdminsController;
use App\Http\Controllers\Dashboard\Categories\BannersController;
use App\Http\Controllers\Dashboard\Customers\CustomersController;
use App\Http\Controllers\Dashboard\Clubs\ClubsController;
// Categories
use App\Http\Controllers\Dashboard\Categories\CategoriesController;
use App\Http\Controllers\Dashboard\Categories\ChidersController;
use App\Http\Controllers\Dashboard\Chats\ChatsController;
use App\Http\Controllers\Dashboard\Cities\CitiesController;
use App\Http\Controllers\Dashboard\Clubs\ClubActivitiesController;
use App\Http\Controllers\Dashboard\Clubs\ClubActivitiesRatesController;
use App\Http\Controllers\Dashboard\Clubs\ClubBranchesController;
use App\Http\Controllers\Dashboard\Clubs\ClubRatesController;
use App\Http\Controllers\Dashboard\Clubs\ClubStaffsController;
// Messages
use App\Http\Controllers\Dashboard\Contacts\ContactsController;
use App\Http\Controllers\Dashboard\Countries\CountriesController;
use App\Http\Controllers\Dashboard\Counts\CountsController;
use App\Http\Controllers\Dashboard\Coupons\CouponsController;
use App\Http\Controllers\Dashboard\Dues\DuesController;
use App\Http\Controllers\Dashboard\Features\FeaturesController;
use App\Http\Controllers\Dashboard\Feedbacks\FeedbacksController;
use App\Http\Controllers\Dashboard\Notifications\NotificationsController;
use App\Http\Controllers\Dashboard\Orders\OrdersController;
use App\Http\Controllers\Dashboard\Reports\ReportsController;
use App\Http\Controllers\Dashboard\Requests\RequestsController;
use App\Http\Controllers\Dashboard\Screens\ScreensController;
use App\Http\Controllers\Dashboard\Sliders\SlidersController;
// ========================== //
// Support
use Illuminate\Support\Facades\Auth;
// ========================== //
// Requests
use App\Http\Requests\Dashboard\Auth\ForgetPasswordRequest;
use App\Http\Requests\Dashboard\Auth\ResetPasswordRequest;
// Models
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Route::middleware(['localeSessionRedirect','localizationRedirect','localeViewPath'])->prefix(LaravelLocalization::setLocale())->group(function(){

    Route::any('/callback',[\App\Http\Controllers\API\Orders\OrdersController::class, 'callBack']); // Done

    Route::get('/', function () {
        return view("welcome");
    });
    Route::get('/home', function () {
        return redirect("/dashboard");
    });
    Route::prefix('/dashboard')->name('admin.')->group(function () {
        Auth::routes();

        Route::get('/register', function () {
            return view("admin.pages.auth.register");
        })->name("register.index");

        Route::post('/register', function (\App\Http\Requests\Dashboard\Auth\RegisterRequest $request) {
            $request = $request->validated();
            $request["password"] = Hash::make($request['password']);
            $request["avatar"]   = (new \App\Support\Image)->FileUpload($request['logo'],"clubs");
            $club = \App\Models\User::create($request);
            $club->assignRole(\App\Models\User::TYPE_CLUB);
            $club->clubImages()->create([
                "image" => $request["avatar"]
            ]);
            $club->categories()->sync(request('categories',[]));
            Auth::login($club);
            return redirect("/dashboard");
        })->name("register.store");

        // ================ //
        Route::get('/forget-password', function(){
            return view("admin.pages.auth.forget-password");
        })->name('forgetPassword.index');
        // ================ //
        Route::post('/forget-password', function(ForgetPasswordRequest $request) {
            $user = User::where("phone",$request->phone)->first();
            GenerateAndSendOTP($user);
            return redirect("/dashboard/reset-password?phone=$user->phone");
        })->name('forgetPassword.send');
        // ================ //

        Route::get('/reset-password', function() {
            return view("admin.pages.auth.reset-password");
        })->name('resetPassword.index');
        // ================ //
        Route::post('/reset-password', function(ResetPasswordRequest $request) {
            $user = User::where("phone",$request->phone)->first();
            if($user->otp != $request->otp) {
                return redirect()->back()->with("danger",__("Incorrect verification code"));
            }
            $user->update([
                "password"  => \Hash::make($request->password)
            ]);
            return redirect()->route("admin.login");
        })->name('resetPassword.send');


        // ================ //
        Route::middleware(["auth","auth.dashboard","auth.dashboardSuspend","auth.userAccessDashboard"])->group(function () {
            // Home
            Route::get('/', [HomeController::class, 'index'])->name('home');
            Route::get('/home', [HomeController::class, 'index'])->name('home.index');
            // ============================================================ //
            // ============================================================ //
            // ============================================================ //
            // ============================================================ //
            // Profile
            Route::get('/profile', [ProfileController::class,'index'])->name('profile.index');
            Route::post('/profile', [ProfileController::class,'store'])->name('profile.store');
            Route::get('/change_password', [ProfileController::class, 'change_password'])->name('change_password.index');
            Route::post('/change_password', [ProfileController::class, 'update_password'])->name('change_password.store');
             // Logout
             Route::get('/logout', [HomeController::class,'logout'])->name('logout');
            // ============================================================ //
            // ============================================================ //
            // ============================================================ //
            // ============================================================ //
            // Settings
            Route::prefix('settings')->name('settings.')->group(function () {
                Route::get('/', [SettingsController::class,'index'])->name('index');
                Route::get('/{group_by}', [SettingsController::class,'edit'])->name('edit');
                Route::put('/{group_by}', [SettingsController::class,'update'])->name('update');
            });
            // Times Hobbies
            Route::resource('/times', TimesController::class);
            // Contact US
            Route::resource('/contacts', ContactsController::class);
            Route::post('/contacts/excel', [ContactsController::class,"exportExcel"])->name("contacts.export.excel");
            Route::post('/contacts/pdf', [ContactsController::class,"exportPdf"])->name("contacts.export.pdf");
            // Pages
            Route::resource('/pages', PagesController::class)->only(["index","edit","update"]);
            // FAQS
            Route::resource('/faqs', FAQSController::class);
            // ============================================================ //
            // ============================================================ //
            // ============================================================ //
            // ============================================================ //
            // Roles
            Route::resource('/roles', RoleController::class);
            // Admins
            Route::resource('/admins', AdminsController::class);
            Route::post('/admins/excel', [AdminsController::class,"exportExcel"])->name("admins.export.excel");
            Route::post('/admins/pdf', [AdminsController::class,"exportPdf"])->name("admins.export.pdf");
            // Customers
            Route::resource('/customers', CustomersController::class);
            Route::post('/customers/excel', [CustomersController::class,"exportExcel"])->name("customers.export.excel");
            Route::post('/customers/pdf', [CustomersController::class,"exportPdf"])->name("customers.export.pdf");
            // ================================================================== //
            // Clubs
            Route::prefix('/clubs')->name('clubs.')->group(function () {
                Route::get('/branches/getAll', [ClubBranchesController::class,"getAll"])->name("branches.getAll");
                Route::resource('/branches', ClubBranchesController::class);
                Route::post('/branches/excel', [ClubBranchesController::class,"exportExcel"])->name("branches.export.excel");
                Route::post('/branches/pdf', [ClubBranchesController::class,"exportPdf"])->name("branches.export.pdf");
                // ================================================================= //
                Route::resource('/activities', ClubActivitiesController::class);
                Route::prefix('/activities')->name('activities.')->group(function () {
                    Route::post('/excel', [ClubActivitiesController::class,"exportExcel"])->name("export.excel");
                    Route::post('/pdf', [ClubActivitiesController::class,"exportPdf"])->name("export.pdf");
                    Route::get('/{activity}/rates/{rate}/confirmed', [ClubActivitiesRatesController::class,"confirmed"])->name("rates.confirmed")->where("activity","[0-9]+")->where("rate","[0-9]+");
                    Route::resource('/{activity}/rates', ClubActivitiesRatesController::class);
                });
                // ================================================================= //
                Route::get('/{club}/rates/{rate}/confirmed', [ClubRatesController::class,"confirmed"])->name("rates.confirmed")->where("club","[0-9]+")->where("rate","[0-9]+");
                Route::resource('/{club}/rates', ClubRatesController::class);
                Route::get('/{club}/images/{image}', [ClubsController::class,"imageDestroy"])->name("images.destroy")->where("club","[0-9]+")->where("image","[0-9]+");
                // ================================================================= //
                Route::get('/staffs/getAll', [ClubStaffsController::class,"getAll"])->name("staffs.getAll");
                Route::resource('/staffs', ClubStaffsController::class);
            });
            Route::resource('/clubs', ClubsController::class);
            Route::post('/clubs/excel', [ClubsController::class,"exportExcel"])->name("clubs.export.excel");
            Route::post('/clubs/pdf', [ClubsController::class,"exportPdf"])->name("clubs.export.pdf");
            // ============================================================ //
            // ============================================================ //
            // ============================================================ //
            // ============================================================ //
            // Categories
            Route::prefix('/categories')->name('categories.')->group(function () {
                Route::resource('/{category}/banners', BannersController::class);
                Route::resource('/{category}/chiders', ChidersController::class);
                Route::post('/{category}/chiders/excel', [ChidersController::class,"exportExcel"])->name("chiders.export.excel");
                Route::post('/{category}/chiders/pdf', [ChidersController::class,"exportPdf"])->name("chiders.export.pdf");
            });
            Route::get('/categories/getAll', [CategoriesController::class,"getAll"])->name("categories.getAll");
            Route::get('/categories/child', [CategoriesController::class,"getChild"])->name("categories.child");
            Route::resource('/categories', CategoriesController::class);
            Route::post('/categories/excel', [CategoriesController::class,"exportExcel"])->name("categories.export.excel");
            Route::post('/categories/pdf', [CategoriesController::class,"exportPdf"])->name("categories.export.pdf");
            // ============================================================ //
            // ============================================================ //
            // ============================================================ //
            // ============================================================ //
            // Countries & Cities
            Route::prefix('/countries')->name('countries.')->group(function () {
                Route::resource('/{country}/cities', CitiesController::class);
                Route::get('/getCities', [CitiesController::class,"getCities"])->name("getCities");
            });
            Route::resource('/countries', CountriesController::class);
            // ============================================================ //
            // ============================================================ //
            // ============================================================ //
            // ============================================================ //
            // Orders
            Route::get('/orders/excel', [OrdersController::class,"exportExcel"])->name("orders.export.excel");
            Route::get('/orders/pdf', [OrdersController::class,"exportPdf"])->name("orders.export.pdf");
            Route::resource('/orders', OrdersController::class);
            Route::post('/orders/{order}/change-status', [OrdersController::class,"changeStatus"])->name("orders.change-status")->where("order","[0-9]+");
            Route::get('/orders/{order}/otp', [OrdersController::class,"reSendOtp"])->name("orders.re-send")->where("order","[0-9]+");
            Route::post('/orders/{order}/otp', [OrdersController::class,"customerOtp"])->name("orders.check-otp")->where("order","[0-9]+");
            // ============================================================ //
            // ============================================================ //
            // ============================================================ //
            // ============================================================ //
            // Coupons
            Route::resource('/coupons', CouponsController::class);
            // ============================================================ //
            // ============================================================ //
            // ============================================================ //
            // ============================================================ //
            // Counts
            Route::resource('/counts', CountsController::class);
            // ============================================================ //
            // ============================================================ //
            // ============================================================ //
            // ============================================================ //
            // Features
            Route::resource('/features', FeaturesController::class);
            // ============================================================ //
            // ============================================================ //
            // ============================================================ //
            // ============================================================ //
            // Feedbacks
            Route::resource('/feedbacks', FeedbacksController::class);
            // ============================================================ //
            // ============================================================ //
            // ============================================================ //
            // ============================================================ //
            // Screens
            Route::resource('/screens', ScreensController::class)->only(["index","store","destroy"]);
            // ============================================================ //
            // ============================================================ //
            // ============================================================ //
            // ============================================================ //
            // Sliders
            Route::resource('/sliders', SlidersController::class);
            // ============================================================ //
            // ============================================================ //
            // ============================================================ //
            // ============================================================ //
            // Notifications
            Route::resource('/notifications', NotificationsController::class);
            // ============================================================ //
            // ============================================================ //
            // ============================================================ //
            // ============================================================ //
            // Reports
            Route::resource('/reports', ReportsController::class);
            // ============================================================ //
            // ============================================================ //
            // ============================================================ //
            // ============================================================ //
            // Requests
            Route::resource('/requests', RequestsController::class);
            Route::post('/requests/{request}/accept', [RequestsController::class,"accept"])->where("accept","[0-9]+")->name("requests.accept");
            Route::post('/requests/{request}/reject', [RequestsController::class,"reject"])->where("reject","[0-9]+")->name("requests.reject");
            // ============================================================ //
            // ============================================================ //
            // ============================================================ //
            // ============================================================ //
            // Dues
            Route::get('/dues', [DuesController::class,"index"])->name("dues.index");
            Route::get('/dues/export', [DuesController::class,"exportAll"])->name("dues.export");
            Route::get('/dues/{club}', [DuesController::class,"show"])->name("dues.show")->where('club',"[0-9]+");
            Route::get('/dues/{club}/export', [DuesController::class,"exportClub"])->name("dues.club.export")->where('club',"[0-9]+");
            Route::get('/dues/{club}/confirmed', [DuesController::class,"confirmed"])->name("dues.confirmed")->where('club',"[0-9]+");
            // ============================================================ //
            // ============================================================ //
            // Club Revenue
            Route::get('/revenue', [RevenueController::class,"index"])->name("revenue.index");
            Route::get('/revenue/export', [RevenueController::class,"exportAll"])->name("revenue.export");
            Route::get('/revenue/{club}', [RevenueController::class,"show"])->name("revenue.show")->where('club',"[0-9]+");
            Route::get('/revenue/{club}/export', [RevenueController::class,"exportClub"])->name("revenue.club.export")->where('club',"[0-9]+");
            Route::get('/revenue/{club}/confirmed', [RevenueController::class,"confirmed"])->name("revenue.confirmed")->where('club',"[0-9]+");
            // ============================================================ //
            // ============================================================ //
            // Chats
            Route::resource('/chats', ChatsController::class);
        });
    });
});

