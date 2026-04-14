<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained()->cascadeOnDelete();
            $table->string('session_token')->unique()->comment('Anonymous session identifier');
            $table->string('email')->nullable()->index();
            $table->string('name')->nullable();
            $table->string('status')->default('in_progress')->comment('in_progress | completed | abandoned');
            $table->foreignId('result_type_id')->nullable()->constrained()->nullOnDelete();
            $table->json('score_breakdown')->nullable()->comment('Map of result_type_code => total_score');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_attempts');
    }
};
