<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        Category::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        // Ensure directory exists
        $path = storage_path('app/public/categories');
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        $categories = [
            [
                'name' => 'Electronics',
                'children' => [
                    [
                        'name' => 'Computers & Laptops',
                        'children' => [
                            ['name' => 'Gaming Laptops'],
                            ['name' => 'Ultrabooks'],
                            ['name' => 'MacBooks'],
                            ['name' => 'Accessories'],
                        ]
                    ],
                    [
                        'name' => 'Smartphones & Tablets',
                        'children' => [
                            ['name' => 'Apple iPhone'],
                            ['name' => 'Samsung Galaxy'],
                            ['name' => 'Tablets'],
                            ['name' => 'Phone Accessories'],
                        ]
                    ],
                    [
                        'name' => 'Cameras & Audio',
                        'children' => [
                            ['name' => 'DSLR Cameras'],
                            ['name' => 'Headphones'],
                            ['name' => 'Bluetooth Speakers'],
                            ['name' => 'Home Audio'],
                        ]
                    ]
                ]
            ],
            [
                'name' => 'Fashion',
                'children' => [
                    [
                        'name' => 'Men Fashion',
                        'children' => [
                            ['name' => 'T-Shirts & Polos'],
                            ['name' => 'Jeans & Trousers'],
                            ['name' => 'Formal Shirts'],
                            ['name' => 'Shoes & Sneakers'],
                        ]
                    ],
                    [
                        'name' => 'Women Fashion',
                        'children' => [
                            ['name' => 'Dresses & Skirts'],
                            ['name' => 'Tops & Tees'],
                            ['name' => 'Handbags & Wallets'],
                            ['name' => 'Heels & Sandals'],
                        ]
                    ],
                    [
                        'name' => 'Watches & Jewelry',
                        'children' => [
                            ['name' => 'Men Watches'],
                            ['name' => 'Women Watches'],
                            ['name' => 'Necklaces'],
                            ['name' => 'Rings & Earrings'],
                        ]
                    ]
                ]
            ],
            [
                'name' => 'Home & Living',
                'children' => [
                    [
                        'name' => 'Furniture',
                        'children' => [
                            ['name' => 'Living Room'],
                            ['name' => 'Bedroom'],
                            ['name' => 'Office Furniture'],
                            ['name' => 'Outdoor'],
                        ]
                    ],
                    [
                        'name' => 'Kitchen & Dining',
                        'children' => [
                            ['name' => 'Cookware'],
                            ['name' => 'Tableware'],
                            ['name' => 'Kitchen Appliances'],
                            ['name' => 'Coffee & Tea'],
                        ]
                    ]
                ]
            ]
        ];

        foreach ($categories as $rootCat) {
            // Root category: Level 0, Parent ID null
            $this->createCategory($rootCat, null, 0);
        }

        $this->command->info('✅ All categories created with 3-level hierarchy (0-1-2) and images!');
    }

    private function createCategory($data, $parentId, $level)
    {
        $name = $data['name'];
        $slug = Str::slug($name);
        $imageName = $slug . '.png';
        $path = storage_path('app/public/categories');

        // Generate unique color
        $colors = ['1e293b', '334155', '475569', '0f172a', '1e1b4b', '312e81', '3730a3', '4338ca', '064e3b', '065f46', '047857', '14532d', '164e63', '155e75', '0e7490', '7f1d1d', '991b1b', 'b91c1c', 'c2410c', '9a3412'];
        $bg = $colors[array_rand($colors)];

        // Download image
        $imageUrl = "https://placehold.co/400x300/{$bg}/ffffff.png?text=" . urlencode($name);
        try {
            $imageContent = file_get_contents($imageUrl);
            if ($imageContent !== false) {
                file_put_contents($path . '/' . $imageName, $imageContent);
            }
        } catch (\Exception $e) {
            // Ignore download errors
        }

        $category = Category::create([
            'parent_id' => $parentId,
            'level' => $level,
            'category_name' => $name,
            'category_image' => 'categories/' . $imageName,
            'url' => $slug,
            'description' => "Best collection of {$name}",
            'status' => 1,
            'category_discount' => 0,
        ]);

        $this->command->info("Created Level {$level}: {$name}");

        if (isset($data['children'])) {
            foreach ($data['children'] as $child) {
                $this->createCategory($child, $category->id, $level + 1);
            }
        }
    }
}
