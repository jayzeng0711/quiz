<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('quiz_attempt_id')->constrained()->cascadeOnDelete();
            $table->string('email');
            $table->string('name')->nullable();
            $table->integer('amount')->comment('Amount in cents');
            $table->string('currency', 3)->default('TWD');
            $table->string('status')->default('pending')->comment('pending | paid | failed | refunded');
            $table->string('payment_provider')->nullable()->comment('e.g. stripe, ecpay, newebpay');
            $table->string('payment_reference')->nullable()->comment('Provider transaction ID');
            $table->json('payment_meta')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
