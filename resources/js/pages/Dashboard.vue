<script setup>
import { usePage } from '@inertiajs/vue3'
import { ref, computed } from 'vue'
import { Eye, Download, Heart, FileText, FileImage, File } from 'lucide-vue-next'
import AuthLayout from '@/layouts/AuthLayout.vue'

defineOptions({
  layout: AuthLayout
})

const page = usePage()

/* =========================
   DATA DARI BACKEND (REAL)
========================= */
const dataArsip = page.props.arsip || []
const kategoriData = page.props.kategori || []

/* =========================
   STATE FILTER
========================= */
const search = ref('')
const kategori = ref('')
const tanggal_awal = ref('')
const tanggal_akhir = ref('')

/* =========================
   HELPER FILE TYPE
========================= */
const getFileType = (path) => {
  if (!path) return 'FILE'
  const ext = path.split('.').pop()?.toLowerCase()

  if (['jpg','jpeg','png','gif','webp'].includes(ext)) return 'IMAGE'
  if (ext === 'pdf') return 'PDF'
  return 'FILE'
}

/* =========================
   MAPPING DATA (REAL → CLEAN)
========================= */
const allDocuments = computed(() => {
  return dataArsip.map(item => ({
    id: item.id,
    title: item.judul,
    nomor: item.nomor || '',
    deskripsi: item.deskripsi,

    kategori_id: item.id_kategori,
    kategori: item.kategori?.nama || '-',
    jenis: item.jenis_arsip || '-',

    // 🔥 TAMBAHAN (BIDANG)
    bidang: item.user?.bagian || '-',

    tahun: item.tahun,
    lokasi: item.lokasi,
    status: item.status_akses,

    files: item.files || [],
    format: item.files?.length
      ? getFileType(item.files[0].path_file)
      : 'FILE',

    tanggal: item.created_at
      ? new Date(item.created_at).toLocaleDateString()
      : '-'
  }))
})

/* =========================
   FILTER
========================= */
const filteredDocuments = computed(() => {
  return allDocuments.value.filter(item => {

    const matchSearch =
      item.title.toLowerCase().includes(search.value.toLowerCase()) ||
      item.nomor.toLowerCase().includes(search.value.toLowerCase())

    const matchKategori = kategori.value
      ? item.kategori_id == kategori.value
      : true

    const matchTanggal =
      (!tanggal_awal.value || item.tahun >= new Date(tanggal_awal.value).getFullYear()) &&
      (!tanggal_akhir.value || item.tahun <= new Date(tanggal_akhir.value).getFullYear())

    return matchSearch && matchKategori && matchTanggal
  })
})

/* =========================
   PREVIEW
========================= */
const previewModal = ref(false)
const selectedDoc = ref(null)

const openPreview = (item) => {
  selectedDoc.value = item
  previewModal.value = true
}
</script>

<template>

  <div class="p-6 bg-[#f3f4f6] min-h-screen space-y-6">

    <h1 class="text-2xl font-bold text-gray-800">Selamat Datang!</h1>

    <div class="h-4 bg-gray-300 rounded-full w-full"></div>

    <!-- FORM -->
    <form @submit.prevent class="bg-[#2f6f7e] p-4 rounded-xl shadow-md flex flex-wrap gap-3 items-center relative z-30">

  <div class="bg-white rounded-lg px-4 py-2 flex items-center shadow-sm flex-1 min-w-[200px] h-11">
    <span class="text-gray-400 mr-2 text-lg">🔍</span>
    <input v-model="search" type="text" placeholder="Cari dokumen..." class="w-full outline-none text-sm bg-transparent text-gray-800"/>
  </div>

  <div class="bg-white rounded-lg px-3 py-2 flex items-center shadow-sm w-[220px] h-11 relative">
    <select 
      v-model="kategori" 
      class="text-sm outline-none w-full bg-white text-gray-900 cursor-pointer appearance-none pr-8 relative z-10"
    >
      <option value="" class="bg-white text-gray-900">Semua Kategori</option>
      <option 
        v-for="kat in kategoriData" 
        :key="kat.id" 
        :value="kat.id" 
        class="bg-white text-gray-900"
      >
        {{ kat.nama }}
      </option>
    </select>
    <span class="text-gray-500 absolute right-3 pointer-events-none z-0">▼</span>
  </div>

  <div class="bg-white rounded-lg px-3 py-2 w-[175px] h-11 flex items-center shadow-sm">
    <input type="date" v-model="tanggal_awal" class="w-full text-sm outline-none bg-white text-gray-900 uppercase"/>
  </div>

  <div class="text-white font-bold px-1 text-lg">-</div>

  <div class="bg-white rounded-lg px-3 py-2 w-[175px] h-11 flex items-center shadow-sm">
    <input type="date" v-model="tanggal_akhir" class="w-full text-sm outline-none bg-white text-gray-900 uppercase"/>
  </div>

  <button type="submit" class="bg-white hover:bg-gray-100 text-[#2f6f7e] rounded-lg px-6 h-11 text-sm font-bold shadow-sm transition-all active:scale-95">
    Cari
  </button>

