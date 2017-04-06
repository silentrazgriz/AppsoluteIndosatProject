<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    $this->call(SalesAreaSeeder::class);
	    $this->call(UserSeeder::class);
	    $this->call(EventSeeder::class);
	    $this->call(NumberListSeeder::class);
    }
}
