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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->boolean("active")->default(true);
            $table->timestamps();
        });

        Schema::create('page_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('page_id')->index();
            $table->string('locale')->nullable();
            // ==================================== //
            $table->longText('content')->nullable();
            // ==================================== //
            $table->unique(['page_id', 'locale']);
            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');
        });

        Schema::create('faq', function (Blueprint $table) {
            $table->id();
            $table->boolean("active")->default(true);
            $table->timestamps();
        });

        Schema::create('faq_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('faq_id')->index();
            $table->string('locale')->nullable();
            // ==================================== //
            $table->string('question')->nullable();
            $table->longText('answer')->nullable();
            // ==================================== //
            $table->unique(['faq_id', 'locale']);
            $table->foreign('faq_id')->references('id')->on('faq')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_translations');
        Schema::dropIfExists('pages');
        Schema::dropIfExists('faq_translations');
        Schema::dropIfExists('faq');
    }
};
