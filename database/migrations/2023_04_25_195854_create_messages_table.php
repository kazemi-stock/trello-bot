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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('contact_id')->nullable();
            $table->string('from', 191)->nullable()->charset('utf8mb4_general_ci');
            $table->string('to', 191)->nullable()->charset('utf8mb4_general_ci');
            $table->longText('body')->nullable()->charset('utf8mb4_general_ci');
            $table->enum('type', ['card', 'comment'])->default('card');
            $table->foreign('contact_id')->references('id')->on('contacts');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
