<?php

use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $comapny = new \App\Company([
            'title'         => 'MEL',
            'description'   => 'This is MEL Company or Organization.',
        ]);
        $comapny->save();
        $comapny = new \App\Company([
            'title'         => 'Ghassan Aboud Group',
            'description'   => 'This is Ghassan Aboud Group Company or Organization.',
        ]);
        $comapny->save();
        $comapny = new \App\Company([
            'title'         => 'Other Company',
            'description'   => 'This is Other Company or Organization.',
        ]);
        $comapny->save();

        $faker = Faker\Factory::create();
    	foreach (range(1,50) as $index) {
	        DB::table('companies')->insert([
	            'title' => $faker->words(3, true),
	        ]);
	    }
    }
}
