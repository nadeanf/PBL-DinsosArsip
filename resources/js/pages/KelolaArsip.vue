<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import AuthLayout from '@/layouts/AuthLayout.vue';

defineOptions({ layout: AuthLayout });

const page = usePage();

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

    files: item.files || [],
    format: item.files?.length
      ? getFileType(item.files[0].path_file)
      : 'FILE',

    tanggal: item.created_at
      ? new Date(item.created_at).toLocaleDateString()
      : '-'
  }))
);

/* =========================
   PREVIEW MODAL
========================= */
const previewModal = ref(false)
const selectedDoc = ref<any>(null)

const openPreview = (item: any) => {
  selectedDoc.value = item
  previewModal.value = true
}

/* =========================
   EDIT
========================= */
const handleEdit = (id: number) => {
  router.get(`/edit-dokumen/${id}`);
}

/* =========================
   DELETE
========================= */
const showModal = ref(false)
const modalConfig = ref({
  id: null as number | null,
  message: 'Anda yakin akan menghapus file ini?'
})

const openConfirmDelete = (id: number) => {
  modalConfig.value.id = id
  showModal.value = true
}

const handleExecute = () => {
  router.delete(`/arsip/${modalConfig.value.id}`, {
    onSuccess: () => {
      showModal.value = false
    }
  })
}

/* =========================
   PAGINATION
========================= */
const itemsPerPage = 5
const currentPage = ref(1)

const totalPages = computed(() =>
  Math.ceil(allDocuments.value.length / itemsPerPage)
)

const paginatedData = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage
  return allDocuments.value.slice(start, start + itemsPerPage)
})

const setPage = (pageNum: number) => {
  if (pageNum >= 1 && pageNum <= totalPages.value) {
    currentPage.value = pageNum
    window.scrollTo({ top: 0, behavior: 'smooth' })
  }
}
</script>

<template>
  <Head title="Kelola Arsip Saya" />

  <div class="py-10 px-6 max-w-6xl mx-auto">
    <h1 class="text-4xl font-black mb-10 text-gray-800 uppercase tracking-tight italic">
      Kelola Arsip Saya
    </h1>

    <!-- LIST -->
    <div class="space-y-4 mb-8">
      <div
        v-for="item in paginatedData"
        :key="item.id"
        @click="openPreview(item)"
        class="flex items-center justify-between bg-[#7fa1b1] p-4 rounded-2xl shadow-md border-b-4 border-black/10 cursor-pointer hover:scale-[1.01] transition-all"
      >

        <!-- LEFT -->
        <div class="flex items-center gap-6 flex-1">

          <div class="w-20 h-20 bg-white rounded-2xl flex items-center justify-center shadow-inner text-[#7fa1b1]">
            📄
          </div>

          <div class="text-white">

            <h3 class="font-black text-gray-900 text-lg mb-1">
              {{ item.title }}
            </h3>

            <!-- NOMOR + KATEGORI -->
            <div class="flex flex-wrap gap-2 mb-1">
              <span class="bg-black/20 px-3 py-0.5 rounded-full text-[10px] font-bold">
                No: {{ item.nomor }}
              </span>

              <span class="bg-blue-500/30 px-3 py-0.5 rounded-full text-[10px] font-bold">
                {{ item.kategori }}
              </span>
            </div>

            <!-- FORMAT + TANGGAL -->
            <div class="flex gap-3 items-center">
              <span class="bg-black/20 px-3 py-0.5 rounded-full text-[10px] font-black uppercase">
                {{ item.format }}
              </span>

              <span class="text-xs font-bold text-white/80 italic">
                {{ item.tanggal }}
              </span>
            </div>

          </div>
        </div>

        <!-- RIGHT BUTTON -->
        <div class="flex gap-3 ml-4">

          <!-- EDIT -->
          <button
            @click.stop="handleEdit(item.id)"
            class="group relative p-4 bg-white/30 hover:bg-white rounded-2xl transition-all duration-300 shadow-md"
          >
            ✏️
            <span class="absolute -top-8 left-1/2 -translate-x-1/2 text-[10px] bg-black text-white px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition">
              Edit
            </span>
          </button>

          <!-- DELETE -->
          <button
            @click.stop="openConfirmDelete(item.id)"
            class="group relative p-4 bg-red-500 hover:bg-red-700 text-white rounded-2xl transition-all duration-300 shadow-md"
          >
            🗑️
            <span class="absolute -top-8 left-1/2 -translate-x-1/2 text-[10px] bg-black text-white px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition">
              Hapus
            </span>
          </button>

        </div>

      </div>
    </div>

    <!-- EMPTY -->
    <div v-if="allDocuments.length === 0"
      class="text-center py-20 bg-gray-100 rounded-[40px] border-2 border-dashed border-gray-300">
      Tidak ada arsip
    </div>
  </div>

  <!-- PREVIEW MODAL -->
  <div v-if="previewModal"
    class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">

    <div class="bg-white w-full max-w-4xl rounded-[30px] p-6 shadow-2xl">

      <div class="flex justify-between mb-4">
        <h2 class="text-xl font-black">{{ selectedDoc.title }}</h2>
        <button @click="previewModal = false">✕</button>
      </div>

      <div class="flex gap-2 mb-2">
        <span class="bg-blue-600 text-white px-3 py-1 rounded-full text-xs font-bold">
          {{ selectedDoc.format }}
        </span>

        <span class="bg-gray-800 text-white px-3 py-1 rounded-full text-xs font-bold">
          {{ selectedDoc.kategori }}
        </span>
      </div>

      <!-- PREVIEW -->
      <div class="mt-4">
        <img v-if="selectedDoc.format === 'IMAGE'"
          :src="`/storage/${selectedDoc.files[0].path_file}`"
          class="w-full max-h-[400px] object-contain rounded-xl"/>

        <iframe v-else-if="selectedDoc.format === 'PDF'"
          :src="`/storage/${selectedDoc.files[0].path_file}`"
          class="w-full h-[400px] rounded-xl"></iframe>

        <div v-else class="text-center py-10 text-gray-500">
          Preview tidak tersedia
        </div>
      </div>

      <!-- DESKRIPSI -->
      <div class="mt-4">
        <h3 class="font-bold">Deskripsi</h3>
        <p>{{ selectedDoc.deskripsi || '-' }}</p>
      </div>

      <!-- ACTION -->
      <div class="flex justify-end gap-3 mt-6">
        <a
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

  <!-- DELETE MODAL -->
  <div v-if="showModal"
    class="fixed inset-0 flex items-center justify-center bg-black/50">

    <div class="bg-white p-6 rounded-xl text-center">

      <p>{{ modalConfig.message }}</p>

      <div class="flex gap-3 mt-4 justify-center">
        <button @click="showModal = false">Batal</button>
        <button @click="handleExecute" class="text-red-600 font-bold">
          Hapus
        </button>
      </div>

    </div>
  </div>

</template>