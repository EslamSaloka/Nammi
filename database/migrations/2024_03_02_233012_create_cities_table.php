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
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->boolean("active")->default(1);
            $table->timestamps();
            // ================================ //
            $table->unsignedBigInteger('country_id')->index();
            $table->foreign('country_id')->references('id')->on('cities')->onDelete('cascade');
        });

        Schema::create('city_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('city_id')->index();
            $table->string('locale')->nullable();
            // ==================================== //
            $table->longText('name')->nullable();
            // ==================================== //
            $table->unique(['city_id', 'locale']);
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('city_translations');
        Schema::dropIfExists('cities');
    }
};