</form>

    <!-- STAT -->
    <div class="grid md:grid-cols-3 gap-4">

      <div class="bg-[#7fa6b3] rounded-xl p-4 shadow-md flex justify-between items-center">
        <div>
          <div class="bg-white text-xs px-2 py-1 rounded w-fit mb-1">
            {{ filteredDocuments.length }}
          </div>
          <p class="text-sm text-white">Dokumen terlihat</p>
        </div>
        <div class="bg-white p-2 rounded">
          <Eye class="w-5 h-5 text-gray-700" />
        </div>
      </div>

      <div class="bg-[#7fa6b3] rounded-xl p-4 shadow-md flex justify-between items-center">
        <div>
          <div class="bg-white text-xs px-2 py-1 rounded w-fit mb-1">-</div>
          <p class="text-sm text-white">Dokumen diunduh</p>
        </div>
        <div class="bg-white p-2 rounded">
          <Download class="w-5 h-5 text-gray-700" />
        </div>
      </div>

      <div class="bg-[#7fa6b3] rounded-xl p-4 shadow-md flex justify-between items-center">
        <div>
          <div class="bg-white text-xs px-2 py-1 rounded w-fit mb-1">-</div>
          <p class="text-sm text-white">Favorit</p>
        </div>
        <div class="bg-white p-2 rounded">
          <Heart class="w-5 h-5 text-gray-700" />
        </div>
      </div>

    </div>

    <!-- LIST -->
    <div class="space-y-4">

      <div
        v-for="item in filteredDocuments"
        :key="item.id"
        @click="openPreview(item)"
        class="cursor-pointer bg-[#7fa6b3] rounded-xl p-4 shadow-md flex items-center gap-4 border border-transparent hover:border-blue-500 transition"
      >

        <!-- ICON -->
        <div class="w-12 h-12 flex items-center justify-center rounded-xl bg-gray-100">
          <FileText v-if="item.format === 'PDF'" class="w-6 h-6 text-red-500"/>
          <FileImage v-else-if="item.format === 'IMAGE'" class="w-6 h-6 text-blue-500"/>
          <File v-else class="w-6 h-6 text-gray-500"/>
        </div>

        <!-- DATA -->
        <div class="flex-1 text-xs text-gray-900">
          <p class="font-semibold text-sm mb-1">{{ item.title }}</p>

          <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
  <span>No : {{ item.nomor }}</span>
  <span>Kategori : {{ item.kategori }}</span>
  <span>Bidang : {{ item.bidang }}</span>
  <span>Tanggal : {{ item.tahun }}</span>
</div>
        </div>

        <div class="bg-white text-xs px-3 py-1 rounded-full">
          {{ item.status }}
        </div>

      </div>

      <div v-if="!filteredDocuments.length" class="text-center text-gray-500">
        Tidak ada dokumen
      </div>

    </div>

  </div>

<div v-if="previewModal"
  class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-6">

  <div class="bg-white w-full max-w-5xl rounded-[30px] shadow-2xl overflow-hidden flex flex-col md:flex-row">

    <!-- LEFT (PREVIEW FILE) -->
    <div class="w-full md:w-1/2 bg-gray-100 flex items-center justify-center p-6">

      <!-- IMAGE -->
      <img v-if="selectedDoc.format === 'IMAGE'"
        :src="`/storage/${selectedDoc.files[0].path_file}`"
        class="max-h-[400px] object-contain rounded-xl shadow" />

      <!-- PDF -->
      <iframe v-else-if="selectedDoc.format === 'PDF'"
        :src="`/storage/${selectedDoc.files[0].path_file}`"
        class="w-full h-[400px] rounded-xl"></iframe>

      <!-- OTHER -->
      <div v-else class="text-gray-500 text-center">
        📄<br/>Preview tidak tersedia
      </div>

    </div>

    <!-- RIGHT (DETAIL) -->
    <div class="w-full md:w-1/2 p-8 flex flex-col justify-between">

      <div>
        <div class="flex justify-between items-start mb-4">
          <h2 class="text-2xl font-black text-gray-800">
            {{ selectedDoc?.title }}
          </h2>

          <button @click="previewModal = false">✕</button>
        </div>

        <!-- BADGE -->
        <div class="flex flex-wrap gap-2 mb-4">
          <span class="bg-gray-200 px-3 py-1 rounded-full text-xs font-bold">
            No: {{ selectedDoc?.nomor }}
          </span>

          <span class="bg-blue-100 px-3 py-1 rounded-full text-xs font-bold">
            {{ selectedDoc?.kategori }}
          </span>

          <span class="bg-green-100 px-3 py-1 rounded-full text-xs font-bold uppercase">
            {{ selectedDoc?.jenis }}
          </span>
        </div>

        <!-- DETAIL -->
        <div class="grid grid-cols-2 gap-4 text-sm">
          <div>
            <p class="font-bold">Bidang</p>
            <p>{{ selectedDoc?.bidang }}</p>
          </div>

          <div>
            <p class="font-bold">Tahun</p>
            <p>{{ selectedDoc?.tahun }}</p>
          </div>

          <div>
            <p class="font-bold">Status</p>
            <p>{{ selectedDoc?.status }}</p>
          </div>

          <div class="col-span-2">
            <p class="font-bold">Lokasi</p>
            <p>{{ selectedDoc?.lokasi }}</p>
          </div>
        </div>

        <!-- DESKRIPSI -->
        <div class="mt-6">
          <p class="font-bold">Deskripsi</p>
          <p>{{ selectedDoc?.deskripsi || '-' }}</p>
        </div>
      </div>

      <!-- ACTION -->
      <div class="flex justify-end gap-3 mt-6">
        <a
          v-if="selectedDoc?.files?.length"
          :href="`/storage/${selectedDoc.files[0].path_file}`"
          download
          class="bg-blue-600 text-white px-4 py-2 rounded-xl font-bold"
        >
          Download
        </a>

        <button
          @click="previewModal = false"
          class="bg-gray-300 px-4 py-2 rounded-xl font-bold"
        >
          Tutup
        </button>
      </div>

    </div>

  </div>
</div>
</template>