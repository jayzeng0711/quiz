<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->json('selected_question_ids')
                ->nullable()
                ->after('completed_at')
                ->comment('Randomly selected question IDs for this attempt (10 from pool of 100)');

            $table->json('dimension_scores')
                ->nullable()
                ->after('selected_question_ids')
                ->comment('Normalised 0-100 score per personality dimension, e.g. {ASSERTIVENESS: 72}');
        });
    }

    public function down(): void
    {
        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->dropColumn(['selected_question_ids', 'dimension_scores']);
        });
    }
};
