<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('result_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained()->cascadeOnDelete();
            $table->string('code')->comment('Internal identifier e.g. ENFJ, type_a');
            $table->string('title');
            $table->text('description');
            $table->text('report_content')->nullable()->comment('Full HTML/Markdown content for the report');
            $table->json('meta')->nullable()->comment('Extra fields e.g. image, color, tags');
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['quiz_id', 'code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('result_types');
    }
};
