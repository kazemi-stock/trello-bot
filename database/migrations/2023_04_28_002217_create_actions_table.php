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
        Schema::create('actions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('card_id')->nullable();
            $table->string('t_action_id', 155)->comment('Trello Action ID');
            $table->string('type', 155)->charset('utf8mb4_general_ci');
            $table->longText('data')->nullable()->charset('utf8mb4_general_ci');
            $table->dateTime('date')->default('CURRENT_TIMESTAMP');
            $table->foreign('card_id')->references('id')->on('cards');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actions');
    }
};
