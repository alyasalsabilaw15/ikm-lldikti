<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PertanyaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tbl_pertanyaan')->insert([
            [
                'unsur' => 'U1',
                'no_urut' => 1,
                'pertanyaan' => 'Bagaimana pendapat Saudara tentang kesesuaian persyaratan pelayanan dengan jenis pelayanannya?'
            ],
            [
                'unsur' => 'U2',
                'no_urut' => 2,
                'pertanyaan' => 'Bagaimana pemahaman Saudara tentang kemudahan prosedur pelayanan di LLDIKTI Wilayah IV?'
            ],
            [
                'unsur' => 'U3',
                'no_urut' => 3,
                'pertanyaan' => 'Bagaimana pendapat Saudara tentang kecepatan waktu dalam memberikan pelayanan di LLDIKTI Wilayah IV?'
            ],
            [
                'unsur' => 'U4',
                'no_urut' => 4,
                'pertanyaan' => 'Pada LLDIKTI Wilayah IV seluruh Layanan Tidak dikenakan biaya/tarif (GRATIS), bagaimana pendapat Saudara?'
            ],
            [
                'unsur' => 'U5',
                'no_urut' => 5,
                'pertanyaan' => 'Bagaimana Pendapat Saudara tentang kesesuaian produk pelayanan (hasil) antara yang tercantum dalam standar pelayanan dengan hasil yang diberikan?'
            ],
            [
                'unsur' => 'U6',
                'no_urut' => 6,
                'pertanyaan' => 'Bagaimana pendapat Saudara tentang kompetensi/ kemampuan petugas dalam memberikan pelayanan?',
            ],
            [
                'unsur' => 'U7',
                'no_urut' => 7,
                'pertanyaan' => 'Bagaimana pendapat Saudara perilaku petugas dalam pelayanan terkait kesopanan dan keramahan'
            ],
            [
                'unsur' => 'U8',
                'no_urut' => 8,
                'pertanyaan' => 'Bagaimana pendapat Saudara tentang kualitas sarana dan prasarana (ULT atau aplikasi yang digunakan dalam penerimaan layanan seperti EMPAT, JAD dan pindah homebase) pada LLDIKTI Wilayah IV?'
            ],
            [
                'unsur' => 'U9',
                'no_urut' => 9,
                'pertanyaan' => 'Bagaimana pendapat Saudara tentang penanganan pengaduan, saran dan masukan di unit pelayanan ini?'
            ]
        ]);
    }
}
