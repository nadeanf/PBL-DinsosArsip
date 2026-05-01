<script setup>

import { usePage, router } from '@inertiajs/vue3'
import { ref, computed, onMounted } from 'vue'
import { Eye, FileText, FileImage, File } from 'lucide-vue-next'
import { ChevronRight, ChevronDown } from 'lucide-vue-next'
import TreeDropdown from '@/components/TreeDropdown.vue'
import UserLayout from '@/layouts/UserLayout.vue'


defineOptions({
  layout: UserLayout
})

const page = usePage()
console.log('KATEGORI:', page.props.kategori)

const canAccessFull = (doc) => {
  const user = page.props.auth?.user

  if (!user) return false

  // publik → bebas
  if (doc.status === 'publik') return true

  // pemilik arsip
  if (doc.user_id === user.id) return true

  // private tapi bagian sama
  if (doc.status === 'private' && doc.bidang === user.bagian) return true

  return false
}

const requestAkses = (arsipId) => {
  console.log('🔥 KLIK MASUK', arsipId)

  router.post(`/request-akses/${arsipId}`, {}, {
  onSuccess: () => {
    console.log('✅ BERHASIL')

    // 🔥 langsung update UI
    selectedDoc.value.request_status = 'pending'
  },
  })
}

/* =========================
   DATA BACKEND (FIX)
========================= */
const dataArsip = computed(() => page.props?.arsip ?? [])
console.log(dataArsip.value) // 
const flattenKategori = (data, level = 0) => {
  let result = []

  data.forEach(kat => {
    result.push({
      id: kat.id,
      nama: kat.nama,
      level: level // 
    })

    if (kat.children_recursive && kat.children_recursive.length) {
      result = result.concat(
        flattenKategori(kat.children_recursive, level + 1)
      )
    }
  })

  return result
}

const totalDownload = computed(() => page.props?.totalDownload ?? 0)

/* =========================
   STATE FILTER
========================= */
const search = ref('')
const kategori = ref('')
const showDropdown = ref(false)

const selectedKategoriName = computed(() => {
  const findName = (data) => {
    for (let item of data) {
      if (item.id == kategori.value) return item.nama
      if (item.children_recursive) {
        const found = findName(item.children_recursive)
        if (found) return found
      }
    }
  }
  return findName(page.props.kategori) || ''
})
const tanggal_awal = ref('')
const tanggal_akhir = ref('')

const exportPDF = () => {
  window.location.href = `/export/pdf?search=${search.value}&kategori=${kategori.value}&tanggal_awal=${tanggal_awal.value}&tanggal_akhir=${tanggal_akhir.value}`
}

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
   MAPPING DATA
========================= */
const aktivitasTerbaru = computed(() => {
  return dataArsip.value.map(item => ({
    id: item.id,
    user_id: item.user_id,

    title: item.judul,
    nomor: item.nomor || '',
    deskripsi: item.deskripsi,

    kategori: item.kategori?.nama || '-',
    jenis: item.jenis_arsip || '-',
    bidang: item.user?.bagian || '-',

    tahun: item.tahun,
    lokasi: item.lokasi,

    status: item.status_akses,

    
    request_status: item.request_status ?? null,

    files: item.files ?? [],
    format: item.files?.length
      ? getFileType(item.files[0].path_file)
      : 'FILE',

    tanggal: item.created_at
      ? new Date(item.created_at).toLocaleDateString()
      : '-'
  }))
})

/* =========================
   LOAD DATA
========================= */
const filteredData = ref([])

onMounted(() => {
  filteredData.value = aktivitasTerbaru.value
})

/* =========================
   LIMIT DASHBOARD
========================= */
const limitedData = computed(() => {
  return filteredData.value.slice(0, 5)
})

/* =========================
   SEARCH REDIRECT
========================= */
const handleSearch = () => {
  router.get('/daftar-arsip', {
    search: search.value,
    kategori: kategori.value,
    tanggal_awal: tanggal_awal.value,
    tanggal_akhir: tanggal_akhir.value
  })
}

