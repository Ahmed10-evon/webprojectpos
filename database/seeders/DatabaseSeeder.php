<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\BusinessSetting;
use App\Models\Category;
use App\Models\MembershipSetting;
use App\Models\TaxRate;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Default logins — change these passwords immediately after first login.
        User::updateOrCreate(
            ['email' => 'admin@craveabs.test'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'salesman@craveabs.test'],
            [
                'name' => 'Salesman',
                'password' => Hash::make('password'),
                'role' => 'salesman',
                'is_active' => true,
            ]
        );

        foreach (['Dresses', 'Tops', 'Bottoms', 'Outerwear', 'Accessories'] as $name) {
            Category::firstOrCreate(['name' => $name]);
        }

        foreach (['CRAVE ABS', 'Generic'] as $name) {
            Brand::firstOrCreate(['name' => $name]);
        }

        Unit::firstOrCreate(['name' => 'Piece'], ['short_code' => 'pc']);
        Unit::firstOrCreate(['name' => 'Set'], ['short_code' => 'set']);

        BusinessSetting::firstOrCreate([], [
            'business_name' => 'CRAVE ABS',
            'receipt_footer_line1' => 'THANK YOU FOR SHOPPING!',
            'barcode_prefix' => 'CRV',
        ]);

        MembershipSetting::firstOrCreate([], ['discount_percent' => 10]);

        TaxRate::firstOrCreate(['name' => 'VAT'], ['rate_percent' => 5]);
    }
}
