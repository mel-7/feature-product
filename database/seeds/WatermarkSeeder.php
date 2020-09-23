<?php

use Illuminate\Database\Seeder;

class WatermarkSeeder extends Seeder
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
	        DB::table('watermarks')->insert([
	            'title' => $faker->words(3, true),
	            'company_id' => 1
	        ]);
	    }
    }

}
