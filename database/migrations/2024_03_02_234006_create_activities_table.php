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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('club_id')->index();
            // $table->unsignedBigInteger('category_id')->index();
            $table->unsignedBigInteger('branch_id')->index();
            // $table->unsignedBigInteger('country_id')->index();
            // $table->unsignedBigInteger('city_id')->index();
            // ===================================== //
            $table->string('image')->nullable();
            $table->float('price')->nullable();
            $table->float('offer')->default(0);
            $table->boolean('disabilities')->default(false);
            // ===================================== //
            $table->bigInteger('customer_count')->default(0);
            $table->timestamp('start_offer')->nullable();
            $table->timestamp('end_offer')->nullable();
            // ===================================== //
            $table->longText('payment_types')->nullable();
            // ===================================== //
            $table->timestamps();
            // ===================================== //
            $table->foreign('club_id')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('club_branches')->onDelete('cascade');
            // $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
            // $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
            // ===================================== //
        });

        Schema::create('activity_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('activity_id')->index();
            $table->string('locale')->nullable();
            // ==================================== //
            $table->string('name')->nullable();
            $table->longText('description')->nullable();
            // ==================================== //
            $table->unique(['activity_id', 'locale']);
            $table->foreign('activity_id')->references('id')->on('activities')->onDelete('cascade');
        });

        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('activity_id')->index();
            // ==================================== //
            $table->string('image')->nullable();
            // ==================================== //
            $table->timestamps();
            // ==================================== //
            $table->foreign('activity_id')->references('id')->on('activities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
        Schema::dropIfExists('activity_translations');
        Schema::dropIfExists('activities');
    }
};
