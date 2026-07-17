<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Per-product Low Stock Alert threshold — replaces the old
            // hardcoded "3 units" constant so each product can have its
            // own sensible reorder point (a shirt might reorder at 10,
            // an accessory at 3).
            $table->unsignedInteger('reorder_level')->default(5)->after('quantity');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('reorder_level');
        });
    }
};
