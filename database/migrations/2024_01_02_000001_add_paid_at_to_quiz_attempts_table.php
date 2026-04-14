<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->timestamp('paid_at')->nullable()->after('completed_at');
        });

        // Update quiz price to 4900 (= TWD 49)
        DB::table('quizzes')->update(['price' => 4900]);
    }

    public function down(): void
    {
        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->dropColumn('paid_at');
        });
    }
};
