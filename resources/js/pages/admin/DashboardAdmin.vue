<script setup>

import { usePage, router } from '@inertiajs/vue3'
import { ref, computed, onMounted, watch } from 'vue'
import { Eye, FileText, FileImage, File } from 'lucide-vue-next'
import { ChevronRight, ChevronDown } from 'lucide-vue-next'
import TreeDropdown from '@/components/TreeDropdown.vue'
import AdminLayout from '@/layouts/AdminLayout.vue'


defineOptions({
  layout: AdminLayout
})

const page = usePage()
console.log('ISI KATEGORI:', page.props.kategori)
console.log('FULL PAGE PROPS:', page.props)
console.log('ARSIP:', page.props.arsip)
console.log('KATEGORI:', page.props.kategori)
const canAccessFull = (doc) => {
  const user = page.props.auth?.user

  if (!doc || !user) return false

  const normalize = (val) =>
    String(val || '').toLowerCase().trim()

  // ADMIN / SUPERADMIN
  if (['admin', 'superadmin'].includes(user.role)) {
    return true
  }

  // SUDAH APPROVED
  if (doc.request_status === 'approved') {
    return true
  }

  // PUBLIK
  if (normalize(doc.status) === 'publik') {
    return true
  }

  // PEMILIK
  if (doc.user_id === user.id) {
    return true
  }

  // BIDANG SAMA
  const userBagian = normalize(user.bagian)
  const docBidang = normalize(doc.bidang)

  if (
    normalize(doc.status) === 'private' &&
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
const tipeDokumen = computed(() => page.props?.tipeDokumen ?? [])
const approvalList = computed(() => page.props?.approvalList ?? [])
const totalApproval = computed(() => page.props?.totalApproval ?? 0)

/* STATE FILTER */
const search = ref('')
const kategori = ref('')
const showDropdown = ref(false)

const selectedKategoriName = computed(() => {
  if (!page.props.kategori) return ''

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
  if (val && !tanggal_akhir.value) {
    tanggal_akhir.value = getTodayDate()
  }
}, { immediate: true })

const exportPDF = () => {
  window.location.href = `/export/pdf?search=${search.value}&kategori=${kategori.value}&tanggal_awal=${tanggal_awal.value}&tanggal_akhir=${tanggal_akhir.value}`
}

/* HELPER FILE TYPE */const getFileType = (path) => {
    if (!path) return 'FILE'

    const ext = path.split('.').pop()?.toLowerCase()

    if (['jpg','jpeg','png','gif','webp'].includes(ext)) return 'IMAGE'

    if (ext === 'pdf') return 'PDF'

    // TAMBAH INI
    if (['xls', 'xlsx', 'csv'].includes(ext)) return 'EXCEL'

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
    bidang: item.bagian || '-',

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

/* LOAD DATA */
const filteredData = computed(() => aktivitasTerbaru.value)

/* LIMIT DASHBOARD */
const limitedData = computed(() => {
  return filteredData.value.slice(0, 3)
})

/* SEARCH REDIRECT */
const handleSearch = () => {
  const params = {
    search: search.value,
    kategori: kategori.value,
    tanggal_awal: tanggal_awal.value,
    from_dashboard: 1
  }

  if (tanggal_akhir.value) {
    params.tanggal_akhir = tanggal_akhir.value
  }

  router.get('/admin/daftar-arsip', params)
}

/* PREVIEW MODAL */
const previewModal = ref(false)
const selectedDoc = ref(null)


const openPreview = (doc) => {
  if (!doc) return
  selectedDoc.value = doc

  console.log('FULL DOC:', doc)
  console.log('STATUS DOC:', doc.status)
  previewModal.value = true
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
  v-if="page.props.kategori"
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

<!-- =======================
     TIPE DOKUMEN + APPROVAL
======================= -->
<div class="grid md:grid-cols-2 gap-6">

  <!-- TIPE DOKUMEN -->
  <div>

    <h2 class="bg-[#2f4fa2] text-white px-4 py-1 rounded-md text-sm w-fit mb-3">
      Tipe Dokumen
    </h2>

    <div class="bg-[#7fa6b3] p-4 rounded-xl space-y-3 shadow">

      <div
        v-for="item in tipeDokumen"
        :key="item.nama"
        class="bg-white px-4 py-3 rounded flex justify-between text-sm"
      >
        <span>{{ item.nama }}</span>
        <span>{{ item.total }} Jumlah</span>
      </div>

    </div>
  </div>

  <!-- MENUNGGU PERSETUJUAN -->
  <div>

    <div class="flex justify-between items-center mb-3">

      <h2 class="bg-[#2f4fa2] text-white px-4 py-1 rounded-md text-sm">
        Menunggu Persetujuan
        <span class="ml-2 bg-white text-black px-2 rounded">
          {{ totalApproval }}
        </span>
      </h2>

      <a href="/admin/persetujuan" class="text-xs bg-gray-200 px-2 py-1 rounded">
      Lihat Semua
    </a>

    </div>

    <div class="space-y-3">

      <div
        v-for="item in approvalList"
        :key="item.title"
        class="bg-[#7fa6b3] p-4 rounded-xl flex justify-between items-center shadow"
      >
        <div>
          <p class="text-white font-semibold text-sm">
            {{ item.title }}
          </p>
          <p class="text-white text-xs">
            Oleh: {{ item.user }} - {{ item.tanggal }}
          </p>
        </div>

        <div class="bg-white px-3 py-1 rounded text-sm">
          {{ item.jumlah }}
        </div>
      </div>

    </div>

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

    <a href="/admin/daftar-arsip" class="text-xs bg-gray-200 px-2 py-1 rounded">
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

<!-- EXCEL -->
<div
    v-else-if="selectedDoc?.format === 'EXCEL' && canAccessFull(selectedDoc)"
    class="text-center"
>
    <p class="mb-4 text-gray-600">
        File Excel tidak bisa dipreview
    </p>

    <a
        :href="`/download/${selectedDoc?.id}`"
        class="bg-blue-600 text-white px-4 py-2 rounded-xl font-bold"
    >
        Download Excel
    </a>
</div>

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
<div v-else-if="selectedDoc?.request_status === 'rejected'"
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
       <button
  v-if="canAccessFull(selectedDoc)"
  @click="handleDownload(selectedDoc.id)"
  class="bg-blue-600 text-white px-4 py-2 rounded-xl font-bold"
>
  Download
</button>

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