<?php

use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $faker = Faker\Factory::create();
    	foreach (range(1,50) as $index) {
	        DB::table('products')->insert([
	            'title' => $faker->words(3, true),
	            'slug' => $faker->slug,
	            'status' => $faker->biasedNumberBetween(0,1),
	            'author' => $faker->biasedNumberBetween(1,3),
                'created_at' => $faker->dateTimeBetween('-2 years', 'now')
	        ]);
	    }
    }
}
