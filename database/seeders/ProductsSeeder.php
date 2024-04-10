<?php

namespace Database\Seeders;

use App\Models\Products;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as FakerFactory;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $faker = FakerFactory::create();

        for ($i = 0; $i < 10; $i++) {
            Products::create([
                'name' => $faker->name,
                'price' => $faker->numberBetween(100, 1000),
            ]);
        }
    }
}
