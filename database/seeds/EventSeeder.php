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
			    "survey" => '[{"key":"profile","description":"Profil Customer","questions":[{"key":"provider","text":"Provider yang digunakan","type":"dropdown","values":[{"key":"im3_ooredoo_pascabayar","text":"IM3 Ooredoo Pascabayar"},{"key":"im3_ooredoo_prabayar","text":"IM3 Ooredoo Prabayar"},{"key":"telkomsel_halo","text":"Telkomsel Halo"},{"key":"telkomsel_simpati","text":"Telkomsel Simpati"},{"key":"telkomsel_loop","text":"Telkomsel Loop"},{"key":"telkomsel_kartu_as","text":"Telkomsel Kartu As"},{"key":"xl_axiata_pascabayar","text":"XL Axiata Pascabayar"},{"key":"xl_axiata_prabayar","text":"XL Axiata Prabayar"},{"key":"3","text":"3"},{"key":"smartfren","text":"SmartFren"},{"key":"bolt","text":"Bolt"}]},{"key":"data_usage","text":"Seberapa banyak data yang digunakan dalam sebulan?","type":"dropdown","values":[{"key":"0-1_gb","text":"0 - 1 GB"},{"key":"1-3_gb","text":"1 - 3 GB"},{"key":"3-6_gb","text":"3 - 6 GB"},{"key":"6-10_gb","text":"6 - 10 GB"},{"key":"10-20_gb","text":"10 - 20 GB"},{"key":"20-30_gb","text":"20 - 30 GB"},{"key":">30_gb","text":"> 30 GB"}]},{"key":"phone_usage","text":"Seberapa banyak penggunaan telepon per bulannya?","type":"dropdown","values":[{"key":"0-5_menit","text":"0 - 5 menit"},{"key":"6-10_menit","text":"6 - 10 menit"},{"key":"11-20_menit","text":"11 - 20 menit"},{"key":"21-50_menit","text":"21 - 50 menit"},{"key":"51-90_menit","text":"51 - 90 menit"},{"key":">90_menit","text":"> 90 menit"}]},{"key":"sms_usage","text":"Seberapa banyak penggunaan SMS per bulannya?","type":"dropdown","values":[{"key":"0-10_sms","text":"0 - 10 SMS"},{"key":"11-25_sms","text":"11 - 25 SMS"},{"key":"25-50_sms","text":"25 - 50 SMS"},{"key":">50_sms","text":"> 50 SMS"}]},{"key":"name","text":"Nama","type":"text","class":"border-round required"},{"key":"phone","text":"No. Handphone","type":"phone","class":"border-round terminate-empty"},{"key":"gender","text":"Gender","type":"radio","values":[{"key":"laki-laki","text":"Laki-laki","checked":true},{"key":"perempuan","text":"Perempuan"}]},{"key":"job","text":"Pekerjaan","type":"dropdown","values":[{"key":"pelajar","text":"Pelajar"},{"key":"mahasiswa","text":"Mahasiswa"},{"key":"karyawan_swasta","text":"Karyawan Swasta"},{"key":"pns","text":"PNS"},{"key":"ibu_rumah_tangga","text":"Ibu Rumah Tangga"},{"key":"lainnya","text":"Lainnya"}]},{"key":"age","text":"Range usia","type":"dropdown","values":[{"key":"<15","text":"< 15"},{"key":"15-25","text":"15 - 25"},{"key":"26-35","text":"26 - 35"},{"key":">35","text":"> 35"}]}]},{"key":"education","description":"Edukasi & Trial","questions":[{"key":"education","text":"Edukasi paket","type":"checkboxes","values":[{"key":"data_rollover","text":"Data Rollover"},{"key":"stream_on","text":"Stream On"},{"key":"kuota_bonus_4g","text":"Kuota Bonus 4G"},{"key":"unlimited_call_sms","text":"Unlimited Call & SMS"},{"key":"no_time_band","text":"No time band"},{"key":"freedom_combo","text":"Freedom Combo"},{"key":"freedom_postpaid","text":"Freedom Postpaid"},{"key":"my_im3","text":"My IM3"},{"key":"paypro","text":"Paypro"}]},{"type":"line"},{"key":"customer_photo","text":"Upload Foto Pelanggan","type":"image","class":"required"}]},{"key":"sales","description":"Form Penjualan","questions":[{"key":"sales","type":"number_sales","number":{"key":"new_phone","text":"No. Hp pelanggan, jika membeli SP baru","placeholder":"Pilih nomor"},"package":{"key":"package","text":"Paket yang di beli","values":[{"key":"tidak_membeli_0","text":"Tidak membeli"},{"key":"freedom_combo_m_long_weekend_64900","text":"Freedom Combo M - Long Weekend"},{"key":"freedom_combo_m_59000","text":"Freedom Combo M"},{"key":"freedom_combo_l_99000","text":"Freedom Combo L"},{"key":"freedom_combo_xl_149000","text":"Freedom Combo XL"},{"key":"freedom_combo_xxl_199000","text":"Freedom Combo XXL"},{"key":"paket_nelpon_harian_ke_sesama_indosat_ooredoo_2750","text":"Paket Nelpon Harian Ke Sesama Indosat Ooredoo"},{"key":"paket_nelpon_mingguan_ke_sesama_indosat_ooredoo_16000","text":"Paket Nelpon Mingguan Ke Sesama Indosat Ooredoo"},{"key":"paket_nelpon_bulanan_ke_sesama_indosat_ooredoo_27500","text":"Paket Nelpon Bulanan Ke Sesama Indosat Ooredoo"},{"key":"paket_nelpon_harian_ke_semua_operator_6250","text":"Paket Nelpon Harian Ke Semua Operator"},{"key":"paket_nelpon_mingguan_ke_semua_operator_17000","text":"Paket Nelpon Mingguan Ke Semua Operator"},{"key":"paket_nelpon_bulanan_ke_semua_operator_65000","text":"Paket Nelpon Bulanan Ke Semua Operator"},{"key":"paket_sms_harian_1200","text":"Paket SMS Harian"},{"key":"paket_sms_mingguan_17000","text":"Paket SMS Mingguan"},{"key":"paket_sms_bulanan_22000","text":"Paket SMS Bulanan"}]},"voucher":{"key":"voucher","text":"Denom voucher yang dibeli","values":[{"key":"5000","text":"5K"},{"key":"10000","text":"10K"},{"key":"25000","text":"25K"},{"key":"50000","text":"50K"},{"key":"100000","text":"100K"},{"key":"200000","text":"200K"}]}}]},{"key":"sharing","description":"Customer Sharing Moment","questions":[{"key":"twitter","text":"Twitter","type":"text"},{"key":"facebook","text":"Facebook","type":"text"},{"key":"instagram","text":"Instagram","type":"text"},{"key":"hadiah_voucher","text":"Hadiah voucher","type":"checkbox"}]}]',
			    "kpi" => '[{"text":"Edukasi & Trial","field":"education","values":["data_rollover","stream_on","kuota_bonus_4g"],"type":"require_multiple","goal":30,"unit":"orang"},{"text":"Share Moment","field":"twitter","type":"require","goal":10,"unit":"orang"},{"text":"SP","field":"sales","type":"count","goal":10,"unit":"pcs"},{"text":"Add-on","field":"sales.package","type":"price","goal":100000,"unit":""}]',
			    "auth_code" => substr($faker->uuid, 0, 5),
			    "created_at" => $date,
			    "updated_at" => $date
		    ]
	    ]);
    }
}
