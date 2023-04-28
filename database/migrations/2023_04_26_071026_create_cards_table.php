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
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->string('t_board_id', 155)->nullable()->comment('Trello Board ID');
            $table->string('t_card_id', 155)->nullable()->comment('Trello Card ID');
            $table->string('name', 155)->nullable()->charset('utf8mb4_general_ci');
            $table->longText('desc')->nullable()->charset('utf8mb4_general_ci');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};
