<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('memberships', function (Blueprint $table) {
            $table->id();
            $table->string('phone')->unique();
            $table->string('note')->nullable();
            $table->date('start_date');
            $table->date('expiry_date');
            $table->enum('status', ['active', 'revoked'])->default('active');
            $table->timestamps();
        });

        Schema::create('membership_settings', function (Blueprint $table) {
            $table->id();
            $table->decimal('discount_percent', 5, 2)->default(10);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('memberships');
        Schema::dropIfExists('membership_settings');
    }
};
