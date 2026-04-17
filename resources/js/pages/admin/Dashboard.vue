<script setup>
import { Head } from '@inertiajs/vue3'
import AdminLayout from '@/layouts/AdminLayout.vue'
import { Search, Calendar } from 'lucide-vue-next'

defineOptions({
  layout: AdminLayout
})
</script>

<template>
  <Head title="Dashboard Admin" />

  <div class="p-6 space-y-6 bg-gray-100 min-h-screen text-gray-900">

    <!-- TITLE -->
    <h1 class="text-3xl font-bold">
      Dashboard Administrator
    </h1>

    <!-- SEARCH -->
    <div class="bg-[#2c5d73] p-4 rounded-xl flex flex-wrap gap-3 items-center shadow">

      <div class="relative flex-1 min-w-[200px]">
        <Search class="absolute left-3 top-3 text-gray-400 w-4" />
        <input type="text"
          placeholder="Cari dokumen, nomor surat,..."
          class="w-full pl-10 pr-3 py-2 rounded-lg text-sm outline-none text-gray-900" />
      </div>

      <select class="px-3 py-2 rounded-lg text-sm text-gray-900">
        <option>Semua Kategori</option>
        (<option>Vital</option>
        <option>Aktif</option>
        <option>Inaktif</option>)
      </select>

      <div class="relative">
        <Calendar class="absolute left-2 top-2 text-gray-400 w-4" />
        <input type="date" class="pl-8 pr-2 py-2 rounded-lg text-sm text-gray-900" />
      </div>

      <div class="relative">
        <Calendar class="absolute left-2 top-2 text-gray-400 w-4" />
        <input type="date" class="pl-8 pr-2 py-2 rounded-lg text-sm text-gray-900" />
      </div>

      <button class="bg-white px-4 py-2 rounded-lg text-sm font-semibold text-gray-900">
        Cari
      </button>
    </div>

    <!-- STATS -->
    <div class="grid md:grid-cols-3 gap-4">

      <div class="bg-[#7fa6b8] p-5 rounded-xl shadow text-gray-900">
        <p class="text-sm">Dokumen terlihat</p>
        <h2 class="text-2xl font-bold">12</h2>
      </div>

      <div class="bg-[#7fa6b8] p-5 rounded-xl shadow text-gray-900">
        <p class="text-sm">Dokumen diunduh</p>
        <h2 class="text-2xl font-bold">3</h2>
      </div>

      <div class="bg-[#7fa6b8] p-5 rounded-xl shadow text-gray-900">
        <p class="text-sm">Favorit</p>
        <h2 class="text-2xl font-bold">15</h2>
      </div>

    </div>

    <!-- GRID -->
    <div class="grid md:grid-cols-2 gap-6">

      <!-- TIPE DOKUMEN -->
      <div>
        <div class="bg-blue-700 text-white px-4 py-2 rounded-t-lg font-semibold">
          Tipe Dokumen
        </div>

        <div class="bg-[#7fa6b8] p-4 rounded-b-lg space-y-3 shadow">

          <div class="flex justify-between bg-white px-3 py-2 rounded">
            <span>Dokumen</span>
            <span>9 Jumlah</span>
          </div>

          <div class="flex justify-between bg-white px-3 py-2 rounded">
            <span>Foto / Gambar</span>
            <span>10 Jumlah</span>
          </div>

          <div class="flex justify-between bg-white px-3 py-2 rounded">
            <span>Video</span>
            <span>6 Jumlah</span>
          </div>

          <div class="flex justify-between bg-white px-3 py-2 rounded">
            <span>Audio</span>
            <span>8 Jumlah</span>
          </div>

        </div>
      </div>

      <!-- MENUNGGU -->
      <div>
        <div class="bg-blue-700 text-white px-4 py-2 rounded-t-lg font-semibold flex justify-between">
          <span>Menunggu Persetujuan</span>
          <button class="text-xs bg-white text-blue-700 px-2 py-1 rounded">
            Lihat Semua
          </button>
        </div>

        <div class="bg-[#7fa6b8] p-4 rounded-b-lg space-y-3 shadow">

          <div class="bg-white p-3 rounded flex justify-between items-center">
            <div>
              <p class="font-semibold text-sm text-gray-900">Draft Surat Edaran SPBE</p>
              <p class="text-xs text-gray-500">Oleh: Ahmad Yani • 24 Feb 2026</p>
            </div>
            <div class="w-6 h-6 bg-blue-700 rounded"></div>
          </div>

          <div class="bg-white p-3 rounded flex justify-between items-center">
            <div>
              <p class="font-semibold text-sm text-gray-900">Draft Surat Edaran SPBE</p>
              <p class="text-xs text-gray-500">Oleh: Ahmad Yani • 24 Feb 2026</p>
            </div>
            <div class="w-6 h-6 bg-blue-700 rounded"></div>
          </div>

          <div class="bg-white p-3 rounded flex justify-between items-center">
            <div>
              <p class="font-semibold text-sm text-gray-900">Draft Surat Edaran SPBE</p>
              <p class="text-xs text-gray-500">Oleh: Ahmad Yani • 24 Feb 2026</p>
            </div>
            <div class="w-6 h-6 bg-blue-700 rounded"></div>
          </div>

        </div>
      </div>

    </div>

    <!-- AKTIVITAS TERBARU -->
    <div>

      <div class="flex justify-between items-center mb-3">
        <h2 class="bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold">
          Aktivitas Terbaru
        </h2>

        <button class="text-sm text-gray-700">
          Lihat Semua
        </button>
      </div>

      <div class="space-y-4">

        <div class="bg-[#7fa6b8] p-4 rounded-xl shadow flex items-center gap-4">

          <div class="w-14 h-14 bg-gray-200 rounded-lg"></div>

          <div class="flex-1 space-y-2">
            <p class="font-semibold">Nama File</p>
            <p class="text-sm text-gray-700">Jenis File • 12/10/13</p>
          </div>

          <div class="bg-white px-4 py-1 rounded-full text-xs">
            Tipe Akses
          </div>

        </div>

        <div class="bg-[#7fa6b8] p-4 rounded-xl shadow flex items-center gap-4">

          <div class="w-14 h-14 bg-gray-200 rounded-lg"></div>

          <div class="flex-1 space-y-2">
            <p class="font-semibold">Nama File</p>
            <p class="text-sm text-gray-700">Jenis File • 10/6/13</p>
          </div>

          <div class="bg-white px-4 py-1 rounded-full text-xs">
            Tipe Akses
          </div>

        </div>

        <div class="bg-[#7fa6b8] p-4 rounded-xl shadow flex items-center gap-4">

          <div class="w-14 h-14 bg-gray-200 rounded-lg"></div>

          <div class="flex-1 space-y-2">
            <p class="font-semibold">Nama File</p>
            <p class="text-sm text-gray-700">Jenis File • 5/27/15</p>
          </div>

          <div class="bg-white px-4 py-1 rounded-full text-xs">
            Tipe Akses
          </div>

        </div>

      </div>

    </div>

  </div>
</template>