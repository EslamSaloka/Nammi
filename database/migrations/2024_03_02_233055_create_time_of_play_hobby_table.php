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
        Schema::create('time_hobbies', function (Blueprint $table) {
            $table->id();
            $table->boolean("active")->default(true);
            $table->timestamps();
        });

        Schema::create('time_hobby_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('time_id')->index();
            $table->string('locale')->nullable();
            // ==================================== //
            $table->string('name')->nullable();
            // ==================================== //
            $table->unique(['time_id', 'locale']);
            $table->foreign('time_id')->references('id')->on('time_hobbies')->onDelete('cascade');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->bigInteger('time_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_hobby_translations');
        Schema::dropIfExists('time_hobbies');
    }
};
