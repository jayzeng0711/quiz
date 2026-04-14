<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_attempt_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('result_type_id')->constrained()->cascadeOnDelete();
            $table->string('access_token')->unique()->comment('Public token for shareable/download link');
            $table->string('status')->default('draft')->comment('draft | generated | delivered');
            $table->string('pdf_path')->nullable()->comment('Storage path to generated PDF');
            $table->json('rendered_content')->nullable()->comment('Snapshot of rendered report data');
            $table->timestamp('generated_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
