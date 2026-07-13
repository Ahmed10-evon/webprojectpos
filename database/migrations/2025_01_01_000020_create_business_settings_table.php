<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('business_settings', function (Blueprint $table) {
            $table->id();
            $table->string('business_name')->default('Space Topup');
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('receipt_footer_line1')->default('THANK YOU FOR SHOPPING!');
            $table->string('receipt_footer_line2')->nullable();
            $table->string('barcode_prefix')->default('SPT');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('business_settings');
    }
};
