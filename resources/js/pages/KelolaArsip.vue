<script setup lang="ts">
import { ref, computed } from 'vue'
import { Head, router, usePage } from '@inertiajs/vue3'
import AuthLayout from '@/layouts/AuthLayout.vue'

defineOptions({ layout: AuthLayout })

const page = usePage()

/* =========================
   FILTER (JENIS SAJA)
========================= */
const filterJenis = ref('')

/* =========================
   HELPER FILE TYPE
========================= */
const getFileType = (path: string) => {
  if (!path) return 'FILE'
  const ext = path.split('.').pop()?.toLowerCase()

  if (['jpg','jpeg','png','gif','webp'].includes(ext)) return 'IMAGE'
  if (ext === 'pdf') return 'PDF'
  if (['doc','docx'].includes(ext)) return 'DOC'
  if (['xls','xlsx'].includes(ext)) return 'EXCEL'
  return 'FILE'
}

/* =========================
   DATA MAPPING
========================= */
const allDocuments = ref(
  (page.props.arsip || []).map((item: any) => ({
    id: item.id,
    title: item.judul,
    nomor: item.nomor,
    deskripsi: item.deskripsi,

    kategori: item.kategori?.nama || '-',
    jenis: item.jenis_arsip || '-',
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
)

/* =========================
   FILTER
========================= */
const filteredDocuments = computed(() => {
  return allDocuments.value.filter(item => {
    return filterJenis.value
      ? item.jenis === filterJenis.value
      : true
  })
})

/* =========================
   PAGINATION
========================= */
const itemsPerPage = 5
const currentPage = ref(1)

const paginatedData = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage
  return filteredDocuments.value.slice(start, start + itemsPerPage)
})

/* =========================
   PREVIEW
========================= */
const previewModal = ref(false)
const selectedDoc = ref<any>(null)

const openPreview = (item: any) => {
  selectedDoc.value = item
  previewModal.value = true
}

/* =========================
   ACTION
========================= */
const handleEdit = (id: number) => {
  router.get(`/edit-dokumen/${id}`)
}

const showModal = ref(false)
const modalConfig = ref({ id: null as number | null })

const openConfirmDelete = (id: number) => {
  modalConfig.value.id = id
  showModal.value = true
}

const handleExecute = () => {
  if (!modalConfig.value.id) return

  router.delete(`/arsip/${modalConfig.value.id}`, {
    onSuccess: () => {
      showModal.value = false
    },
    onError: (err) => {
      console.log(err)
      alert('Gagal hapus data')
    }
  })
}

</script>
<template>
  <Head title="Kelola Arsip Saya" />

  <div class="py-10 px-6 max-w-6xl mx-auto">

    <h1 class="text-4xl font-black mb-6 text-gray-800 uppercase">
      Kelola Arsip Saya
    </h1>

    <!-- FILTER -->
    <div class="flex gap-4 mb-6">
      <select v-model="filterJenis" class="p-3 rounded-xl border">
        <option value="">Semua Jenis</option>
        <option value="aktif">Aktif</option>
        <option value="inaktif">Inaktif</option>
        <option value="vital">Vital</option>
      </select>
    </div>

    <!-- LIST -->
    <div class="space-y-4 mb-8">

      <div
        v-for="item in paginatedData"
        :key="item.id"
        @click="openPreview(item)"
        class="flex items-center justify-between bg-[#7fa1b1] p-4 rounded-2xl shadow-md cursor-pointer hover:scale-[1.01] transition-all"
      >

        <!-- LEFT -->
        <div class="flex items-center gap-6 flex-1">

          <div class="w-20 h-20 bg-white rounded-2xl flex items-center justify-center">
            📄
          </div>

          <div class="text-white">

            <h3 class="font-black text-gray-900 text-lg">
              {{ item.title }}
            </h3>

            <div class="flex flex-wrap gap-2 mt-1">

              <span class="bg-black/20 px-3 py-0.5 rounded-full text-[10px] font-bold">
                No: {{ item.nomor }}
              </span>

              <span class="bg-blue-500/30 px-3 py-0.5 rounded-full text-[10px] font-bold">
                {{ item.kategori }}
              </span>

              <span class="bg-green-500/30 px-3 py-0.5 rounded-full text-[10px] font-bold uppercase">
                {{ item.jenis }}
              </span>

            </div>

            <div class="flex gap-3 mt-1">
              <span class="text-xs italic">
                {{ item.tanggal }}
              </span>
            </div>

          </div>
        </div>

        <!-- BUTTON -->
        <div class="flex gap-2">

          <!-- EDIT -->
          <button
            type="button"
            @click.stop="handleEdit(item.id)"
            class="group relative p-3 bg-white/30 hover:bg-white rounded-xl shadow transition-all"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15.232 5.232l3.536 3.536M9 11l6-6a2.121 2.121 0 013 3l-6 6-4 1 1-4z" />
            </svg>

            <!-- TOOLTIP -->
            <span class="absolute -top-8 left-1/2 -translate-x-1/2 text-[10px] bg-black text-white px-2 py-1 rounded opacity-0 group-hover:opacity-100">
              Edit
            </span>
          </button>

          <!-- DELETE -->
          <button
            type="button"
            @click.stop="openConfirmDelete(item.id)"
            class="group relative p-3 bg-red-500 hover:bg-red-600 text-white rounded-xl shadow transition-all"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M6 7h12M9 7V4h6v3m-8 0v13m4-13v13m4-13v13" />
            </svg>

            <!-- TOOLTIP -->
            <span class="absolute -top-8 left-1/2 -translate-x-1/2 text-[10px] bg-black text-white px-2 py-1 rounded opacity-0 group-hover:opacity-100">
              Hapus
            </span>
          </button>

        </div>

      </div>

    </div>

    <!-- EMPTY -->
    <div v-if="filteredDocuments.length === 0" class="text-center py-10">
      Tidak ada arsip
    </div>

  </div>

  <!-- PREVIEW MODAL (TIDAK DIUBAH) -->
  <div v-if="previewModal"
  class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-6">

  <div class="bg-white w-full max-w-5xl rounded-[30px] shadow-2xl overflow-hidden flex flex-col md:flex-row">

    <!-- LEFT -->
    <div class="w-full md:w-1/2 bg-gray-100 flex items-center justify-center p-6">

      <img v-if="selectedDoc.format === 'IMAGE'"
        :src="`/storage/${selectedDoc.files[0].path_file}`"
        class="max-h-[400px] object-contain rounded-xl shadow" />

      <iframe v-else-if="selectedDoc.format === 'PDF'"
        :src="`/storage/${selectedDoc.files[0].path_file}`"
        class="w-full h-[400px] rounded-xl"></iframe>

      <div v-else class="text-gray-500 text-center">
        📄<br/>Preview tidak tersedia
      </div>

    </div>

    <!-- RIGHT -->
    <div class="w-full md:w-1/2 p-8 flex flex-col justify-between">

      <div>
        <div class="flex justify-between items-start mb-4">
          <h2 class="text-2xl font-black text-gray-800">
            {{ selectedDoc.title }}
          </h2>

          <button @click="previewModal = false">✕</button>
        </div>

        <div class="flex flex-wrap gap-2 mb-4">
          <span class="bg-gray-200 px-3 py-1 rounded-full text-xs font-bold">
            No: {{ selectedDoc.nomor }}
          </span>

          <span class="bg-blue-100 px-3 py-1 rounded-full text-xs font-bold">
            {{ selectedDoc.kategori }}
          </span>

          <span class="bg-green-100 px-3 py-1 rounded-full text-xs font-bold uppercase">
            {{ selectedDoc.jenis }}
          </span>
        </div>

        <div class="grid grid-cols-2 gap-4 text-sm">
          <div>
            <p class="font-bold">Tahun</p>
            <p>{{ selectedDoc.tahun }}</p>
          </div>

          <div>
            <p class="font-bold">Status</p>
            <p>{{ selectedDoc.status }}</p>
          </div>

          <div class="col-span-2">
            <p class="font-bold">Lokasi</p>
            <p>{{ selectedDoc.lokasi }}</p>
          </div>
        </div>

        <div class="mt-6">
          <p class="font-bold">Deskripsi</p>
          <p>{{ selectedDoc.deskripsi || '-' }}</p>
        </div>
      </div>

      <div class="flex justify-end gap-3 mt-6">
        <a
          v-if="selectedDoc.files.length"
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
 <!-- MODAL KONFIRMASI HAPUS -->
<div v-if="showModal"
  class="fixed inset-0 z-[60] flex items-center justify-center bg-black/50 backdrop-blur-sm">

  <div class="bg-white rounded-2xl p-6 w-full max-w-sm shadow-xl">

    <h2 class="text-lg font-bold text-gray-800 mb-3">
      Konfirmasi Hapus
    </h2>

    <p class="text-sm text-gray-600 mb-6">
      Yakin mau hapus arsip ini? 
    </p>

    <div class="flex justify-end gap-3">

      <button
        @click="showModal = false"
        class="px-4 py-2 rounded-xl bg-gray-200 hover:bg-gray-300 text-sm"
      >
        Batal
      </button>

      <button
        @click="handleExecute"
        class="px-4 py-2 rounded-xl bg-red-500 hover:bg-red-600 text-white text-sm"
      >
        Hapus
      </button>

    </div>

  </div>
</div>

</template>