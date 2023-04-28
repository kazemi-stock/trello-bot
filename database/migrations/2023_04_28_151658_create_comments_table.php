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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('card_id');
            $table->bigInteger('contact_id')->nullable();
            $table->string('name', 155)->nullable()->charset('utf8mb4_general_ci');
            $table->longText('body')->nullable()->charset('utf8mb4_general_ci');
            $table->date('sent_at')->nullable();
            $table->foreign('card_id')->references('id')->on('cards');
            $table->foreign('contact_id')->references('id')->on('contacts');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
