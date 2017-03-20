<?php

use Carbon\Carbon;
use Faker\Provider\Uuid;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$date = Carbon::now();
    	DB::table('admins')->delete();
    	DB::table('admins')->insert([
    		[
    			"id" => Uuid::uuid(),
			    "email" => "admin@indosatsurvey.com",
			    "password" => bcrypt("admin"),
			    "name" => "Admin",
			    "created_at" => $date,
			    "updated_at" => $date
		    ]
	    ]);
    }
}
