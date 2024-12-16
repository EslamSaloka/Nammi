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
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->index();
            $table->unsignedBigInteger('club_id')->index();
            // =================================== //
            $table->timestamps();
            // =================================== //
            $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('club_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chat_id')->index();
            $table->unsignedBigInteger('user_id')->index();
            // =================================== //
            $table->longText('message')->nullable();
            $table->boolean("seen")->default(false);
            // =================================== //
            $table->timestamps();
            // =================================== //
            $table->foreign('chat_id')->references('id')->on('chats')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
        Schema::dropIfExists('chats');
    }
};
