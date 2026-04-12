<script setup>
import { router } from '@inertiajs/vue3'
import { arsip } from '@/routes'
import { ref } from 'vue'
import { Eye, Download, Heart } from 'lucide-vue-next'
import AuthLayout from '@/layouts/AuthLayout.vue'

defineOptions({
  layout: AuthLayout
})

// state input
const search = ref('')
const kategori = ref('')
const tanggal = ref('')

// function search
const handleSearch = () => {
  router.get(arsip().url, {
    search: search.value,
    kategori: kategori.value,
    tanggal: tanggal.value
  })
}
</script>

<template>

  <div class="p-6 bg-[#f3f4f6] min-h-screen space-y-6">

    <!-- Judul -->
    <h1 class="text-2xl font-bold text-gray-800">Selamat Datang!</h1>

    <!-- Bar abu -->
    <div class="h-4 bg-gray-300 rounded-full w-full"></div>

    <!-- FORM -->
    <form @submit.prevent="handleSearch" class="bg-[#2f6f7e] p-4 rounded-xl shadow-md grid md:grid-cols-4 gap-4">

      <!-- 🔍 SEARCH -->
      <div class="bg-white rounded-lg px-4 py-3 flex items-center shadow-sm">
        <span class="text-gray-400 mr-2">🔍</span>
        <input
          type="text"
          v-model="search"
          placeholder="Cari dokumen..."
          class="w-full outline-none text-sm"
        />
      </div>

      <!-- 📂 KATEGORI -->
      <div class="bg-white rounded-lg px-4 py-3 flex items-center shadow-sm">
        <select v-model="kategori" class="text-sm outline-none w-full">
          <option value="">Semua Kategori</option>
          <option value="Proposal">Vital</option>
          <option value="Laporan">Aktif</option>
          <option value="Surat">Inaktif</option>
        </select>
      </div>

      <!-- 📅 TANGGAL -->
      <div class="bg-white rounded-lg px-4 py-3 flex items-center shadow-sm">
        <input
          type="date"
          v-model="tanggal"
          class="text-sm outline-none w-full"
        />
      </div>

      <!-- 🔘 BUTTON -->
      <div class="bg-white rounded-lg px-4 py-3 flex items-center justify-center shadow-sm">
        <button 
          type="submit"
          class="flex items-center gap-2 text-sm font-semibold text-gray-700"
        >
          🔍 Cari
        </button>
      </div>

    </form>

    <!-- 📊 CARD STAT -->
    <div class="grid md:grid-cols-3 gap-4">
      
      <div class="bg-[#7fa6b3] rounded-xl p-4 shadow-md flex justify-between items-center">
        <div>
          <div class="bg-white text-xs px-2 py-1 rounded w-fit mb-1">10</div>
          <p class="text-sm text-white">Dokumen terlihat</p>
        </div>
        <div class="bg-white p-2 rounded flex items-center justify-center">
          <Eye class="w-5 h-5 text-gray-700" />
        </div>
      </div>

      <div class="bg-[#7fa6b3] rounded-xl p-4 shadow-md flex justify-between items-center">
        <div>
          <div class="bg-white text-xs px-2 py-1 rounded w-fit mb-1">2</div>
          <p class="text-sm text-white">Dokumen diunduh</p>
        </div>
        <div class="bg-white p-2 rounded flex items-center justify-center">
          <Download class="w-5 h-5 text-gray-700" />
        </div>
      </div>

      <div class="bg-[#7fa6b3] rounded-xl p-4 shadow-md flex justify-between items-center">
        <div>
          <div class="bg-white text-xs px-2 py-1 rounded w-fit mb-1">3</div>
          <p class="text-sm text-white">Favorit</p>
        </div>
        <div class="bg-white p-2 rounded flex items-center justify-center">
          <Heart class="w-5 h-5 text-gray-700" />
        </div>
      </div>

    </div>

    <!-- 📄 HEADER DOKUMEN -->
    <div class="flex items-center justify-between">
      <h2 class="bg-[#2f4fa2] text-white px-4 py-1 rounded-md text-sm">
        Dokumen Terbaru
      </h2>

      <div class="flex items-center gap-2">
        <div class="h-2 bg-gray-300 w-40 rounded-full"></div>
        <span class="text-xs bg-gray-200 px-2 py-1 rounded">Lihat Semua</span>
      </div>
    </div>

    <!-- 📄 LIST DOKUMEN -->
    <div class="space-y-4">

      <div
        v-for="i in 3"
        :key="i"
        class="bg-[#7fa6b3] rounded-xl p-4 shadow-md flex items-center gap-4 border border-transparent hover:border-blue-500 transition"
      >
        <!-- icon -->
        <div class="bg-gray-200 w-12 h-12 rounded"></div>

        <!-- isi -->
        <div class="flex-1 text-xs text-gray-900">
          <p class="font-semibold text-sm mb-1">
            Proposal Program Pemberdayaan Masyarakat
          </p>

          <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
            <span>No : DOK/1012/11/2026</span>
            <span>Kategori : Proposal</span>
            <span>Divisi : Sekretariat</span>
            <span>Tanggal : 25 Januari 2026</span>
            <span>Ukuran : 3.5 MB</span>
          </div>
        </div>

        <!-- badge -->
        <div class="bg-white text-xs px-3 py-1 rounded-full shadow">
          Public
        </div>
      </div>

    </div>

  </div>

</template>