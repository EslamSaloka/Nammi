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
        Schema::create('club_branches', function (Blueprint $table) {
            $table->id();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('what_app')->nullable();
            $table->double('lat')->nullable();
            $table->double('lng')->nullable();
            $table->timestamps();
            // ================================ //
            $table->unsignedBigInteger('club_id')->index();
            $table->foreign('club_id')->references('id')->on('users')->onDelete('cascade');
            // ================================ //
            $table->unsignedBigInteger('country_id')->index();
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
            // ================================ //
            $table->unsignedBigInteger('city_id')->index();
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
            // ================================ //
        });

        Schema::create('branch_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->index();
            $table->string('locale')->nullable();
            // ==================================== //
            $table->string('name')->nullable();
            $table->longText('address')->nullable();
            // ==================================== //
            $table->unique(['branch_id', 'locale']);
            $table->foreign('branch_id')->references('id')->on('club_branches')->onDelete('cascade');
        });

        Schema::create('user_club_fav', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('club_id')->index();
            $table->foreign('club_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('club_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('club_id')->index();
            $table->string('image')->nullable();
            $table->timestamps();
            $table->foreign('club_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('club_branches');
        Schema::dropIfExists('user_club_fav');
        Schema::dropIfExists('club_images');
    }
};
