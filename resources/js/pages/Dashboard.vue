<script setup lang+="ts">

import { usePage, router } from '@inertiajs/vue3'
import { ref, computed, onMounted, watch } from 'vue'
import { Eye, FileText, FileImage, File } from 'lucide-vue-next'
import { ChevronRight, ChevronDown } from 'lucide-vue-next'
import TreeDropdown from '@/components/TreeDropdown.vue'
import UserLayout from '@/layouts/UserLayout.vue'


defineOptions({
  layout: UserLayout
})

const page = usePage()

console.log('USER LOGIN:', page.props.auth?.user)
const canAccessFull = (doc) => {
  const user = page.props.auth?.user

  if (!doc || !user) return false

  const normalize = (val) =>
    String(val || '').toLowerCase().trim()

  // 🔥 ADMIN / SUPERADMIN
  if (['admin', 'superadmin'].includes(user.role)) {
    return true
  }

  // PUBLIC
  if (normalize(doc.status_akses) === 'publik') {
    return true
  }

  // PEMILIK
  if (doc.user_id === user.id) {
    return true
  }

  // SUDAH APPROVED
  if (doc.request_status === 'approved') {
    return true
  }

  // BIDANG SAMA
  const bidangDoc =
    doc.bidang ||
    doc.bagian ||
    doc.user?.bagian ||
    ''

 const userBagian = normalize(user.bagian)
const docBidang = normalize(bidangDoc)

if (
  normalize(doc.status_akses) === 'private' &&
  (
    userBagian.includes(docBidang) ||
    docBidang.includes(userBagian)
  )
) {
  return true
}

  return false
}
const requestAkses = (arsipId) => {
  console.log('KLIK MASUK', arsipId)

  router.post(`/request-akses/${arsipId}`, {}, {
  onSuccess: () => {
    console.log('BERHASIL')

    // update UI
    selectedDoc.value.request_status = 'pending'
  },
  })
}

/* DATA BACKEND (FIX) */
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

const totalDownload = ref(page.props?.totalDownload ?? 0)

/* STATE FILTER */
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

const getTodayDate = () => {
  return new Date().toISOString().slice(0, 10)
}

watch(tanggal_awal, (val) => {
  if (val && !tanggal_akhir.value) tanggal_akhir.value = getTodayDate()
}, { immediate: true })

const exportPDF = () => {
  const akhir = tanggal_awal.value && !tanggal_akhir.value
    ? getTodayDate()
    : tanggal_akhir.value

  window.location.href = `/export/pdf?search=${search.value}&kategori=${kategori.value}&tanggal_awal=${tanggal_awal.value}&tanggal_akhir=${akhir}`
}

/* HELPER FILE TYPE */
const getFileType = (path) => {
  if (!path) return 'FILE'
  const ext = path.split('.').pop()?.toLowerCase()

  if (['jpg','jpeg','png','gif','webp'].includes(ext)) return 'IMAGE'
  if (ext === 'pdf') return 'PDF'
  return 'FILE'
}

