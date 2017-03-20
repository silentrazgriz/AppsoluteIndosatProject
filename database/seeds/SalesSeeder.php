<?php

use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;

class SalesSeeder extends Seeder
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

	    DB::table('sales')->delete();
	    DB::table('sales')->insert([
		    [
			    "id" => "7464c2cf-9a6a-44d1-8197-26be65df42ba",
			    "email" => $faker->freeEmail,
			    "password" => bcrypt("sales"),
			    "name" => $faker->name,
			    "gender" => "Laki-laki",
			    "phone" => "081211223344",
			    "balance" => "1000000",
			    "last_location" => '{"data":{"formatted_address":"Stasiun Gambir, Jl. Medan Merdeka Tim. No.1, Gambir, Kota Jakarta Pusat, DKI Jakarta 10110, Indonesia","geometry":{"lat":-6.1765322,"long":106.8306315},"establishment":{"long_name":"Stasiun Gambir","short_name":"Stasiun Gambar"},"placeID":"ChIJ4YTZsDL0aS4RivOJNmIVHDQ"}}',
			    "created_at" => $date,
			    "updated_at" => $date
		    ],
		    [
			    "id" => "efaf6f73-be88-4e42-a328-35173876c2c1",
			    "email" => $faker->freeEmail,
			    "password" => bcrypt("sales"),
			    "name" => $faker->name,
			    "gender" => "Laki-laki",
			    "phone" => "081211223355",
			    "balance" => "1000000",
			    "last_location" => '{"data":{"formatted_address":"Stasiun Gambir, Jl. Medan Merdeka Tim. No.1, Gambir, Kota Jakarta Pusat, DKI Jakarta 10110, Indonesia","geometry":{"lat":-6.1765322,"long":106.8306315},"establishment":{"long_name":"Stasiun Gambir","short_name":"Stasiun Gambar"},"placeID":"ChIJ4YTZsDL0aS4RivOJNmIVHDQ"}}',
			    "created_at" => $date,
			    "updated_at" => $date
		    ],
		    [
			    "id" => "1c7c4587-91ee-459f-ae3e-57144958a24d",
			    "email" => $faker->freeEmail,
			    "password" => bcrypt("sales"),
			    "name" => $faker->name,
			    "gender" => "Perempuan",
			    "phone" => "081244223344",
			    "balance" => "1000000",
			    "last_location" => '{"data":{"formatted_address":"Stasiun Gambir, Jl. Medan Merdeka Tim. No.1, Gambir, Kota Jakarta Pusat, DKI Jakarta 10110, Indonesia","geometry":{"lat":-6.1765322,"long":106.8306315},"establishment":{"long_name":"Stasiun Gambir","short_name":"Stasiun Gambar"},"placeID":"ChIJ4YTZsDL0aS4RivOJNmIVHDQ"}}',
			    "created_at" => $date,
			    "updated_at" => $date
		    ],
	    ]);
    }
}
