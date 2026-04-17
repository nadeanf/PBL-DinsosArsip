<script setup>
import AuthLayoutPimpinan from '@/layouts/AuthLayoutPimpinan.vue'
import { ref } from 'vue'
import { router, Link } from '@inertiajs/vue3'
import { Eye, Download, FileText } from 'lucide-vue-next'

defineOptions({
  layout: AuthLayoutPimpinan
})

// --- STATE SEARCH ---
const search = ref('')
const kategori = ref('')
const tanggal_awal = ref('')
const tanggal_akhir = ref('')

// --- DATA DUMMY (Aktivitas Terbaru) ---
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
  router.get('/daftar-arsip', {
    search: search.value,
    kategori: kategori.value,
    tanggal_awal: tanggal_awal.value,
    tanggal_akhir: tanggal_akhir.value
  })
}
</script>

<template>
  <div class="p-6 bg-[#f3f4f6] min-h-screen space-y-6">

    <h1 class="text-2xl font-bold text-gray-900">
      Dashboard Pimpinan Dinas
    </h1>

    <div class="h-4 bg-[#5f8ea0] rounded-full w-full"></div>

    <form @submit.prevent="handleSearch"
      class="bg-[#2f6f7e] p-4 rounded-xl flex items-center gap-3 w-full shadow-md">

      <div class="flex items-center bg-white px-3 py-2 rounded-lg text-sm flex-1">
        <span class="text-gray-400 mr-2">🔍</span>
        <input v-model="search" placeholder="Cari dokumen, nomor surat,..." class="outline-none w-full" />
      </div>

      <select v-model="kategori" class="bg-white px-3 py-2 rounded-lg text-sm w-[180px]">
        <option value="">Semua Kategori</option>
        <option value="Proposal">Vital</option>
        <option value="Laporan">Aktif</option>
        <option value="Surat">Inaktif</option>
      </select>

      <input type="date" v-model="tanggal_awal" class="bg-white px-3 py-2 rounded-lg text-sm w-[150px]" />
      <input type="date" v-model="tanggal_akhir" class="bg-white px-3 py-2 rounded-lg text-sm w-[150px]" />

      <button type="submit" class="bg-white px-5 py-2 rounded-lg text-sm font-semibold hover:bg-gray-100 transition">
        Cari
      </button>
    </form>

    <div class="grid md:grid-cols-2 gap-4">
      <div class="bg-[#6f98a8] p-4 rounded-xl flex justify-between items-center shadow-sm">
        <div>
          <div class="bg-white text-[#6f98a8] text-xs px-2 py-1 rounded w-fit mb-1 font-bold">125</div>
          <p class="text-white text-sm">Dokumen terlihat</p>
          <div class="h-2 bg-gray-300/30 rounded mt-2 w-40"></div>
        </div>
        <div class="bg-white p-3 rounded-lg shadow-inner">
          <Eye class="w-6 h-6 text-[#6f98a8]" />
        </div>
      </div>

      <div class="bg-[#6f98a8] p-4 rounded-xl flex justify-between items-center shadow-sm">
        <div>
          <div class="bg-white text-[#6f98a8] text-xs px-2 py-1 rounded w-fit mb-1 font-bold">42</div>
          <p class="text-white text-sm">Dokumen diunduh</p>
          <div class="h-2 bg-gray-300/30 rounded mt-2 w-40"></div>
        </div>
        <div class="bg-white p-3 rounded-lg shadow-inner">
          <Download class="w-6 h-6 text-[#6f98a8]" />
        </div>
      </div>
    </div>

    <div>
      <h2 class="bg-[#2f4fa2] text-white px-4 py-1 rounded-md w-fit text-sm mb-3">
        Tipe Dokumen
      </h2>
      <div class="bg-[#6f98a8] p-4 rounded-xl w-[300px] space-y-2 shadow-sm">
        <div class="flex justify-between bg-white px-3 py-1.5 rounded text-xs font-medium">
          <span>Dokumen</span><span class="text-[#2f4fa2]">85 Item</span>
        </div>
        <div class="flex justify-between bg-white px-3 py-1.5 rounded text-xs font-medium">
          <span>Foto / Gambar</span><span class="text-[#2f4fa2]">12 Item</span>
        </div>
        <div class="flex justify-between bg-white px-3 py-1.5 rounded text-xs font-medium">
          <span>Video</span><span class="text-[#2f4fa2]">5 Item</span>
        </div>
        <div class="flex justify-between bg-white px-3 py-1.5 rounded text-xs font-medium">
          <span>Audio</span><span class="text-[#2f4fa2]">2 Item</span>
        </div>
      </div>
    </div>

    <div class="flex items-center justify-between">
      <h2 class="bg-[#2f4fa2] text-white px-4 py-1 rounded-md text-sm">
        Aktivitas Terbaru
      </h2>
      <Link href="/daftar-arsip" class="bg-red-700 text-white px-4 py-1 rounded text-xs hover:bg-red-800 transition">
        Lihat Semua
      </Link>
    </div>

    <div class="space-y-4">
      <Link
        v-for="doc in aktivitasTerbaru"
        :key="doc.id"
        :href="`/arsip/${doc.id}`"
        class="bg-[#6f98a8] rounded-xl p-4 shadow-md flex items-center gap-4 border border-transparent hover:border-white transition cursor-pointer block group"
      >
        <div class="bg-gray-200 w-12 h-12 rounded flex items-center justify-center">
            <FileText class="w-6 h-6 text-gray-500" />
        </div>

        <div class="flex-1 text-xs text-white">
          <p class="font-bold text-sm mb-1 group-hover:underline">
            {{ doc.title }}
          </p>

          <div class="grid grid-cols-2 md:grid-cols-4 gap-2 opacity-90">
            <span>No : {{ doc.nomor }}</span>
            <span>Kategori : {{ doc.kategori }}</span>
            <span>Divisi : {{ doc.divisi }}</span>
            <span>Tanggal : {{ doc.tanggal }}</span>
            <span>Ukuran : {{ doc.ukuran }}</span>
          </div>
        </div>

        <div class="bg-white text-[#6f98a8] text-[10px] font-bold px-3 py-1 rounded-full shadow">
          {{ doc.status }}
        </div>
      </Link>
    </div>

  </div>
</template>