<?php

// Support
use Illuminate\Support\Facades\Route;


Route::prefix('/auth')->group(function(){
    Route::post("login",[\App\Http\Controllers\API\Auth\LoginController::class,"index"]);
    // ==================================== //
    Route::post("login-register-social",[\App\Http\Controllers\API\Auth\LoginSocialController::class,"index"]);
    // ==================================== //
    Route::post("register-by-phone",[\App\Http\Controllers\API\Auth\RegisterPhoneController::class,"index"]);
    // ==================================== //
    Route::post("complete-account",[\App\Http\Controllers\API\Auth\CompletedAccountController::class,"index"])->middleware(["auth:sanctum","auth.customer"]);
    // ==================================== //
    Route::post("verify-account",[\App\Http\Controllers\API\Auth\VerifyAccountController::class,"index"]);
    // ==================================== //
    Route::post("forget-password",[\App\Http\Controllers\API\Auth\ForgetPasswordController::class,"index"]);
    // ==================================== //
    Route::post("otp",[\App\Http\Controllers\API\Auth\OTPController::class,"index"])->middleware(["auth:sanctum","auth.customer"]);
    // ==================================== //
    Route::post("reset-password",[\App\Http\Controllers\API\Auth\ResetPasswordController::class,"index"])->middleware(["auth:sanctum","auth.customer","auth.verify"]);
    // ==================================== //
    Route::post("register",[\App\Http\Controllers\API\Auth\RegisterController::class,"store"]);
    // ==================================== //
    Route::post("re-send",[\App\Http\Controllers\API\Auth\ReSendController::class,"index"])->middleware(["auth:sanctum","auth.customer"]);
    // ==================================== //
});

Route::prefix('/settings')->group(function(){
    Route::get("/",[\App\Http\Controllers\API\Settings\SettingsController::class,"index"]);
    Route::get("about-us",[\App\Http\Controllers\API\Settings\PagesController::class,"aboutIndex"]);
    Route::get("policy",[\App\Http\Controllers\API\Settings\PagesController::class,"policyIndex"]);
    Route::get("terms",[\App\Http\Controllers\API\Settings\PagesController::class,"termsIndex"]);
    Route::get("faq",[\App\Http\Controllers\API\Settings\PagesController::class,"FAQIndex"]);
    // ==================================== //
    Route::post("contact-us",[\App\Http\Controllers\API\Settings\ContactController::class,"store"]);
    // ==================================== //
    Route::get("time-of-play-hobby",[\App\Http\Controllers\API\Settings\TimeOfPlayHobbyController::class,"index"]);
    // ==================================== //
    Route::get("categories",[\App\Http\Controllers\API\Settings\CategoriesController::class,"index"]);
    Route::get("get-all-sub-categories",[\App\Http\Controllers\API\Settings\CategoriesController::class,"getAllSubCategories"]);
    Route::get("categories/{category}",[\App\Http\Controllers\API\Settings\CategoriesController::class,"show"])->where("category","[0-9]+");
    Route::get("categories/{category}/banners",[\App\Http\Controllers\API\Settings\CategoriesController::class,"getCategoryBanners"])->where("category","[0-9]+");
    Route::get("get-categories-pluck",[\App\Http\Controllers\API\Settings\CategoriesController::class,"showAllPluck"])->where("category","[0-9]+");
    // ==================================== //
    Route::get("areas",[\App\Http\Controllers\API\Settings\AreasController::class,"index"]);
});


