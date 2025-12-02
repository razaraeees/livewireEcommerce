<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Brand>
 */
class BrandFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $brandNames = [
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
            'Diesel',
            'Tommy Hilfiger',
            'Calvin Klein',
            'Ralph Lauren',
            'Lacoste',
            'Hugo Boss',
            'Armani',
            'Versace',
            'Gucci',
            'Prada',
            'Burberry'
        ];

        $name = $this->faker->unique()->randomElement($brandNames);
        $slug = \Illuminate\Support\Str::slug($name);

        return [
            'name' => $name,
            'slug' => $slug,
            'image' => 'brands/' . strtolower(str_replace(' ', '-', $name)) . '.png',
            'description' => $this->faker->sentence(10),
            'status' => $this->faker->randomElement([0, 1]),
        ];
    }
}
