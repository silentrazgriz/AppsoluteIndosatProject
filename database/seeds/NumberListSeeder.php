<?php

use Illuminate\Database\Seeder;
use Faker\Factory;

class NumberListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    $faker = Factory::create();

	    DB::table('number_lists')->delete();
	    DB::table('number_lists')->insert([
	    	['number' => '085656804564'],
		    ['number' => '085648375985'],
		    ['number' => '085641219715'],
		    ['number' => '085611384590'],
		    ['number' => '085663482611'],
		    ['number' => '085619850461'],
		    ['number' => '085611379372'],
		    ['number' => '085692581026'],
		    ['number' => '085690779872'],
		    ['number' => '085673448806'],
		    ['number' => '085655571638'],
		    ['number' => '085624731064'],
		    ['number' => '085679287346'],
		    ['number' => '085699305375'],
		    ['number' => '085623811761'],
		    ['number' => '085649787907'],
		    ['number' => '085698717207'],
		    ['number' => '085694859969'],
		    ['number' => '085623138874'],
		    ['number' => '085643145896']
	    ]);
    }
}