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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->timestamp('phone_verified_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamp('last_action_at')->nullable();
            $table->string('password');
            $table->date('birthday')->nullable();
            $table->float('otp')->default(rand(1000,9999));
            $table->enum('gender',["male","female"])->default("male");
            $table->boolean('disabilities')->default(false);
            $table->string('avatar')->default("icon.png");
            $table->boolean('suspend')->default(false);
            // ================================================ //
            $table->string('points')->default(0);
            $table->string('account_by')->default("normal");
            $table->bigInteger('social_id')->nullable();
            $table->string('fire_base_token')->nullable();
            // ================================================ //
            // ================================================ //
            // Club Information
            $table->string('name_en')->nullable();
            $table->longText('about')->nullable();
            $table->longText('about_en')->nullable();
            $table->float('rates')->default(0);
            $table->integer('vat')->default(0);
            $table->longText('rejected_message')->nullable();
            // ================================================ //
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
