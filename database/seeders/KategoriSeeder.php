<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Kategori;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        // Reset data
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Kategori::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // =========================
        // DATA KATEGORI
        // =========================
        $data = [

            'Umum' => [
                'Ketatausahaan dan Kerumahtanggaan' => [
                    'Telekomunikasi',

                    'Perjalanan Dinas Dalam Negeri' => [
                        'Perjalanan Dinas Kepala Daerah',
                        'Perjalanan Dinas DPRD',
                        'Perjalanan Dinas Pegawai',
                    ],

                    'Perjalanan Dinas Luar Negeri' => [
                        'Perjalanan Dinas Kepala Daerah',
                        'Perjalanan Dinas DPRD',
                        'Perjalanan Dinas Pegawai',
                    ],

                    'Penggunaan Fasilitas Kantor',
                    'Rapat Pimpinan',
                    'Penyediaan Konsumsi',

                    'Pengurusan Kendaraan Dinas' => [
                        'Pengurusan surat-surat kendaraan dinas',
                        'Pemeliharaan dan perbaikan',
                        'Pengurusan kehilangan dan masalah kendaraan',
                    ],

                    'Pemeliharaan Gedung, Taman, dan Peralatan Kantor' => [
                        'Pertamanan / Landscape',
                        'Penghijauan',
                        'Perbaikan Gedung',
                        'Perbaikan Peralatan Kantor',
                        'Perbaikan Rumah Dinas/ Wisma',
                        'Kebersihan Gedung dan Taman',
                    ],

                    'Pengelolaan Jaringan Listrik, Air, Telepon dan Komputer' => [
                        'Perbaikan / Pemeliharaan',
                        'Pemasangan',
                    ],

                    'Ketertiban dan Keamanan' => [
                        'Pengamanan, Penjagaan, dan Pengawalan terhadap Pejabat, Kantor dan Rumah Dinas',
                        'Laporan Ketertiban dan Keamanan',
                    ],

                    'Administrasi Pengelolaan Parkir',
                    'Administrasi Pakaian Dinas Pegawai, Satpam, Petugas Kebersihan, dan Pegawai Lainnya',
                ],
            ],

            'Pemerintahan' => [
                'Otonomi Daerah' => [
                    'Kebijakan di bidang Otonomi Daerah yang dilakukan oleh Pemerintah Daerah',
                    'Penyelenggaraan Pemerintah Daerah (Fasilitasi, Bimbingan, Pengawasan, Monitoring dan Evaluasi)',
                    'Penataan Daerah, Pembinaan Daerah Pemekaran, Otonomi Khusus dan Dewan Pertimbangan Otonomi Daerah',

                    'Pemilihan Kepala Daerah, DPRD, dan Hubungan Antar Lembaga' => [
                        'Penyelenggaraan Pemilihan Umum Kepala Daerah',
                        'Administrasi Kepala Daerah dan DPRD',
                        'Penyiapan Perumusan Kebijakan Pemberdayaan Kapasitas Kepala Daerah dan DPRD di Bidang Pemerintahan',
                        'Hubungan Antar Lembaga Daerah (Pemerintah Daerah dan DPRD)',
                        'Assosiasi Daerah',
                    ],

                    'Peningkatan Kapasitas Dan Evaluasi Kinerja Daerah' => [
                        'Kinerja Penyelenggaraan Pemerintahan Daerah',
                        'Kemampuan Penyelenggaraan Otonomi Daerah',
                        'Pengembangan Kapasitas Daerah',
                    ],

                    'LKPJ/LKPJAMJ dan LPPD',
                ],
            ],

            'Pemerintahan Umum' => [
                'Kebijakan di bidang Pemerintahan Umum yang dilakukan oleh Pemerintah Daerah',

                'Dekonsentrasi dan Kerjasama' => [
                    'Fasilitasi Dekonsentrasi',
                    'Fasilitasi Tugas Gubernur',
                    'Fasilitasi Kerjasama Daerah',
                    'Fasilitasi Kecamatan',
                    'Fasilitasi Pelayanan Umum',
                ],

                'Wilayah Administrasi dan Perbatasan' => [
                    'Toponimi dan Data Wilayah',
                    'Pengembangan Batas Antar Negara',
                    'Batas Antar Daerah',
                    'Penataan Batas Wilayah',
                    'Pemeliharaan Batas Wilayah',
                ],
            ],

            'Hukum' => [

                'Program Legislasi' => [
                    'Bahan/Materi Program Legislasi Daerah',
                    'Program Legislasi',
                ],

                'Rancangan Peraturan Perundang-Undangan' => [
                    'Rancangan Peraturan Daerah',
                ],

                'Keputusan/Ketetapan Pimpinan Pemerintah' => [
                    'Keputusan Gubernur',
                    'Keputusan Bupati',
                    'Keputusan Walikota',
                    'Keputusan Sekretaris Daerah Provinsi',
                    'Keputusan Sekretaris Daerah Kabupaten',
                    'Keputusan Sekretaris Daerah Kota',
                ],

                'Instruksi/Surat Edaran' => [
                    'Instruksi Provinsi',
                    'Instruksi Kabupaten',
                    'Instruksi Kota',
                    'Instruksi Eselon II',
                ],

                'Surat Perintah' => [
                    'Surat Perintah Gubernur',
                    'Surat Perintah Bupati',
                    'Surat Perintah Walikota',
                    'Surat Perintah Eselon II',
                ],

                'Standar/Pedoman/Prosedur Kerja/Petunjuk Pelaksanaan/Petunjuk Teknis',

                'Nota Kesepakatan/Memorandum of Understanding (MoU)/Kontrak/Perjanjian Kerja Sama' => [
                    'Dalam Negeri',
                    'Luar Negeri',
                ],

                'Dokumentasi Hukum (antara lain: Undang-Undang, Peraturan Pemerintah, Keputusan Presiden dan Peraturan-Peraturan yang dijadikan referensi)',

                'Kasus/Sengketa Hukum' => [
                    'Pidana Kasus/ sengketa pidana, baik kejahatan maupun pelanggaran',
                    'Perdata Kasus/sengketa perdata',
                    'Tata Usaha Negara',
                    'Perburuhan',
                    'Arbitrase',
                    'Sengketa Adat',
                ],

                'Perijinan',

                'Hak atas Kekayaan Intelektual (HaKI)' => [
                    'Hak Cipta',
                    'Hak Paten',
                    'Hak Desain Industri',
                    'Hak Rahasia Dagang',
                    'Hak Merk',
                ],

                'Permohonan HaKI yang Ditolak',
            ],

            'Kesejahteraan Rakyat' => [
                'Sosial' => [
                    'Kebijakan di bidang Sosial yang dilakukan oleh Pemerintah Daerah',
                    'Kesejahteraan Sosial Anak' => [
                        'Kesejahteraan Sosial Anak Balita',
                        'Kesejahteraan Sosial Anak Terlantar',
                        'Kesejahteraan Sosial Anak Berhadapan dengan Hukum',
                        'Kesejahteraan Sosial Anak dengan Kecacatan',
                        'Kesejahteraan Sosial Anak Perlindungan Khusus',
                    ],

                    'Rehabilitasi Sosial' => [
                        'Rehabilitasi Sosial Orang dengan Kecacatan Tubuh dan Bekas Penderita Penyakit Kronis, Netra dan Rungu Wicara, Mental',
                        'Kelembagaan dan Advokasi Sosial',
                        'Asistensi dan Pemeliharaan Kesejahteraan Sosial',
                    ],

                    'Rehabilitasi Sosial Tuna Sosial' => [
                        'Gelandangan, Pengemis, dan Pemulung',
                        'Tuna Susila dan Korban Trafficking Perempuan',
                        'Warga Binaan Lembaga Pemasyarakatan meliputi Penyiapan, Reintegrasi',
                        'Pelayanan Sosial Orang dengan HIV/AIDS dan Kelompok Minoritas',
                    ],

                    'Rehabilitasi Sosial Korban Penyalahgunaan Napza' => [
                        'Pelayanan Sosial Lanjut Usia',
                        'Pelayanan Sosial Dalam dan Luar Panti',
                        'Pengembangan Kelembagaan meliputi Pembinaan Lembaga, Kerjasama Lembaga', 
                        'Advokasi dan Pelayanan Sosial Kedaruratan',
                    ],
                    
                    'Pengumpulan dan Pengelolaan Sumber Dana Bantuan Sosial',
                    'Perlindungan Sosial Korban Tindak Kekerasan dan Pekerja Migran',

                    'Perlindungan Sosial Bencana Sosial'=> [
                        'Ketahanan Sosial Masyarakat meliputi Keserasian Sosial, Penguatan Sumber Daya',
                        'Tanggap Darurat meliputi Bantuan Darurat, Advokasi Sosial',
                        'Pemulihan Sosial meliputi Penguatan Sosial, Reintegrasi Sosial',
                        'Kerjasama Meliputi Kerjasama Pemerintah, Kerjasama Non Pemerintah',
                    ],
                    
                    'Perlindungan Sosial Bencana Alam' => [
                        'Kesiapsiagaan dan mitigasi',
                        'Tanggap Darurat meliputi Bantuan Darurat, Advokasi Sosial',
                        'Pemulihan Sosial dan Penguatan Sosial', 
                        'Kerjasama',
                    ],

                    'Jaminan Sosial'=> [
                        'Seleksi dan verifikasi',
                        'Asuransi kesejahteraan sosial meliputi kelembagaan, pengelolaan premi',
                        'Bantuan langsung dan tunjangan berkelanjutan meliputi pendampingan dan penyaluran',
                        'Kerjasama',
                    ],

                    'Pemberdayaan Keluarga dan Kelembagaan Sosial' => [
                        'Ketahanan Keluarga',
                        'Asistensi Keluarga dan Pemberdayaan Perempuan',
                        'Tenaga Kesejahteraan Sosial Masyarakat dan Organisasi Sosial',
                        'Kemitraan dunia usaha',
                        'Karang Taruna meliputi Kelembagaan, Pengembangan Kapasitas',
                    ],

                    'Pemberdayaan Komunitas Adat Terpencil'=> [
                        'Persiapan Pemberdayaan',
                        'Pemberdayaan Sumber Daya Manusia',
                        'Penggalian dan Pengembangan Potensi',
                        'Keserasian dan Penguatan Komunitas Adat Terpencil',
                        'Kerjasama Kelembagaan',
                    ],

                    'Penanggulangan Kemiskinan Perkotaan dan Perdesaan'=> [
                        'Identifikasi dan Analisis',
                        'Pengembangan Kapasitas',
                        'Penataan Sosial Lingkungan Kumuh',
                        'Advokasi Sosial dan Pengembangan Aksesibilitas',
                        'Bantuan Langsung',
                        'Kerjasama Kelembagaan',
                    ],

                    'Kepahlawanan, Keperintisan  dan Kesetiakawanan Sosial' => [
                        'Penghargaan  dan Kesejahteraan Keluarga Pahlawan',
                        'Pelestarian Nilai-Nilai Kepahlawanan dan Keperintisan',
                        'Pengembangan Kesetiakawanan Sosial',
                        'Pengelolaan Taman Makam Pahlawan',
                    ],
                ],
            ],

            'Keuangan' => [
                'Keuangan Daerah' => [
                    
                    'Pelaksanaan Anggaran' => [
                        'Surat Penyedia Dana (SPP, SPM dan SP2D): UP, GU, TU, LS',
                        'Pendapatan',
                        'Belanja',
                        'Pembiayaan Daerah',
                        'Dokumen Penatausahaan Keuangan',
                        'Pertanggungjawaban Penggunaan Dana',
                        'Daftar Gaji',
                        'Kartu Gaji',
                        'Data Rekening Bendahara Umum Daerah (BUD)',
                        'Laporan Keuangan',
                    ],
                ],
            ],

            'VITAL' => [
                'Kebijakan dan program pemerintah tentang masalah sosial',
                'Bantuan Sosial',
                'Penghargaan kepada pahlawan',
                'Perintis kemerdekaan',
                'Taman Makam Pahlawan',
                'Organisasi dan kelembagaan masyarakat sosial',
                'Korban kekacauan, pengungsian dan rehabilitasi',
                'Program Keluarga Harapan',
                'Suku terasing',
                'Pembinaan Komunitas Adat Terpencil (PKAT)',
            ],
        ];


        // Insert data
        $this->insertKategori($data);
    }

    // =========================
    // FUNCTION RECURSIVE
    // =========================
    private function insertKategori($data, $parentId = null)
    {
        foreach ($data as $key => $value) {

            if (is_array($value)) {

                $kategori = Kategori::create([
                    'nama' => is_string($key) ? $key : $value,
                    'parent_id' => $parentId
                ]);

                $this->insertKategori($value, $kategori->id);

            } else {

                Kategori::create([
                    'nama' => $value,
                    'parent_id' => $parentId
                ]);
            }
        }
    }
}