/* MAPPING DATA */
const aktivitasTerbaru = computed(() => {
  return dataArsip.value.map(item => ({
    id: item.id,
    user_id: item.user_id,

    title: item.judul,
    nomor: item.nomor || '',
    deskripsi: item.deskripsi,

    kategori: item.kategori?.nama || '-',
    jenis: item.jenis_arsip || '-',
    bagian: item.bagian || item.user?.bagian || '-',
    bidang: item.bagian || item.user?.bagian || '-',

    tahun: item.tahun,
    lokasi: item.lokasi,

    status: item.status_akses,
    status_akses: item.status_akses,

    
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

/* LOAD DATA */
const filteredData = ref([])

onMounted(() => {
  filteredData.value = aktivitasTerbaru.value
})

/* LIMIT DASHBOARD */
const limitedData = computed(() => {
  return filteredData.value.slice(0, 3)
})

/* SEARCH REDIRECT */
const handleSearch = () => {
  const params = {
    search: search.value,
    kategori: kategori.value,
    tanggal_awal: tanggal_awal.value
  }

  if (tanggal_akhir.value) {
    params.tanggal_akhir = tanggal_akhir.value
  }

  // mark this visit as coming from dashboard so list view can decide
  params.from_dashboard = 1

  router.get('/daftar-arsip', params)
}

/* PREVIEW MODAL */
const previewModal = ref(false)
const selectedDoc = ref(null)
const openPreview = (doc) => {
  if (!doc) return
 console.log('DOC:', doc)
  console.log('STATUS:', doc.status_akses)
  console.log('BIDANG DOC:', doc.bidang)
  console.log('BAGIAN USER:', page.props.auth?.user?.bagian)
  console.log('HASIL AKSES:', canAccessFull(doc))

  selectedDoc.value = doc
  previewModal.value = true

  // track riwayat akses (silent - tidak perlu error dialog)
  const trackView = async () => {
    try {
      const response = await fetch('/riwayat/view', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: JSON.stringify({ dokumen_id: doc.id })
      })
      if (!response.ok) throw new Error('Tracking failed')
    } catch (err) {
      // Silent - jangan tampilkan error
      console.debug('View tracking completed', err)
    }
  }
  trackView()
}

const handleDownload = (id) => {

  // bikin form manual (bypass Inertia)
  const form = document.createElement('form')
  form.method = 'GET'
  form.action = `/download/${id}`

  document.body.appendChild(form)
  form.submit()
  document.body.removeChild(form)

  // update count manual
  totalDownload.value++

  // track riwayat download
  const trackDownload = async () => {
    try {
      const response = await fetch('/riwayat/view', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: JSON.stringify({ dokumen_id: id })
      })
      if (!response.ok) throw new Error('Tracking failed')
    } catch (err) {
      // Silent - jangan tampilkan error
      console.debug('Download tracking completed', err)
    }
  }
  trackDownload()
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
    class="bg-white text-black px-4 py-3 rounded-lg text-sm cursor-pointer flex justify-between items-center"
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
      <div class="bg-white text-black text-xs px-2 py-1 rounded w-fit mb-1">
        {{ filteredData.length }}
      </div>
      <p class="text-sm text-white">Akumulasi Arsip</p>
    </div>
    <Eye class="w-5 h-5 text-gray-700" />
  </div>

  <!-- Dokumen Diunduh -->
  <div class="bg-[#7fa6b3] rounded-xl p-4 shadow-md flex justify-between items-center">
    <div>
      <div class="bg-white text-black text-xs px-2 py-1 rounded w-fit mb-1">
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

    <a href="/daftar-arsip" class="text-xs bg-slate-800 text-slate-100 px-2 py-1 rounded-md font-medium">
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
          <span>Bidang : {{ doc.bagian }}</span>
          <span>Tahun : {{ doc.tahun }}</span>
        </div>
      </div>

      <div class="bg-white text-xs px-3 py-1 rounded-full">
        {{ doc.status }}
      </div>

    </div>

  </div>

</div>

<!-- PREVIEW MODAL -->
<div
  v-if="previewModal && selectedDoc"
  @click.self="previewModal = false; selectedDoc = null"
  class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-6"
>
  <div class="bg-white w-full max-w-5xl rounded-[30px] shadow-2xl overflow-hidden flex flex-col md:flex-row">

    <!-- LEFT -->
    <div class="w-full md:w-1/2 bg-gray-100 flex items-center justify-center p-6">

      <!-- ✅ ADA AKSES -->
      <template v-if="canAccessFull(selectedDoc)">
        
        <!-- IMAGE -->
        <img
          v-if="selectedDoc.format === 'IMAGE'"
          :src="`/storage/${selectedDoc.files?.[0]?.path_file}`"
          class="max-h-[400px] object-contain rounded-xl shadow"
        />

        <!-- PDF -->
        <iframe
          v-else-if="selectedDoc.format === 'PDF'"
          :src="`/storage/${selectedDoc.files?.[0]?.path_file}`"
          class="w-full h-[400px] rounded-xl"
        />

        <!-- OTHER -->
        <div v-else class="text-gray-500 text-center">
          📄<br/>Preview tidak tersedia
        </div>

      </template>

      <!-- ❌ TIDAK ADA AKSES -->
      <div v-else class="text-center text-gray-500 space-y-3">

        <div class="text-6xl">🔒</div>

        <p class="text-lg font-semibold">Akses Terbatas</p>

        <p class="text-sm">
          Arsip private di bidang lain. Minta akses untuk melihat isi dokumen.
        </p>
      </div>

    </div>

    <!-- RIGHT -->
    <div class="w-full md:w-1/2 p-8 flex flex-col justify-between text-black">

      <div>
        <div class="flex justify-between items-start mb-4">
          <h2 class="text-2xl font-black text-gray-800">
            {{ selectedDoc.title }}
          </h2>

          <button @click="closePreviewModal" class="text-gray-400 hover:text-gray-600">✕</button>
        </div>

        <div class="flex flex-wrap gap-2 mb-4">
          <span class="bg-black/70 px-3 py-0.5 rounded-full text-[10px] font-bold">
            No: {{ selectedDoc.nomor }}
          </span>

          <span class="bg-blue-500/70 px-3 py-0.5 rounded-full text-[10px] font-bold">
            {{ selectedDoc.kategori }}
          </span>

          <span class="bg-green-500/70 px-3 py-0.5 rounded-full text-[10px] font-bold uppercase">
            {{ selectedDoc.jenis }}
          </span>
        </div>

        <div class="grid grid-cols-2 gap-4 text-sm">
          <div>
            <p class="text-black font-bold">Tahun</p>
            <p class="text-gray-600 font-medium">{{ selectedDoc.tahun }}</p>
          </div>

          <div>
            <p class="text-black font-bold">Status</p>
            <p class="text-gray-600 font-medium">{{ selectedDoc.status_akses }}</p>
          </div>

          <div class="col-span-2">
            <p class="text-black font-bold">Lokasi</p>
            <p class="text-gray-600 font-medium">{{ selectedDoc.lokasi }}</p>
          </div>
        </div>

        <div class="mt-6">
          <p class="text-black font-bold">Deskripsi</p>
          <p class="text-gray-600 font-medium">{{ selectedDoc.deskripsi || '-' }}</p>
        </div>
      </div>

      <div v-if="!canAccessFull(selectedDoc)" class="mt-4 space-y-2">

  <!-- STATUS -->
  <div v-if="selectedDoc?.request_status === 'pending'"
       class="text-yellow-500 font-semibold text-sm">
    ⏳ Menunggu persetujuan
  </div>

  <div v-else-if="selectedDoc?.request_status === 'rejected'"
       class="text-red-500 font-semibold text-sm">
    ❌ Akses ditolak
  </div>

  <!-- BUTTON -->
  <button
    v-else-if="selectedDoc.status_akses === 'private'"
    @click.stop.prevent="requestAkses(selectedDoc.id)"
    class="bg-yellow-500 text-white px-4 py-2 rounded-lg text-sm font-semibold"
  >
    Minta Akses
  </button>

</div>

      <!-- ACTION -->
      <div class="flex justify-end gap-3 mt-6">

        <!-- DOWNLOAD -->
        <button
          v-if="selectedDoc.files?.length && canAccessFull(selectedDoc)"
          @click="handleDownload(selectedDoc.id)"
          class="bg-blue-600 text-white px-4 py-2 rounded-xl font-bold"
        >
          Download
        </button>

        <!-- CLOSE -->
        <button
          @click="previewModal=false; selectedDoc = null"
          class="bg-slate-100 px-4 py-2 rounded-xl text-sm"
        >
          Tutup
        </button>

      </div>

    </div>

  </div>
</div>

</template>