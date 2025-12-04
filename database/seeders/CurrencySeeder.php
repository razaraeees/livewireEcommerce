<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currencies = [
            ['name' => 'US Dollar', 'code' => 'USD', 'symbol' => '$', 'status' => true],
            ['name' => 'Euro', 'code' => 'EUR', 'symbol' => '€', 'status' => true],
            ['name' => 'British Pound', 'code' => 'GBP', 'symbol' => '£', 'status' => true],
            ['name' => 'Pakistani Rupee', 'code' => 'PKR', 'symbol' => '₨', 'status' => true],
            ['name' => 'Indian Rupee', 'code' => 'INR', 'symbol' => '₹', 'status' => true],
            ['name' => 'Japanese Yen', 'code' => 'JPY', 'symbol' => '¥', 'status' => true],
            ['name' => 'Chinese Yuan', 'code' => 'CNY', 'symbol' => '¥', 'status' => true],
            ['name' => 'Australian Dollar', 'code' => 'AUD', 'symbol' => 'A$', 'status' => true],
            ['name' => 'Canadian Dollar', 'code' => 'CAD', 'symbol' => 'C$', 'status' => true],
            ['name' => 'Swiss Franc', 'code' => 'CHF', 'symbol' => 'CHF', 'status' => true],
            ['name' => 'Saudi Riyal', 'code' => 'SAR', 'symbol' => '﷼', 'status' => true],
            ['name' => 'UAE Dirham', 'code' => 'AED', 'symbol' => 'د.إ', 'status' => true],
            ['name' => 'Singapore Dollar', 'code' => 'SGD', 'symbol' => 'S$', 'status' => true],
            ['name' => 'Malaysian Ringgit', 'code' => 'MYR', 'symbol' => 'RM', 'status' => true],
            ['name' => 'Thai Baht', 'code' => 'THB', 'symbol' => '฿', 'status' => true],
            ['name' => 'South Korean Won', 'code' => 'KRW', 'symbol' => '₩', 'status' => true],
            ['name' => 'Turkish Lira', 'code' => 'TRY', 'symbol' => '₺', 'status' => true],
            ['name' => 'Russian Ruble', 'code' => 'RUB', 'symbol' => '₽', 'status' => true],
            ['name' => 'Brazilian Real', 'code' => 'BRL', 'symbol' => 'R$', 'status' => true],
            ['name' => 'Mexican Peso', 'code' => 'MXN', 'symbol' => '$', 'status' => true],
            ['name' => 'South African Rand', 'code' => 'ZAR', 'symbol' => 'R', 'status' => true],
            ['name' => 'Norwegian Krone', 'code' => 'NOK', 'symbol' => 'kr', 'status' => true],
            ['name' => 'Swedish Krona', 'code' => 'SEK', 'symbol' => 'kr', 'status' => true],
            ['name' => 'Danish Krone', 'code' => 'DKK', 'symbol' => 'kr', 'status' => true],
            ['name' => 'Polish Zloty', 'code' => 'PLN', 'symbol' => 'zł', 'status' => true],
        ];

        foreach ($currencies as $currency) {
            Currency::create($currency);
        }
    }
}
