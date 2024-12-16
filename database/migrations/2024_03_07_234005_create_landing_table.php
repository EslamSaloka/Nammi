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
        Schema::create('landing_counts', function (Blueprint $table) {
            $table->id();
            $table->string("image")->nullable();
            $table->integer("count")->default(0);
            $table->timestamps();
        });

        Schema::create('landing_count_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('count_id')->index();
            $table->string('locale')->nullable();
            // ==================================== //
            $table->string('name')->nullable();
            // ==================================== //
            $table->unique(['count_id', 'locale']);
            $table->foreign('count_id')->references('id')->on('landing_counts')->onDelete('cascade');
        });
        // ====================================== //

        Schema::create('landing_features', function (Blueprint $table) {
            $table->id();
            $table->string("image")->nullable();
            $table->timestamps();
        });

        Schema::create('landing_features_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('features_id')->index();
            $table->string('locale')->nullable();
            // ==================================== //
            $table->string('name')->nullable();
            $table->longText('content')->nullable();
            // ==================================== //
            $table->unique(['features_id', 'locale']);
            $table->foreign('features_id')->references('id')->on('landing_features')->onDelete('cascade');
        });
        // ====================================== //

        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->integer("star")->default(0);
            $table->timestamps();
        });

        Schema::create('testimonial_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('testimonial_id')->index();
            $table->string('locale')->nullable();
            // ==================================== //
            $table->string('name')->nullable();
            $table->longText('content')->nullable();
            // ==================================== //
            $table->unique(['testimonial_id', 'locale']);
            $table->foreign('testimonial_id')->references('id')->on('testimonials')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('landing_count_translations');
        Schema::dropIfExists('landing_counts');

        Schema::dropIfExists('landing_features_translations');
        Schema::dropIfExists('landing_features');

        Schema::dropIfExists('testimonial_translations');
        Schema::dropIfExists('testimonials');
    }
};
