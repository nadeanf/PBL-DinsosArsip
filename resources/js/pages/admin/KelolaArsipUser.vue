<script setup>
import { Head } from '@inertiajs/vue3'
import AdminLayout from '@/layouts/AdminLayout.vue'
import { EyeOff, Trash2, ChevronDown } from 'lucide-vue-next'

defineOptions({
  layout: AdminLayout
})

// 🔥 DATA DUMMY
const arsip = [
  {
    id: 1,
    judul: 'Surat Tugas Dinas Kabupaten Boyolali - Desember 2022',
    tanggal: '08/03/2025',
    tipe: 'PDF',
    uploader: 'Admin',
    akses: 'Public'
  },
  {
    id: 2,
    judul: 'Laporan Kegiatan Sosial - Januari 2023',
    tanggal: '12/01/2025',
    tipe: 'DOCX',
    uploader: 'User A',
    akses: 'Private'
  },
  {
    id: 3,
    judul: 'Proposal Bantuan Masyarakat',
    tanggal: '20/02/2025',
    tipe: 'PDF',
    uploader: 'Admin',
    akses: 'Public'
  },
  {
    id: 4,
    judul: 'Data Penerima Bantuan 2024',
    tanggal: '01/02/2025',
    tipe: 'XLSX',
    uploader: 'User B',
    akses: 'Private'
  },
  {
    id: 5,
    judul: 'Dokumentasi Kegiatan Lapangan',
    tanggal: '15/03/2025',
    tipe: 'PDF',
    uploader: 'Admin',
    akses: 'Public'
  }
]
</script>

<template>
  <Head title="Kelola Arsip User" />

  <div class="p-6 bg-gray-100 min-h-screen">

    <!-- TITLE -->
    <h1 class="text-3xl font-bold text-black mb-6">
      Kelola Arsip User
    </h1>

    <!-- LIST -->
    <div class="space-y-4">

      <div
        v-for="item in arsip"
        :key="item.id"
        class="flex items-center gap-4"
      >
        
        <!-- CARD -->
        <div class="flex-1 bg-[#7da0ad] p-4 rounded-xl shadow flex items-center gap-4 text-black">

          <!-- ICON FILE -->
          <div class="w-14 h-14 bg-gray-200 rounded-md"></div>

          <!-- CONTENT -->
          <div>
            <h3 class="font-semibold">
              {{ item.judul }}
            </h3>

            <div class="flex items-center gap-2 mt-2 text-xs">
              <span class="bg-white px-2 py-1 rounded-full">
                Diunggah oleh {{ item.uploader }}
              </span>
              <span class="bg-white px-2 py-1 rounded-full">
                {{ item.tanggal }}
              </span>
              <span class="bg-white px-2 py-1 rounded-full">
                {{ item.tipe }}
              </span>
            </div>
          </div>
        </div>

        <!-- ACTIONS -->
        <div class="flex items-center gap-2">

          <!-- DROPDOWN -->
          <button class="bg-gray-300 px-4 py-2 rounded-lg text-sm flex items-center gap-2">
            {{ item.akses }}
            <ChevronDown class="w-4 h-4" />
          </button>

          <!-- HIDE -->
          <button class="bg-gray-300 p-2 rounded-lg">
            <EyeOff class="w-5 h-5 text-black" />
          </button>

          <!-- DELETE -->
          <button class="bg-gray-300 p-2 rounded-lg">
            <Trash2 class="w-5 h-5 text-red-600" />
          </button>

        </div>

      </div>

    </div>

  </div>
</template>