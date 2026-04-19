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
        Schema::create('security_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('first_question')->default('What is your favorite color?');
            $table->string('first_answer')->default(bin2hex(random_bytes(5)));
            $table->string('second_question')->default('What is your pet\'s name?');
            $table->string('second_answer')->default(bin2hex(random_bytes(5)));
            $table->string('third_question')->default('Who is your favorite actor?');
            $table->string('third_answer')->default(bin2hex(random_bytes(5)));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('security_answers');
    }
};
