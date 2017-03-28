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
			    "name" => "Dummy Event",
			    "date" => $date->toDateString(),
			    "survey" => '[{"key":"profile","description":"Profil Customer","questions":[{"key":"provider","text":"Provider yang digunakan","type":"dropdown","values":[{"key":"telkomsel+halo","text":"Telkomsel Halo"},{"key":"telkomsel+simpati","text":"Telkomsel Simpati"},{"key":"telkomsel+loop","text":"Telkomsel Loop"},{"key":"telkomsel+kartu+as","text":"Telkomsel Kartu As"},{"key":"im3+ooredoo+pascabayar","text":"IM3 Ooredoo Pascabayar"},{"key":"im3+ooredoo+prabayar","text":"IM3 Ooredoo Prabayar"},{"key":"xl+axiata+pascabayar","text":"XL Axiata Pascabayar"},{"key":"xl+axiata+prabayar","text":"XL Axiata Prabayar"},{"key":"3","text":"3"},{"key":"smartfren","text":"SmartFren"},{"key":"bolt","text":"Bolt"}]},{"key":"data+usage","text":"Seberapa banyak data yang digunakan dalam sebulan?","type":"dropdown","values":[{"key":"0-3+gb","text":"0-3 GB"},{"key":"3-6+gb","text":"3-6 GB"},{"key":"6-10+gb","text":"6-10 GB"},{"key":"10-20+gb","text":"10-20 GB"},{"key":"20-30+gb","text":"20-30 GB"},{"key":">30+gb","text":">30 GB"}]},{"key":"phone+usage","text":"Seberapa banyak penggunaan telepon per bulannya?","type":"dropdown","values":[{"key":"0-5+menit","text":"0-5 menit"},{"key":"6-10+menit","text":"6-10 menit"},{"key":"11-20+menit","text":"11-20 menit"},{"key":"21-50+menit","text":"21-50 menit"},{"key":"51-90+menit","text":"51-90 menit"},{"key":">90+menit","text":">90 menit"}]},{"key":"sms+usage","text":"Seberapa banyak penggunaan SMS per bulannya?","type":"dropdown","values":[{"key":"0-10+sms","text":"0-10 SMS"},{"key":"11-25+sms","text":"11-25 SMS"},{"key":"25-50+sms","text":"25-50 SMS"},{"key":">50+sms","text":">50 SMS"}]},{"key":"name","text":"Nama","type":"text","class":"border-round required","required":true},{"key":"phone","text":"No. Handphone","type":"phone","class":"border-round required","required":true},{"key":"gender","text":"Gender","type":"radio","values":[{"key":"laki-laki","text":"Laki-laki","checked":true},{"key":"perempuan","text":"Perempuan"}]},{"key":"job","text":"Pekerjaan","type":"dropdown","values":[{"key":"pelajar","text":"Pelajar"},{"key":"mahasiswa","text":"Mahasiswa"},{"key":"karyawan+swasta","text":"Karyawan Swasta"},{"key":"pns","text":"PNS"},{"key":"ibu+rumah+tangga","text":"Ibu Rumah Tangga"},{"key":"lainnya","text":"Lainnya"}]},{"key":"age","text":"Range usia","type":"dropdown","values":[{"key":"15-25","text":"15-25"},{"key":"26-35","text":"26-35"},{"key":">35","text":">35"}]}]},{"key":"education","description":"Edukasi & Trial","questions":[{"key":"data+rollover","text":"Data Rollover","type":"checkbox"},{"key":"freedom+combo","text":"Freedom Combo","type":"checkbox"},{"key":"stream+on","text":"Stream On","type":"checkbox"},{"key":"voice+sms","text":"Voice / SMS","type":"checkbox"},{"key":"4g+bonus","text":"4G Bonus","type":"checkbox"},{"key":"paypro","text":"Paypro","type":"checkbox"},{"key":"my+im3","text":"My IM3","type":"checkbox"},{"type":"line"},{"key":"customer+photo","text":"Upload Foto Pelanggan","type":"image"}]},{"key":"sales","description":"Form Penjualan","questions":[{"key":"new+phone","text":"No. Hp pelanggan, jika membeli SP baru","type":"phone"},{"key":"package","text":"Paket yang di beli","type":"dropdown","values":[{"key":"freedom+combo+m","text":"Freedom Combo M"},{"key":"freedom+combo+l","text":"Freedom Combo L"},{"key":"freedom+combo+xl","text":"Freedom Combo XL"},{"key":"freedom+combo+xxl","text":"Freedom Combo XXL"},{"key":"lainnya","text":"Lainnya"}]},{"key":"voucher","text":"Denom voucher yang dibeli","type":"dropdown","values":[{"key":"5000","text":"5K"},{"key":"10000","text":"10K"},{"key":"25000","text":"25K"},{"key":"50000","text":"50K"},{"key":"100000","text":"100K"},{"key":"200000","text":"200K"},{"key":"tidak+membeli","text":"Tidak membeli"}]},{"key":"balance","text":"Saldo kamu","type":"balance"}]},{"key":"sharing","description":"Customer Sharing Moment","questions":[{"key":"twitter","text":"Twitter","type":"text"},{"key":"facebook","text":"Facebook","type":"text"},{"key":"instagram","text":"Instagram","type":"text"}]}]',
			    "auth_code" => substr($faker->uuid, 0, 5),
			    "created_at" => $date,
			    "updated_at" => $date
		    ]
	    ]);
    }
}
