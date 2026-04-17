<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Kategori;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        // RESET TOTAL BIAR ID STABIL
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Kategori::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // ========================
        // UMUM
        // ========================
        $umum = Kategori::create(['nama' => 'Umum', 'parent_id' => null]);

        $ketatausahaan = Kategori::create([
            'nama' => 'Ketatausahaan dan Kerumahtanggaan',
            'parent_id' => $umum->id
        ]);

        Kategori::create(['nama' => 'Telekomunikasi', 'parent_id' => $umum->id]);

        // Perjalanan Dinas Dalam Negeri
        $pdn = Kategori::create([
            'nama' => 'Perjalanan Dinas Dalam Negeri',
            'parent_id' => $umum->id
        ]);

        Kategori::create(['nama' => 'Perjalanan Dinas Kepala Daerah', 'parent_id' => $pdn->id]);
        Kategori::create(['nama' => 'Perjalanan Dinas DPRD', 'parent_id' => $pdn->id]);
        Kategori::create(['nama' => 'Perjalanan Dinas Pegawai', 'parent_id' => $pdn->id]);

        // Perjalanan Dinas Luar Negeri
        $pln = Kategori::create([
            'nama' => 'Perjalanan Dinas Luar Negeri',
            'parent_id' => $umum->id
        ]);

        Kategori::create(['nama' => 'Perjalanan Dinas Kepala Daerah', 'parent_id' => $pln->id]);
        Kategori::create(['nama' => 'Perjalanan Dinas DPRD', 'parent_id' => $pln->id]);
        Kategori::create(['nama' => 'Perjalanan Dinas Pegawai', 'parent_id' => $pln->id]);

        Kategori::create(['nama' => 'Penggunaan Fasilitas Kantor', 'parent_id' => $umum->id]);
        Kategori::create(['nama' => 'Rapat Pimpinan', 'parent_id' => $umum->id]);
        Kategori::create(['nama' => 'Penyediaan Konsumsi', 'parent_id' => $umum->id]);

        // Kendaraan
        $kendaraan = Kategori::create([
            'nama' => 'Pengurusan Kendaraan Dinas',
            'parent_id' => $umum->id
        ]);

        Kategori::create(['nama' => 'Pengurusan surat-surat kendaraan dinas', 'parent_id' => $kendaraan->id]);
        Kategori::create(['nama' => 'Pemeliharaan dan perbaikan', 'parent_id' => $kendaraan->id]);
        Kategori::create(['nama' => 'Pengurusan kehilangan dan masalah kendaraan', 'parent_id' => $kendaraan->id]);

        // Gedung
        $gedung = Kategori::create([
            'nama' => 'Pemeliharaan Gedung, Taman, dan Peralatan Kantor',
            'parent_id' => $umum->id
        ]);

        Kategori::create(['nama' => 'Pertamanan / Landscape', 'parent_id' => $gedung->id]);
        Kategori::create(['nama' => 'Penghijauan', 'parent_id' => $gedung->id]);
        Kategori::create(['nama' => 'Perbaikan Gedung', 'parent_id' => $gedung->id]);
        Kategori::create(['nama' => 'Perbaikan Peralatan Kantor', 'parent_id' => $gedung->id]);

        // Jaringan
        $jaringan = Kategori::create([
            'nama' => 'Pengelolaan Jaringan Listrik, Air, Telepon dan Komputer',
            'parent_id' => $umum->id
        ]);

        Kategori::create(['nama' => 'Perbaikan / Pemeliharaan', 'parent_id' => $jaringan->id]);
        Kategori::create(['nama' => 'Pemasangan', 'parent_id' => $jaringan->id]);

        // Keamanan
        $keamanan = Kategori::create([
            'nama' => 'Ketertiban dan Keamanan',
            'parent_id' => $umum->id
        ]);

        Kategori::create(['nama' => 'Pengamanan, Penjagaan, dan Pengawalan', 'parent_id' => $keamanan->id]);
        Kategori::create(['nama' => 'Laporan Ketertiban dan Keamanan', 'parent_id' => $keamanan->id]);

        // ========================
        // PEMERINTAHAN
        // ========================
        $pemerintahan = Kategori::create(['nama' => 'Pemerintahan', 'parent_id' => null]);

        Kategori::create(['nama' => 'Otonomi Daerah', 'parent_id' => $pemerintahan->id]);
        Kategori::create(['nama' => 'Pemilihan Kepala Daerah', 'parent_id' => $pemerintahan->id]);
        Kategori::create(['nama' => 'Administrasi Kepala Daerah dan DPRD', 'parent_id' => $pemerintahan->id]);

        // ========================
        // HUKUM
        // ========================
        $hukum = Kategori::create(['nama' => 'Hukum', 'parent_id' => null]);

        Kategori::create(['nama' => 'Program Legislasi', 'parent_id' => $hukum->id]);
        Kategori::create(['nama' => 'Rancangan Peraturan Daerah', 'parent_id' => $hukum->id]);
        Kategori::create(['nama' => 'Keputusan Kepala Daerah', 'parent_id' => $hukum->id]);
        Kategori::create(['nama' => 'Instruksi / Surat Edaran', 'parent_id' => $hukum->id]);
        Kategori::create(['nama' => 'Surat Perintah', 'parent_id' => $hukum->id]);
        Kategori::create(['nama' => 'MoU / Perjanjian Kerja Sama', 'parent_id' => $hukum->id]);
        Kategori::create(['nama' => 'Kasus Pidana', 'parent_id' => $hukum->id]);
        Kategori::create(['nama' => 'Kasus Perdata', 'parent_id' => $hukum->id]);

        // ========================
        // KEUANGAN
        // ========================
        $keuangan = Kategori::create(['nama' => 'Keuangan', 'parent_id' => null]);

        Kategori::create(['nama' => 'Pelaksanaan Anggaran', 'parent_id' => $keuangan->id]);
        Kategori::create(['nama' => 'Pendapatan', 'parent_id' => $keuangan->id]);
        Kategori::create(['nama' => 'Belanja', 'parent_id' => $keuangan->id]);
        Kategori::create(['nama' => 'Laporan Keuangan', 'parent_id' => $keuangan->id]);

        // ========================
        // VITAL
        // ========================
        $vital = Kategori::create(['nama' => 'VITAL', 'parent_id' => null]);

        Kategori::create(['nama' => 'Bantuan Sosial', 'parent_id' => $vital->id]);
        Kategori::create(['nama' => 'Program Keluarga Harapan', 'parent_id' => $vital->id]);
    }
}