/* =========================
   PREVIEW MODAL
========================= */
const previewModal = ref(false)
const selectedDoc = ref(null)


const openPreview = (doc) => {
  if (!doc) return
  selectedDoc.value = doc

  console.log('FULL DOC:', doc)
  console.log('STATUS DOC:', doc.status)
  previewModal.value = true
}
</script>

<template>

<div class="p-6 bg-[#f3f4f6] min-h-screen space-y-6">

  <h1 class="text-2xl font-bold text-gray-800">Selamat Datang!</h1>

  <div class="h-4 bg-gray-300 rounded-full w-full"></div>

  <!-- SEARCH -->
  <div class="bg-[#2f6f7e] p-4 rounded-xl shadow-md flex flex-wrap gap-4 items-center">

    <div class="bg-white rounded-lg px-4 py-3 flex items-center shadow-sm flex-1">
      <span class="mr-2">🔍</span>
      <input v-model="search" placeholder="Cari dokumen..." class="w-full outline-none text-sm"/>
    </div>

    <!-- DROPDOWN FIX -->
<div class="relative w-[300px]">

  <!-- BUTTON -->
  <div 
    @click="showDropdown = !showDropdown"
    class="bg-white px-4 py-3 rounded-lg text-sm cursor-pointer flex justify-between items-center"
  >
    <span>
      {{ selectedKategoriName || 'Pilih Kategori' }}
    </span>
    <span>▼</span>
  </div>

  <!-- DROPDOWN -->
  <div 
  v-show="showDropdown"
  class="absolute left-0 top-full mt-2 w-full z-[9999] 
         bg-white border rounded-xl shadow-lg 
         max-h-[300px] overflow-y-auto"
>
<TreeDropdown
  :data="page.props.kategori"
  v-model="kategori"
/>
</div>

</div>

    <input type="date" v-model="tanggal_awal" class="bg-white px-3 py-2 rounded"/>
    <input type="date" v-model="tanggal_akhir" class="bg-white px-3 py-2 rounded"/>

    <button @click="handleSearch" class="bg-white px-4 py-2 rounded font-semibold">
      Cari
    </button>

  </div>

  <!-- CARD -->
  <div class="grid md:grid-cols-2 gap-4">

  <!-- Akumulasi Arsip -->
  <div class="bg-[#7fa6b3] rounded-xl p-4 shadow-md flex justify-between items-center">
    <div>
      <div class="bg-white text-xs px-2 py-1 rounded w-fit mb-1">
        {{ filteredData.length }}
      </div>
      <p class="text-sm text-white">Akumulasi Arsip</p>
    </div>
    <Eye class="w-5 h-5 text-gray-700" />
  </div>

  <!-- Dokumen Diunduh -->
  <div class="bg-[#7fa6b3] rounded-xl p-4 shadow-md flex justify-between items-center">
    <div>
      <div class="bg-white text-xs px-2 py-1 rounded w-fit mb-1">
        {{ totalDownload }}
      </div>
      <p class="text-sm text-white">Dokumen diunduh</p>
    </div>

    <!-- icon download -->
    <svg xmlns="http://www.w3.org/2000/svg"
         class="w-5 h-5 text-gray-700"
         fill="none"
         viewBox="0 0 24 24"
         stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4"/>
    </svg>

  </div>

</div>

  <!-- HEADER -->
  <div class="flex justify-between items-center">

  <h2 class="bg-[#2f4fa2] text-white px-4 py-1 rounded-md text-sm">
    Aktivitas Terbaru
  </h2>

  <div class="flex gap-2">

    <button 
      @click="exportPDF"
      class="text-xs bg-red-500 text-white px-3 py-1 rounded">
      Export PDF
    </button>

    <a href="/daftar-arsip" class="text-xs bg-gray-200 px-2 py-1 rounded">
      Lihat Semua
    </a>
  </div>

