<?php

use App\Models\Order;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->index();
            $table->unsignedBigInteger('club_id')->index();
            $table->unsignedBigInteger('activity_id')->index();
            $table->unsignedBigInteger('branch_id')->index();
            // $table->unsignedBigInteger('country_id')->index();
            // $table->unsignedBigInteger('city_id')->index();
            // =================================== //
            $table->bigInteger('coupon_id')->default(0);
            // =================================== //
            $table->float("sub_price")->default(0);
            $table->float("coupon_price")->default(0);
            $table->float("total")->default(0);
            // =================================== //
            $table->string("order_status")->default(Order::STATUS_PENDING);
            $table->string("cancel_by")->nullable();
            // =================================== //
            $table->string('name')->nullable();
            $table->string('mobile')->nullable();
            $table->string('date')->nullable();
            $table->string('time')->nullable();
            $table->longText('notes')->nullable();
            // =================================== //
            $table->string("payment_type")->default("visa");
            $table->string("payment_status")->default("pending");
            $table->string('invoiceId')->nullable();
            $table->string('transaction_id')->nullable();
            // =================================== //
            $table->timestamps();
            // =================================== //
            $table->foreign('customer_id')->references('id')->on('users');
            $table->foreign('club_id')->references('id')->on('users');
            $table->foreign('activity_id')->references('id')->on('activities');
            $table->foreign('branch_id')->references('id')->on('club_branches');
            // $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
            // $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
        });

        Schema::create('order_rates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->index();
            $table->unsignedBigInteger('club_id')->index();
            $table->unsignedBigInteger('order_id')->index();
            // =================================== //
            $table->float("rate")->default(0);
            $table->longText('notes')->nullable();
            $table->boolean('confirmed')->nullable()->default(false);
            // =================================== //
            $table->timestamps();
            // =================================== //
            $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('club_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });

        Schema::create('order_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->index();
            // =================================== //
            $table->string("order_status")->nullable();
            $table->timestamps();
            // =================================== //
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_rates');
        Schema::dropIfExists('order_histories');
        Schema::dropIfExists('orders');
    }
};
