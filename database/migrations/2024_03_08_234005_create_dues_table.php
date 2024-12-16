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
        Schema::create('dues', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('club_id')->index();
            $table->unsignedBigInteger('order_id')->index();
            $table->float("price")->default(0);
            $table->boolean('confirmed')->nullable()->default(false);
            // =================================== //
            $table->timestamps();
            // =================================== //
            $table->foreign('club_id')->references('id')->on('users');
            $table->foreign('order_id')->references('id')->on('orders');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dues');
    }
};