Route::middleware(['auth:sanctum',"auth.customer","auth.verify","user.checkArea"])->prefix('/my-profile')->group(function(){
    Route::get("/",[\App\Http\Controllers\API\Profile\ProfileController::class,"index"]);
    Route::put("/",[\App\Http\Controllers\API\Profile\ProfileController::class,"update"]);
    Route::put("/update-fire-base-token",[\App\Http\Controllers\API\Profile\ProfileController::class,"updateFireBaseToken"]);
    // ==================================== //
    Route::put("/change-password",[\App\Http\Controllers\API\Profile\ProfileController::class,"updatePassword"]);
    Route::post("/change-avatar",[\App\Http\Controllers\API\Profile\ProfileController::class,"updateAvatar"]);
    // ==================================== //
    Route::prefix("notifications")->group(function(){
        Route::get("/",[\App\Http\Controllers\API\Profile\NotificationController::class,"index"]);
        Route::get("/{notification}",[\App\Http\Controllers\API\Profile\NotificationController::class,"show"])->where("notification","[0-9]+");
        Route::delete("/{notification}",[\App\Http\Controllers\API\Profile\NotificationController::class,"destroy"])->where("notification","[0-9]+");
        // ======================================== //
        Route::get("/read-all-notifications",[\App\Http\Controllers\API\Profile\NotificationController::class,"readAll"]);
        Route::get("/delete-all-notifications",[\App\Http\Controllers\API\Profile\NotificationController::class,"destroyAll"]);
    });
    // ==================================== //
    Route::get("/favorites",[\App\Http\Controllers\API\Profile\FavoritesController::class,"index"]);
    Route::post("/favorites",[\App\Http\Controllers\API\Profile\FavoritesController::class,"store"]);
    // ==================================== //
    Route::get("/orders",[\App\Http\Controllers\API\Orders\OrdersController::class,"index"]);
    Route::get("/orders/{order}",[\App\Http\Controllers\API\Orders\OrdersController::class,"show"])->where("order","[0-9]+");
    Route::post("/orders",[\App\Http\Controllers\API\Orders\OrdersController::class,"store"]);
    Route::post("/orders/{order}/rates",[\App\Http\Controllers\API\Orders\OrdersController::class,"rate"])->where("order","[0-9]+");
    Route::get("/orders/{order}/accept-change-time",[\App\Http\Controllers\API\Orders\OrdersController::class,"acceptChangeTime"])->where("order","[0-9]+");
    Route::delete("/orders/{order}",[\App\Http\Controllers\API\Orders\OrdersController::class,"destroy"])->where("order","[0-9]+");
    // ==================================== //
    Route::post("/check-coupon",[\App\Http\Controllers\API\Coupon\CouponController::class,"index"]);
    // ==================================== //
    Route::get("/logout",[\App\Http\Controllers\API\Profile\ProfileController::class,"logout"]);
    Route::get("/delete-account",[\App\Http\Controllers\API\Profile\ProfileController::class,"deleteAccount"]);


    // ***************************************************
    // ********************* Chats *********************
    // ***************************************************
    Route::prefix("chats")->group(function(){
        Route::get("/",[\App\Http\Controllers\API\Chat\ChatController::class,"index"]);
        Route::get("/{chat}",[\App\Http\Controllers\API\Chat\ChatController::class,"show"])->where("chat","[0-9]+");
        Route::get("/{chat}/message/{message}/delete",[\App\Http\Controllers\API\Chat\ChatController::class,"delete"])->where("chat","[0-9]+")->where("message","[0-9]+");
        Route::post("/",[\App\Http\Controllers\API\Chat\ChatController::class,"store"]);
        Route::post("/search",[\App\Http\Controllers\API\Chat\ChatController::class,"search"]);
    });
});

Route::middleware(["auth.checkIfIAuth","auth.customer","user.checkArea"])->group(function(){
    Route::get("/home",[\App\Http\Controllers\API\Home\HomeController::class,"index"]);
    // ==================================== //
    Route::get("/offers",[\App\Http\Controllers\API\Offers\OffersController::class,"index"]);
    // ==================================== //
    Route::get("/clubs",[\App\Http\Controllers\API\Clubs\ClubsController::class,"index"]);
    Route::get("/clubs/{club}",[\App\Http\Controllers\API\Clubs\ClubsController::class,"show"])->where("club","[0-9]+");
    Route::get("/clubs/{club}/rates",[\App\Http\Controllers\API\Clubs\ClubsController::class,"rates"])->where("club","[0-9]+");
    Route::get("/clubs/{club}/activities",[\App\Http\Controllers\API\Clubs\ClubsController::class,"activities"])->where("club","[0-9]+");
    Route::get("/clubs/{club}/activities/{activity}",[\App\Http\Controllers\API\Clubs\ClubsController::class,"showActivity"])->where("club","[0-9]+")->where("activity","[0-9]+");
    Route::get("/clubs/{club}/activities/{activity}/rates",[\App\Http\Controllers\API\Clubs\ClubsController::class,"showActivityRates"])->where("club","[0-9]+")->where("activity","[0-9]+");
});
