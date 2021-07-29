<?php

namespace Database\Seeders;

use App\Models\{Category, SubCategory};
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $category = Category::create([
        	'name' => 'Sport'
        ]);

        $subCategories = [
        	new SubCategory(['name' => 'Basketball']),
        	new SubCategory(['name' => 'Badminton']),
        	new SubCategory(['name' => 'Marathon']),
        	new SubCategory(['name' => 'Tennis']),
        ];

        $category->subCategories()->saveMany($subCategories);

        $category = Category::create([
        	'name' => 'Technology'
        ]);

        $subCategories = [
        	new SubCategory(['name' => 'IoT']),
        	new SubCategory(['name' => 'Software']),
        	new SubCategory(['name' => 'Network']),
        	new SubCategory(['name' => 'Hardware']),
        ];

        $category->subCategories()->saveMany($subCategories);


        $category = Category::create([
        	'name' => 'Environment'
        ]);

        $subCategories = [
        	new SubCategory(['name' => 'Village']),
        	new SubCategory(['name' => 'City']),
        	new SubCategory(['name' => 'Beach']),
        	new SubCategory(['name' => 'Jungle']),
        ];

        $category->subCategories()->saveMany($subCategories);


    }
}
