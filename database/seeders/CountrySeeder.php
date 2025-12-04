<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [
            ['name' => 'Afghanistan', 'code' => '+93', 'status' => true],
            ['name' => 'Albania', 'code' => '+355', 'status' => true],
            ['name' => 'Algeria', 'code' => '+213', 'status' => true],
            ['name' => 'Argentina', 'code' => '+54', 'status' => true],
            ['name' => 'Australia', 'code' => '+61', 'status' => true],
            ['name' => 'Austria', 'code' => '+43', 'status' => true],
            ['name' => 'Bangladesh', 'code' => '+880', 'status' => true],
            ['name' => 'Belgium', 'code' => '+32', 'status' => true],
            ['name' => 'Brazil', 'code' => '+55', 'status' => true],
            ['name' => 'Canada', 'code' => '+1', 'status' => true],
            ['name' => 'China', 'code' => '+86', 'status' => true],
            ['name' => 'Denmark', 'code' => '+45', 'status' => true],
            ['name' => 'Egypt', 'code' => '+20', 'status' => true],
            ['name' => 'France', 'code' => '+33', 'status' => true],
            ['name' => 'Germany', 'code' => '+49', 'status' => true],
            ['name' => 'India', 'code' => '+91', 'status' => true],
            ['name' => 'Indonesia', 'code' => '+62', 'status' => true],
            ['name' => 'Iran', 'code' => '+98', 'status' => true],
            ['name' => 'Iraq', 'code' => '+964', 'status' => true],
            ['name' => 'Italy', 'code' => '+39', 'status' => true],
            ['name' => 'Japan', 'code' => '+81', 'status' => true],
            ['name' => 'Malaysia', 'code' => '+60', 'status' => true],
            ['name' => 'Mexico', 'code' => '+52', 'status' => true],
            ['name' => 'Netherlands', 'code' => '+31', 'status' => true],
            ['name' => 'New Zealand', 'code' => '+64', 'status' => true],
            ['name' => 'Nigeria', 'code' => '+234', 'status' => true],
            ['name' => 'Pakistan', 'code' => '+92', 'status' => true],
            ['name' => 'Philippines', 'code' => '+63', 'status' => true],
            ['name' => 'Poland', 'code' => '+48', 'status' => true],
            ['name' => 'Russia', 'code' => '+7', 'status' => true],
            ['name' => 'Saudi Arabia', 'code' => '+966', 'status' => true],
            ['name' => 'Singapore', 'code' => '+65', 'status' => true],
            ['name' => 'South Africa', 'code' => '+27', 'status' => true],
            ['name' => 'South Korea', 'code' => '+82', 'status' => true],
            ['name' => 'Spain', 'code' => '+34', 'status' => true],
            ['name' => 'Sweden', 'code' => '+46', 'status' => true],
            ['name' => 'Switzerland', 'code' => '+41', 'status' => true],
            ['name' => 'Thailand', 'code' => '+66', 'status' => true],
            ['name' => 'Turkey', 'code' => '+90', 'status' => true],
            ['name' => 'United Arab Emirates', 'code' => '+971', 'status' => true],
            ['name' => 'United Kingdom', 'code' => '+44', 'status' => true],
            ['name' => 'United States', 'code' => '+1', 'status' => true],
            ['name' => 'Vietnam', 'code' => '+84', 'status' => true],
        ];

        foreach ($countries as $country) {
            Country::create($country);
        }
    }
}
