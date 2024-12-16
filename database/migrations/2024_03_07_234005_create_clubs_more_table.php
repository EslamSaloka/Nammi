<?php

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
        Schema::create('activate_categories_pivot', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->index();
            $table->unsignedBigInteger('activity_id')->index();
            $table->timestamps();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('activity_id')->references('id')->on('activities')->onDelete('cascade');
        });

        Schema::create('activate_rates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->index();
            $table->unsignedBigInteger('activity_id')->index();
            // =================================== //
            $table->float("rate")->default(0);
            $table->longText('notes')->nullable();
            $table->boolean('confirmed')->nullable()->default(false);
            // =================================== //
            $table->timestamps();
            // =================================== //
            $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('activity_id')->references('id')->on('activities')->onDelete('cascade');
        });

        Schema::table('activities', function (Blueprint $table) {
            $table->float('rates')->default(0);
            $table->boolean('order_one_time')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activate_categories_pivot');
        Schema::dropIfExists('activate_rates');
    }
};
