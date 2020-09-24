<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new \App\User([
            'name'          => 'Romel Indemne',
            'email'         => 'admin@admin.com',
            'password'      => Hash::make('123456789'),
            'phone'         => '05012345678',
            'role'          => 1,
            'status'        => true,
            'company_id'    => 1,
        ]);
        $user->save();
        $user = new \App\User([
            'name'          => 'Team Editor',
            'email'         => 'teameditor@test.com',
            'password'      => Hash::make('123456789'),
            'phone'         => '05012345678',
            'role'          => 5,
            'status'        => true,
            'company_id'    => 1,
        ]);
        $user->save();
        $user = new \App\User([
            'name'          => 'Team Admin',
            'email'         => 'teamadmin@test.com',
            'password'      => Hash::make('123456789'),
            'phone'         => '05012345678',
            'role'          => 4,
            'status'        => true,
            'company_id'    => 1,
        ]);
        $user->save();

        $faker = Faker\Factory::create();
    	foreach (range(1,50) as $index) {
	        DB::table('users')->insert([
                'name'          => $faker->name,
                'email'         => $faker->email,
                'password'      => Hash::make('123456789'),
                'phone'         => '05012345678',
                'role'          => 4,
                'status'        => true,
                'company_id'    => 1,
	        ]);
	    }
    }
}
