<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Product;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=1; $i <= 10; $i++) {
            Product::create([
                'name' => 'Laptop '.$i,
                'slug' => 'laptop-'.$i,
                'details' => '14 Inch 256GB SSD 8GB',
                'price' => '10000'.$i,
                'description' => 'Lorem '.$i.' ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.'
            ]);
        }
    }
}
