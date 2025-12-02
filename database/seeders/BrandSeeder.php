<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        Brand::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        $brands = [
            'Nike',
            'Adidas',
            'Puma',
            'Reebok',
            'Under Armour',
            'New Balance',
            'Asics',
            'Converse',
            'Vans',
            'Fila',
            'Skechers',
            'Jordan',
            'Timberland',
            'Columbia',
            'The North Face',
            'Patagonia',
            'Levi\'s',
            'Wrangler',
            'Lee',
            'Diesel'
        ];

        // Ensure directory exists
        $path = storage_path('app/public/brands');
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        foreach ($brands as $name) {
            $slug = \Illuminate\Support\Str::slug($name);
            $imageName = $slug . '.png';

            // Generate unique color for each brand image
            $colors = ['1e293b', '334155', '475569', '0f172a', '1e1b4b', '312e81', '3730a3', '4338ca', '064e3b', '065f46', '047857', '14532d', '164e63', '155e75', '0e7490', '7f1d1d', '991b1b', 'b91c1c', 'c2410c', '9a3412'];
            $bg = $colors[array_rand($colors)];

            // URL for placeholder image with brand name
            $imageUrl = "https://placehold.co/400x300/{$bg}/ffffff.png?text=" . urlencode($name);

            try {
                // Download and save image
                $imageContent = file_get_contents($imageUrl);
                if ($imageContent !== false) {
                    file_put_contents($path . '/' . $imageName, $imageContent);
                }
            } catch (\Exception $e) {
                $this->command->warn("Could not download image for $name");
            }

            Brand::create([
                'name' => $name,
                'slug' => $slug,
                'image' => 'brands/' . $imageName,
                'description' => "Official authentic products from {$name}. High quality and durable.",
                'status' => 1,
            ]);

            $this->command->info("Created brand: $name");
        }

        $this->command->info('✅ All brands created with unique images!');
    }
}
