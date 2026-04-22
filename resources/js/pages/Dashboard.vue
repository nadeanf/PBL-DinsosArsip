<script setup>
import { router, Link } from '@inertiajs/vue3'
import { arsip } from '@/routes'
import { ref } from 'vue'
import { Eye, Download, Heart, FileText } from 'lucide-vue-next'
import UserLayout from '@/layouts/UserLayout.vue'

defineOptions({
  layout: UserLayout
})

// state input
const search = ref('')
const kategori = ref('')
const tanggal_awal = ref('')
const tanggal_akhir = ref('')

// dummy aktivitas terbaru
const aktivitasTerbaru = ref([
  {
    id: 0,
    title: "Proposal Program Pemberdayaan Masyarakat",
    nomor: "DOK/1012/11/2026",
    kategori: "Proposal",
    divisi: "Sekretariat",
    tanggal: "25 Januari 2026",
    ukuran: "3.5 MB",
    status: "Public"
  },
  {
    id: 1,
    title: "Laporan Keuangan Tahunan Dinas",
    nomor: "KEU/088/01/2026",
    kategori: "Laporan",
    divisi: "Keuangan",
    tanggal: "20 Januari 2026",
    ukuran: "5.2 MB",
    status: "Internal"
  },
  {
    id: 2,
    title: "Surat Keputusan Kadis No. 5",
    nomor: "SK/005/XII/2025",
    kategori: "Surat",
    divisi: "Hukum",
    tanggal: "15 Desember 2025",
    ukuran: "1.2 MB",
    status: "Public"
  }
])

const handleSearch = () => {
  router.get(arsip().url, {
    search: search.value,
    kategori: kategori.value,
    tanggal_awal: tanggal_awal.value,
    tanggal_akhir: tanggal_akhir.value
  })
}
</script>

<template>

  <div class="p-6 bg-[#f3f4f6] min-h-screen space-y-6">


    <h1 class="text-2xl font-bold text-gray-800">Selamat Datang!</h1>

    
    <div class="h-4 bg-gray-300 rounded-full w-full"></div>

    <form @submit.prevent="handleSearch"
      class="bg-[#2f6f7e] p-4 rounded-xl shadow-md flex flex-wrap gap-4 items-center">

      <div class="bg-white rounded-lg px-4 py-3 flex items-center shadow-sm flex-1 min-w-[200px]">
        <span class="text-gray-400 mr-2">🔍</span>
        <input v-model="search" placeholder="Cari dokumen..." class="w-full outline-none text-sm" />
      </div>

      <div class="bg-white rounded-lg px-4 py-3 flex items-center shadow-sm w-[180px]">
        <select v-model="kategori" class="text-sm outline-none w-full">
          <option value="">Semua Kategori</option>
          <option value="Proposal">Vital</option>
          <option value="Laporan">Aktif</option>
          <option value="Surat">Inaktif</option>
        </select>
      </div>

      <div class="bg-white rounded-lg px-4 py-3 flex items-center shadow-sm w-[160px]">
        <input type="date" v-model="tanggal_awal" class="text-sm outline-none w-full" />
      </div>

      <div class="text-white font-bold px-1">-</div>

      <div class="bg-white rounded-lg px-4 py-3 flex items-center shadow-sm w-[160px]">
        <input type="date" v-model="tanggal_akhir" class="text-sm outline-none w-full" />
      </div>

      <button type="submit"
        class="bg-white rounded-lg px-4 py-2 shadow-sm text-sm font-semibold text-gray-700 hover:bg-gray-100 transition">
        Cari
      </button>

    </form>

    <!-- CARD STAT (TETAP) -->
    <div class="grid md:grid-cols-3 gap-4">

      <div class="bg-[#7fa6b3] rounded-xl p-4 shadow-md flex justify-between items-center">
        <div>
          <div class="bg-white text-xs px-2 py-1 rounded w-fit mb-1">10</div>
          <p class="text-sm text-white">Dokumen terlihat</p>
        </div>
        <div class="bg-white p-2 rounded">
          <Eye class="w-5 h-5 text-gray-700" />
        </div>
      </div>

      <div class="bg-[#7fa6b3] rounded-xl p-4 shadow-md flex justify-between items-center">
        <div>
          <div class="bg-white text-xs px-2 py-1 rounded w-fit mb-1">2</div>
          <p class="text-sm text-white">Dokumen diunduh</p>
        </div>
        <div class="bg-white p-2 rounded">
          <Download class="w-5 h-5 text-gray-700" />
        </div>
      </div>

      <div class="bg-[#7fa6b3] rounded-xl p-4 shadow-md flex justify-between items-center">
        <div>
          <div class="bg-white text-xs px-2 py-1 rounded w-fit mb-1">3</div>
          <p class="text-sm text-white">Favorit</p>
        </div>
        <div class="bg-white p-2 rounded">
          <Heart class="w-5 h-5 text-gray-700" />
        </div>
      </div>

    </div>

    <!-- HEADER -->
    <div class="flex items-center justify-between">
      <h2 class="bg-[#2f4fa2] text-white px-4 py-1 rounded-md text-sm">
        Aktivitas Terbaru
      </h2>

      <Link
        :href="arsip().url"
        class="text-xs bg-gray-200 px-2 py-1 rounded hover:bg-gray-300 transition"
      >
        Lihat Semua
      </Link>
    </div>

    <!-- LIST -->
    <div class="space-y-4">

      <Link
        v-for="doc in aktivitasTerbaru"
        :key="doc.id"
        :href="`/arsip/${doc.id}`"
        class="bg-[#7fa6b3] rounded-xl p-4 shadow-md flex items-center gap-4 border hover:border-blue-500 transition block"
      >

        <div class="bg-gray-200 w-12 h-12 rounded flex items-center justify-center">
          <FileText class="w-6 h-6 text-gray-500" />
        </div>

        <div class="flex-1 text-xs text-gray-900">
          <p class="font-semibold text-sm mb-1">
            {{ doc.title }}
          </p>

          <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
            <span>No : {{ doc.nomor }}</span>
            <span>Kategori : {{ doc.kategori }}</span>
            <span>Divisi : {{ doc.divisi }}</span>
            <span>Tanggal : {{ doc.tanggal }}</span>
            <span>Ukuran : {{ doc.ukuran }}</span>
          </div>
        </div>

        <div class="bg-white text-xs px-3 py-1 rounded-full shadow">
          {{ doc.status }}
        </div>

      </Link>

    </div>

  </div>

</template>