</div>

  <!-- LIST -->
  <div class="space-y-4">

    <div
      v-for="doc in limitedData"
      :key="doc.id"
      @click="openPreview(doc)"
      class="cursor-pointer bg-[#7fa6b3] rounded-xl p-4 shadow-md flex items-center gap-4 hover:border-blue-500 transition"
    >

      <div class="w-12 h-12 flex items-center justify-center bg-gray-100 rounded-xl">
        <FileText v-if="doc.format === 'PDF'" class="w-6 h-6 text-red-500"/>
        <FileImage v-else-if="doc.format === 'IMAGE'" class="w-6 h-6 text-blue-500"/>
        <File v-else class="w-6 h-6 text-gray-500"/>
      </div>

      <div class="flex-1 text-xs text-gray-900">
        <p class="font-semibold text-sm mb-1">{{ doc.title }}</p>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
          <span>No : {{ doc.nomor }}</span>
          <span>Kategori : {{ doc.kategori }}</span>
          <span>Bidang : {{ doc.bidang }}</span>
          <span>Tahun : {{ doc.tahun }}</span>
        </div>
      </div>

      <div class="bg-white text-xs px-3 py-1 rounded-full">
        {{ doc.status }}
      </div>

    </div>

  </div>

</div>

<!-- PREVIEW MODAL FIX -->
<div v-if="previewModal && selectedDoc"
class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-6"
@click.self="previewModal = false"
>
  <div class="bg-white w-full max-w-5xl rounded-[30px] shadow-2xl overflow-hidden flex flex-col md:flex-row">
    
    <div class="w-full md:w-1/2 bg-gray-100 flex items-center justify-center p-6">

     <!-- IMAGE -->
<img 
  v-if="selectedDoc?.format === 'IMAGE' && canAccessFull(selectedDoc)"
  :src="`/storage/${selectedDoc?.files?.[0]?.path_file}`"
  class="max-h-[400px] object-contain rounded-xl shadow" 
/>

<!-- PDF -->
<iframe 
  v-else-if="selectedDoc?.format === 'PDF' && canAccessFull(selectedDoc)"
  :src="`/storage/${selectedDoc?.files?.[0]?.path_file}`"
  class="w-full h-[400px] rounded-xl">
</iframe>

<!-- TIDAK ADA AKSES -->
<div v-else class="text-gray-500 text-center space-y-3">
  <div>
    🔒 Dokumen ini bersifat privat <br/>
    Anda tidak memiliki akses
  </div>

 <!-- SUDAH REQUEST -->
<div v-if="selectedDoc?.request_status === 'pending'"
     class="text-yellow-500 font-semibold text-sm">
  ⏳ Menunggu persetujuan
</div>

<!-- DITOLAK -->
<div v-else-if="selectedDoc?.request_status === 'ditolak'"
     class="text-red-500 font-semibold text-sm">
  ❌ Akses ditolak
</div>

<!-- BELUM REQUEST -->
<button
  v-else
  @click.stop.prevent="requestAkses(selectedDoc.id)"
  class="bg-yellow-500 text-white px-4 py-2 rounded-lg text-sm font-semibold"
>
  Minta Akses
</button>
</div>
</div>
    <div class="w-full md:w-1/2 p-8 flex flex-col justify-between">

      <div>
        <div class="flex justify-between items-start mb-4">
          <h2 class="text-2xl font-black text-gray-800">
            {{ selectedDoc?.title }}
          </h2>

          <button @click="previewModal = false">✕</button>
        </div>

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

        <div class="grid grid-cols-2 gap-4 text-sm">
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

        <div class="mt-6">
          <p class="font-bold">Deskripsi</p>
          <p>{{ selectedDoc?.deskripsi || '-' }}</p>
        </div>
      </div>

      <div class="flex justify-end gap-3 mt-6">
       <a
  v-if="selectedDoc?.files?.length && canAccessFull(selectedDoc)"
  :href="`/download/${selectedDoc?.id}`"
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