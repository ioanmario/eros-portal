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
        Schema::create('affiliates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('affiliate_code')->unique();
            $table->string('status')->default('pending'); // pending, approved, rejected, suspended
            $table->decimal('commission_rate', 5, 2)->default(20.00); // 20% default
            $table->decimal('total_earnings', 10, 2)->default(0.00);
            $table->decimal('paid_earnings', 10, 2)->default(0.00);
            $table->decimal('pending_earnings', 10, 2)->default(0.00);
            $table->integer('referral_count')->default(0);
            $table->timestamp('approved_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliates');
    }
};
