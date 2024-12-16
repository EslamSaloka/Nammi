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
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->boolean("active")->default(1);
            $table->timestamps();
        });

        Schema::create('country_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('country_id')->index();
            $table->string('locale')->nullable();
            // ==================================== //
            $table->longText('name')->nullable();
            // ==================================== //
            $table->unique(['country_id', 'locale']);
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('country_translations');
        Schema::dropIfExists('countries');
    }
};
