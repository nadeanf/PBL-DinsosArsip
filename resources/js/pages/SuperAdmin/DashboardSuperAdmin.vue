<script setup>
import SuperAdminLayout from '@/layouts/SuperAdminLayout.vue'
import { ref, computed } from 'vue'
import { 
  Eye, 
  Download, 
  FileText,
  FileImage,
  File
} from 'lucide-vue-next'
import { router, Link, Head, usePage } from '@inertiajs/vue3'

defineOptions({
  layout: SuperAdminLayout
})

// STATE SEARCH 
const search = ref('')
const kategori = ref('')
const tanggal_awal = ref('')
const tanggal_akhir = ref('')



const handleSearch = () => {
  router.get('/super-admin/daftar-arsip', {
    search: search.value,
    kategori: kategori.value,
    tanggal_awal: tanggal_awal.value,
    tanggal_akhir: tanggal_akhir.value
  })
}

const page = usePage()

const previewModal = ref(false)
const selectedDoc = ref(null)

const dataArsip = computed(() => page.props.arsip ?? [])

const totalView = computed(() => page.props.totalView ?? 0)

const totalDownload = computed(() => page.props.totalDownload ?? 0)

const tipeDokumen = computed(() => page.props.tipeDokumen ?? [])

const getFileType = (path) => {
  if (!path) return 'FILE'

  const ext = path.split('.').pop()?.toLowerCase()

  if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext)) {
    return 'IMAGE'
  }

  if (ext === 'pdf') {
    return 'PDF'
  }

  return 'FILE'
}

const aktivitasTerbaru = computed(() => {
  return dataArsip.value.slice(0, 3).map(item => ({
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

    files: item.files ?? [],

    format: item.files?.length
      ? getFileType(item.files[0].path_file)
      : 'FILE',

    tanggal: item.created_at
      ? new Date(item.created_at).toLocaleDateString()
      : '-'
  }))
})
const openPreview = (doc) => {
  selectedDoc.value = doc
  previewModal.value = true

  // tracking view
  fetch('/riwayat/view', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute('content') || ''
    },
    body: JSON.stringify({
      dokumen_id: doc.id
    })
  })
}
const handleDownload = (id) => {

  const form = document.createElement('form')
  form.method = 'GET'
  form.action = `/download/${id}`

  document.body.appendChild(form)
  form.submit()
  document.body.removeChild(form)

}
const exportPDF = () => {
  window.location.href =
    `/export/pdf?search=${search.value}` +
    `&kategori=${kategori.value}` +
    `&tanggal_awal=${tanggal_awal.value}` +
    `&tanggal_akhir=${tanggal_akhir.value}`
}
</script>

<template>
  <Head title="Dashboard - Super Admin" />

  <div class="p-6 bg-[#f3f4f6] min-h-screen space-y-6">

    <h1 class="text-2xl font-bold text-gray-900">
      Dashboard Super Admin
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
          <div class="bg-white text-[#6f98a8] text-xs px-2 py-1 rounded w-fit mb-1 font-bold">{{ totalView }}</div>
          <p class="text-white text-sm">Dokumen terlihat</p>
          <div class="h-2 bg-gray-300/30 rounded mt-2 w-40"></div>
        </div>
        <div class="bg-white p-3 rounded-lg shadow-inner">
          <Eye class="w-6 h-6 text-[#6f98a8]" />
        </div>
      </div>

      <div class="bg-[#6f98a8] p-4 rounded-xl flex justify-between items-center shadow-sm">
        <div>
          <div class="bg-white text-[#6f98a8] text-xs px-2 py-1 rounded w-fit mb-1 font-bold">{{ totalDownload }}</div>
          <p class="text-white text-sm">Dokumen diunduh</p>
          <div class="h-2 bg-gray-300/30 rounded mt-2 w-40"></div>
        </div>
        <div class="bg-white p-3 rounded-lg shadow-inner">
          <Download class="w-6 h-6 text-[#6f98a8]" />
        </div>
      </div>
    </div>

    <div class="flex items-center justify-between">

  <h2 class="bg-[#2f4fa2] text-white px-4 py-1 rounded-md text-sm">
    Aktivitas Terbaru
  </h2>

  <!-- GROUP BUTTON -->
  <div class="flex items-center gap-2">

    <button
      @click="exportPDF"
      class="bg-red-600 text-white px-4 py-1 rounded text-xs hover:bg-red-700 transition"
    >
      Export PDF
    </button>

    <Link
      href="/super-admin/daftar-arsip"
      class="bg-[#2f4fa2] text-white px-4 py-1 rounded text-xs hover:bg-red-800 transition"
    >
      Lihat Semua
    </Link>

  </div>

</div>

    <div class="space-y-4">
      <div
  v-for="doc in aktivitasTerbaru"
  :key="doc.id"
  @click="openPreview(doc)"
  class="bg-[#6f98a8] rounded-xl p-4 shadow-md flex items-center gap-4 border border-transparent hover:border-white transition cursor-pointer group"
>

  <div class="bg-gray-200 w-12 h-12 rounded flex items-center justify-center">

    <FileText
      v-if="doc.format === 'PDF'"
      class="w-6 h-6 text-red-500"
    />

    <FileImage
      v-else-if="doc.format === 'IMAGE'"
      class="w-6 h-6 text-blue-500"
    />

    <File
      v-else
      class="w-6 h-6 text-gray-500"
    />

  </div>

  <div class="flex-1 text-xs text-white">

    <p class="font-bold text-sm mb-1 group-hover:underline">
      {{ doc.title }}
    </p>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-2 opacity-90">
      <span>No : {{ doc.nomor }}</span>
      <span>Kategori : {{ doc.kategori }}</span>
      <span>Divisi : {{ doc.bidang }}</span>
      <span>Tanggal : {{ doc.tanggal }}</span>
    </div>

  </div>

  <div class="bg-white text-[#6f98a8] text-[10px] font-bold px-3 py-1 rounded-full shadow">
    {{ doc.status }}
  </div>

</div>
    </div>

  </div>
  <!-- PREVIEW MODAL -->
<div
  v-if="previewModal && selectedDoc"
  class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-6"
  @click.self="previewModal = false"
>

  <div class="bg-white w-full max-w-5xl rounded-[30px] shadow-2xl overflow-hidden flex flex-col md:flex-row">

    <!-- PREVIEW FILE -->
    <div class="w-full md:w-1/2 bg-gray-100 flex items-center justify-center p-6">

      <!-- IMAGE -->
      <img
        v-if="selectedDoc?.format === 'IMAGE'"
        :src="`/storage/${selectedDoc?.files?.[0]?.path_file}`"
        class="max-h-[400px] object-contain rounded-xl shadow"
      />

      <!-- PDF -->
      <iframe
        v-else-if="selectedDoc?.format === 'PDF'"
        :src="`/storage/${selectedDoc?.files?.[0]?.path_file}`"
        class="w-full h-[400px] rounded-xl"
      ></iframe>

      <!-- FILE -->
      <div v-else class="text-gray-500">
        File tidak dapat dipreview
      </div>

    </div>

    <!-- DETAIL -->
    <div class="w-full md:w-1/2 p-8 flex flex-col justify-between">

      <div>

        <div class="flex justify-between items-start mb-4">

          <h2 class="text-2xl font-black text-gray-800">
            {{ selectedDoc?.title }}
          </h2>

          <button @click="previewModal = false">
            ✕
          </button>

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

      <!-- BUTTON -->
      <div class="flex justify-end gap-3 mt-6">

        <button
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