<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import UserLayout from '@/layouts/UserLayout.vue'

defineOptions({ layout: UserLayout })

// (ini boleh tetap ada, tapi sebenernya belum kepakai di halaman ini)
const kategoriData = {
  aktif: {
    Umum: [
      'Telekomunikasi','Perjalanan Dinas Dalam Negeri','Perjalanan Dinas Luar Negeri',
      'Penggunaan Fasilitas Kantor','Rapat Pimpinan','Penyediaan Konsumsi',
      'Pengurusan Kendaraan Dinas','Pengelolaan Jaringan Listrik, Air, Telepon dan Komputer',
      'Ketertiban dan Keamanan','Administrasi Pengelolaan Parkir',
      'Administrasi Pakaian Dinas Pegawai, Satpam, Petugas Kebersihan, dan Pegawai Lainnya'
    ],
    Pemerintahan: ['Otonomi Daerah','Pemerintahan Umum','Hukum'],
    'Kesejahteraan Rakyat': [
      'Kebijakan di bidang Sosial yang dilakukan oleh Pemerintah Daerah','Kesejahteraan Sosial Anak',
      'Rehabilitasi Sosial','Rehabilitasi Sosial Tuna Sosial','Rehabilitasi Sosial Korban Penyalahgunaan NAPZA',
      'Pengumpulan dan Pengelolaan sumber dana bantuan sosial','Perlindungan Sosial Korban Tindak Kekerasan dan Pekerja Migran',
      'Perlindungan Sosial Korban Bencana Sosial','Perlindungan Sosial Korban Bencana Alam','Jaminan Sosial',
      'Pemberdayaan keluarga dan kelembagaan Sosial','Pemberdayaan komunitas adat terpencil',
      'Penanggulangan Kemiskinan Perkotaan dan Perdesaan','Kepahlawanan, Keperintisan dan Kesetiakawanan Sosial'
    ],
    Keuangan: [
      'Surat Penyedia Dana (SPP, SPM dan SP2D): UP, GU, TU, LS','Pendapatan','Belanja',
      'Pembiayaan Daerah','Dokumen Penatausahaan Keuangan','Pertanggungjawaban Penggunaan Dana',
      'Daftar Gaji','Kartu Gaji','Data Rekening Bendahara Umum Daerah (BUD)','Laporan Keuangan'
    ]
  },
  vital: [
    'Kebijakan dan program pemerintah tentang masalah sosial','Bantuan Sosial',
    'Penghargaan kepada pahlawan','Perintis kemerdekaan','Taman Makam Pahlawan',
    'Organisasi dan kelembagaan masyarakat sosial','Korban kekacauan, pengungsian dan rehabilitasi',
    'Suku terasing','Pembinaan Komunitas Adat Terpencil (PKAT)'
  ]
}
</script>

<template>
  <Head title="Unggah File" />

  <div class="py-8 md:py-12 px-4 md:px-6">
    <div class="max-w-7xl mx-auto">

      <h1 class="text-2xl md:text-4xl font-extrabold mb-8 md:mb-12 tracking-tight">
        Unggah File
      </h1>

      <div class="flex flex-col md:flex-row gap-8 md:gap-16 justify-center mt-12 md:mt-20">

        <!-- FIX DISINI -->
        <Link href="/unggah/aktif-inaktif" class="group flex flex-col items-center w-full md:w-auto">
          <div class="relative w-32 md:w-36 h-24 md:h-28 bg-[#2f55a4] rounded-2xl shadow-2xl group-hover:scale-105 transition-transform flex items-center justify-center">
            <div class="absolute -top-3 left-0 w-14 md:w-16 h-5 md:h-6 bg-[#2f55a4] rounded-t-md"></div>
            <span class="text-4xl md:text-5xl">📄</span>
          </div>

          <span class="mt-4 md:mt-6 text-lg md:text-2xl font-black text-gray-800 uppercase tracking-tighter">
            Aktif / Inaktif
          </span>
          <p class="text-xs md:text-sm text-gray-600 text-center mt-2 px-4 max-w-[200px]">
  Arsip aktif digunakan dalam kegiatan sehari-hari (≤ 3 tahun), 
  sedangkan arsip inaktif jarang digunakan namun masih disimpan.
</p>
        </Link>

        <Link href="/unggah/vital" class="group flex flex-col items-center w-full md:w-auto">
          <div class="relative w-32 md:w-36 h-24 md:h-28 bg-[#94a3d8] rounded-2xl shadow-2xl group-hover:scale-105 transition-transform flex items-center justify-center">
            <div class="absolute -top-3 left-0 w-14 md:w-16 h-5 md:h-6 bg-[#94a3d8] rounded-t-md"></div>
            <span class="text-4xl md:text-5xl">🛡️</span>
          </div>

          <span class="mt-4 md:mt-6 text-lg md:text-2xl font-black text-gray-800 uppercase tracking-tighter">
            Vital
          </span>
          <p class="text-xs md:text-sm text-gray-600 text-center mt-2 px-4 max-w-[200px]">
  Arsip penting yang harus dijaga karena menyangkut kelangsungan 
  organisasi dan tidak dapat digantikan jika hilang.
</p>
        </Link>

      </div>
    </div>
  </div>
</template>