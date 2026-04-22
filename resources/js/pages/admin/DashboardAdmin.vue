<script setup>
import { Head, Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/layouts/AdminLayout.vue'
import { Search, Eye, Download, FileText } from 'lucide-vue-next'
import { ref } from 'vue'

defineOptions({
  layout: AdminLayout
})

// STATE SEARCH (FIX)
const search = ref('')
const kategori = ref('')
const tanggal_awal = ref('')
const tanggal_akhir = ref('')

const handleSearch = () => {
  router.get('/daftar-arsip', {
    search: search.value,
    kategori: kategori.value,
    tanggal_awal: tanggal_awal.value,
    tanggal_akhir: tanggal_akhir.value
  })
}

// DUMMY MENUNGGU PERSETUJUAN
const menungguPersetujuan = ref([
  {
    id: 1,
    title: "Draft Surat Edaran SPBE",
    author: "Ahmad Yani",
    tanggal: "24 Feb 2026",
    jumlah: 2
  },
  {
    id: 2,
    title: "Laporan Arsip Bulanan",
    author: "Siti Aminah",
    tanggal: "20 Feb 2026",
    jumlah: 5
  }
])

// DUMMY AKTIVITAS TERBARU
const aktivitasTerbaru = ref([
  {
    id: 0,
    title: "Dokumen Admin 1",
    nomor: "ADM/001/2026",
    kategori: "Laporan",
    divisi: "Admin",
    tanggal: "1 Januari 2026",
    ukuran: "2 MB",
    status: "Public"
  },
  {
    id: 1,
    title: "Dokumen Admin 2",
    nomor: "ADM/002/2026",
    kategori: "Surat",
    divisi: "Admin",
    tanggal: "5 Januari 2026",
    ukuran: "1.5 MB",
    status: "Internal"
  }
])
</script>

<template>
  <Head title="Dashboard Admin" />

  <div class="p-6 bg-[#f3f4f6] min-h-screen space-y-6">

    <!-- TITLE -->
    <h1 class="text-2xl font-bold text-gray-900">
      Dashboard Administrator
    </h1>

    <!-- GARIS -->
    <div class="h-4 bg-[#5f8ea0] rounded-full w-full"></div>

    <!-- SEARCH -->
    <form
      @submit.prevent="handleSearch"
      class="bg-[#2f6f7e] p-4 rounded-xl flex flex-wrap gap-3 items-center shadow-md"
    >

      <div class="flex items-center bg-white px-3 py-2 rounded-lg text-sm flex-1 min-w-[200px]">
        <Search class="text-gray-400 w-4 mr-2" />
        <input
          v-model="search"
          type="text"
          placeholder="Cari dokumen, nomor surat,..."
          class="outline-none w-full text-gray-900"
        />
      </div>

      <select v-model="kategori" class="bg-white px-3 py-2 rounded-lg text-sm w-[180px] text-gray-900">
        <option value="">Semua Kategori</option>
        <option value="Vital">Vital</option>
        <option value="Aktif">Aktif</option>
        <option value="Inaktif">Inaktif</option>
      </select>

      <input type="date" v-model="tanggal_awal" class="bg-white px-3 py-2 rounded-lg text-sm w-[150px]" />
      <input type="date" v-model="tanggal_akhir" class="bg-white px-3 py-2 rounded-lg text-sm w-[150px]" />

      <button
        type="submit"
        class="bg-white px-5 py-2 rounded-lg text-sm font-semibold hover:bg-gray-100"
      >
        Cari
      </button>

    </form>

    <!-- STATS -->
    <div class="grid md:grid-cols-3 gap-4">

      <div class="bg-[#6f98a8] p-4 rounded-xl flex justify-between items-center shadow-sm">
        <div>
          <p class="text-white text-sm">Dokumen terlihat</p>
          <h2 class="text-xl font-bold text-white">12</h2>
        </div>
        <div class="bg-white p-3 rounded-lg">
          <Eye class="w-6 h-6 text-[#6f98a8]" />
        </div>
      </div>

      <div class="bg-[#6f98a8] p-4 rounded-xl flex justify-between items-center shadow-sm">
        <div>
          <p class="text-white text-sm">Dokumen diunduh</p>
          <h2 class="text-xl font-bold text-white">3</h2>
        </div>
        <div class="bg-white p-3 rounded-lg">
          <Download class="w-6 h-6 text-[#6f98a8]" />
        </div>
      </div>

      <div class="bg-[#6f98a8] p-4 rounded-xl flex justify-between items-center shadow-sm">
        <div>
          <p class="text-white text-sm">Favorit</p>
          <h2 class="text-xl font-bold text-white">15</h2>
        </div>
        <div class="bg-white p-3 rounded-lg">
          <FileText class="w-6 h-6 text-[#6f98a8]" />
        </div>
      </div>

    </div>

    <!-- GRID -->
    <div class="grid md:grid-cols-2 gap-6">

      <!-- TIPE DOKUMEN -->
      <div>
        <h2 class="bg-[#2f4fa2] text-white px-4 py-1 rounded-md w-fit text-sm mb-3">
          Tipe Dokumen
        </h2>

        <div class="bg-[#6f98a8] p-4 rounded-xl space-y-2 shadow-sm">
          <div class="flex justify-between bg-white px-3 py-2 rounded text-sm">
            <span>Dokumen</span>
            <span>9 Jumlah</span>
          </div>

          <div class="flex justify-between bg-white px-3 py-2 rounded text-sm">
            <span>Foto / Gambar</span>
            <span>10 Jumlah</span>
          </div>

          <div class="flex justify-between bg-white px-3 py-2 rounded text-sm">
            <span>Video</span>
            <span>6 Jumlah</span>
          </div>

          <div class="flex justify-between bg-white px-3 py-2 rounded text-sm">
            <span>Audio</span>
            <span>8 Jumlah</span>
          </div>
        </div>
      </div>

      <!-- MENUNGGU PERSETUJUAN -->
      <div>
        <div class="flex justify-between items-center mb-3">
          <h2 class="bg-[#2f4fa2] text-white px-4 py-1 rounded-md text-sm flex items-center gap-2">
            Menunggu Persetujuan
            <span class="bg-white text-[#2f4fa2] text-xs px-2 py-0.5 rounded-full font-bold">
              {{ menungguPersetujuan.length }}
            </span>
          </h2>

          <Link href="/admin/persetujuan"
            class="bg-red-700 text-white px-3 py-1 rounded text-xs">
            Lihat Semua
          </Link>
        </div>

        <div class="space-y-3">
          <div
            v-for="item in menungguPersetujuan"
            :key="item.id"
            class="bg-[#6f98a8] p-3 rounded-xl flex justify-between items-center shadow"
          >
            <div>
              <p class="font-semibold text-white text-sm">{{ item.title }}</p>
              <p class="text-xs text-white/80">
                Oleh: {{ item.author }} • {{ item.tanggal }}
              </p>
            </div>

            <div class="w-8 h-8 bg-white rounded flex items-center justify-center text-xs font-bold text-[#2f4fa2]">
              {{ item.jumlah }}
            </div>
          </div>
        </div>
      </div>

    </div>

    <!-- AKTIVITAS TERBARU -->
    <div>

      <div class="flex justify-between items-center mb-3">
        <h2 class="bg-[#2f4fa2] text-white px-4 py-1 rounded-md text-sm">
          Aktivitas Terbaru
        </h2>

        <Link href="/daftar-arsip"
          class="bg-red-700 text-white px-4 py-1 rounded text-xs hover:bg-red-800">
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

  </div>
</template>