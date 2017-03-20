<?php

use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
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

	    DB::table('events')->delete();
	    DB::table('events')->insert([
		    [
			    "id" => "daa7c207-1627-4954-9d32-b224a51b468d",
			    "name" => "Promo Gambir",
			    "date" => $faker->date(),
			    "location" => '{"data":{"formatted_address":"Stasiun Gambir, Jl. Medan Merdeka Tim. No.1, Gambir, Kota Jakarta Pusat, DKI Jakarta 10110, Indonesia","geometry":{"lat":-6.1765322,"long":106.8306315},"establishment":{"long_name":"Stasiun Gambir","short_name":"Stasiun Gambar"},"placeID":"ChIJ4YTZsDL0aS4RivOJNmIVHDQ"}}',
			    "survey" => '{"data":{"items":[{"key":"dpp","description":"Data Pribadi Pelanggan","questions":[{"key":"name","text":"Nama","type":"text"},{"key":"gender","text":"Gender","type":"gender"},{"key":"job","text":"Pekerjaan","type":"text"},{"key":"age","text":"Range usia","type":"dropdown","values":[{"key":"15-20","text":"15-20 tahun"},{"key":"21-25","text":"21-25 tahun"},{"key":"26-30","text":"26-30 tahun"},{"key":"30+","text":"Diatas 30 tahun"}]}]},{"key":"dep","description":"Data Edukasi Pelanggan","questions":[{"key":"data_rollover","text":"Data Rollover","type":"checkbox","detail":{"image":"http://placekitten.com/600/300","description":"Lorem ipsum dolor sit amet"}},{"key":"freedom_combo","text":"Freedom Combo","type":"checkbox","detail":{"image":"http://placekitten.com/600/300","description":"Lorem ipsum dolor sit amet"}}]}]}}',
			    "created_at" => $date,
			    "updated_at" => $date
		    ]
	    ]);
    }
}
