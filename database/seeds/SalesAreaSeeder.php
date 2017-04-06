<?php

use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;

class SalesAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    $date = Carbon::now();
	    $faker = Factory::create();

	    DB::table('sales_areas')->delete();
	    DB::table('sales_areas')->insert([
			['description' => 'Jakarta Pusat Utara'],
		    ['description' => 'Jakarta Timur'],
		    ['description' => 'Jakarta Barat'],
		    ['description' => 'Jakarta Selatan'],
		    ['description' => 'Karawang'],
		    ['description' => 'Bogor'],
		    ['description' => 'Depok'],
		    ['description' => 'Bekasi'],
		    ['description' => 'Tangerang']
	    ]);
    }
}
