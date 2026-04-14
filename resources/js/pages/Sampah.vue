<script setup>
import { ref, computed } from 'vue' // Tambahkan ref dan computed
import { Head, Link } from '@inertiajs/vue3'
import AuthLayout from '@/layouts/AuthLayout.vue'
import { Trash2, RotateCcw, AlertTriangle, ChevronLeft, ChevronRight } from 'lucide-vue-next'

defineOptions({
  layout: AuthLayout
})

// 1. Data Dummy (Ditambah menjadi lebih banyak untuk simulasi pagination)
const allItems = ref([
  { id: 1, title: 'Dokumentasi Rapat Penandatanganan Peresmian 1', date: '22/10/2023', doc_no: 'DOK/1012/11/2026', type: 'PNG' },
  { id: 2, title: 'Dokumentasi Rapat Penandatanganan Peresmian 2', date: '22/10/2023', doc_no: 'DOK/1012/11/2026', type: 'PDF' },
  { id: 3, title: 'Dokumentasi Rapat Penandatanganan Peresmian 3', date: '22/10/2023', doc_no: 'DOK/1012/11/2026', type: 'XLS' },
  { id: 4, title: 'Dokumentasi Rapat Penandatanganan Peresmian 4', date: '22/10/2023', doc_no: 'DOK/1012/11/2026', type: 'PNG' },
  { id: 5, title: 'Dokumentasi Rapat Penandatanganan Peresmian 5', date: '22/10/2023', doc_no: 'DOK/1012/11/2026', type: 'PDF' },
  { id: 6, title: 'Dokumentasi Rapat Penandatanganan Peresmian 6', date: '22/10/2023', doc_no: 'DOK/1012/11/2026', type: 'XLS' },
  { id: 7, title: 'Dokumentasi Rapat Penandatanganan Peresmian 7', date: '22/10/2023', doc_no: 'DOK/1012/11/2026', type: 'PNG' },
])

// 2. Logika Pagination Frontend
const currentPage = ref(1)
const itemsPerPage = 5 // Jumlah data per halaman

const totalPages = computed(() => Math.ceil(allItems.value.length / itemsPerPage))

// Mengambil data yang hanya tampil di halaman aktif
const paginatedItems = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage
  const end = start + itemsPerPage
  return allItems.value.slice(start, end)
})

const setPage = (page) => {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page
    window.scrollTo({ top: 0, behavior: 'smooth' })
  }
}

// Function aksi
const handleRestore = (id) => {
  if (confirm('Pulihkan dokumen ini?')) {
    // Logic restore
  }
}

const handleDeletePermanent = (id) => {
  if (confirm('Hapus permanen? Data tidak bisa dikembalikan!')) {
    // Logic delete
    allItems.value = allItems.value.filter(item => item.id !== id)
  }
}
</script>

<template>
  <Head title="Sampah" />

  <div class="p-6 bg-[#f3f4f6] min-h-screen space-y-6">
    
    <h1 class="text-3xl font-bold text-gray-800">Sampah</h1>

    <div class="bg-gradient-to-r from-[#e11d48] to-[#F4870B] text-white p-4 rounded-xl shadow-md flex items-start gap-4">
      <AlertTriangle class="w-6 h-6 shrink-0 mt-1" />
      <div>
        <p class="font-bold text-lg">Warning Sign</p>
        <p class="text-sm opacity-90">
          Sistem ini akan otomatis dihapus sebelum 30 hari.
        </p>
      </div>
    </div>

    <div class="space-y-4">
      <div
        v-for="item in paginatedItems"
        :key="item.id"
        class="bg-[#7fa6b3] rounded-xl p-5 shadow-md flex flex-col md:flex-row items-center gap-4 border-b-4 border-gray-400/50"
      >
        <div class="bg-white p-3 rounded-lg border-2 border-red-500 shadow-inner">
          <AlertTriangle class="w-10 h-10 text-red-600" />
        </div>

        <div class="flex-1 space-y-2 text-center md:text-left">
          <p class="font-bold text-gray-900 text-m leading-tight">
            {{ item.title }}
          </p>
          <div class="flex flex-wrap justify-center md:justify-start gap-2">
            <span class="text-[10px] text-black font-medium">
              Dihapus : {{ item.date }}
            </span>
            <span class="text-[10px] text-black font-medium">
              No. Dokumen : {{ item.doc_no }}
            </span>
          </div>
        </div>

        <div class="flex flex-col items-center md:items-end gap-6 w-full md:w-auto">
          <span class="text-[10px] px-4 py-1 bg-white/20 rounded-full shadow-sm font-bold border border-white/30 uppercase tracking-wider text-white">
            {{ item.type }}
          </span>

          <div class="flex gap-3">
            <button 
              @click="handleRestore(item.id)"
              class="bg-white hover:bg-gray-100 text-gray-800 px-6 py-2 rounded-full text-xs font-bold shadow-md transition-all flex items-center gap-2"
            >
              <RotateCcw class="w-3 h-3" /> Pulihkan
            </button>
            <button 
              @click="handleDeletePermanent(item.id)"
              class="bg-[#e11d48] hover:bg-red-700 text-white px-6 py-2 rounded-full text-xs font-bold shadow-md transition-all flex items-center gap-2"
            >
              <Trash2 class="w-3 h-3" /> Hapus Permanen
            </button>
          </div>
        </div>
      </div>
    </div>

    <div v-if="totalPages > 1" class="flex justify-center items-center gap-3 mt-8">
      <button 
        @click="setPage(currentPage - 1)"
        :disabled="currentPage === 1"
        class="p-2 rounded-lg bg-white border border-gray-200 disabled:opacity-30 disabled:cursor-not-allowed hover:bg-gray-50 transition-colors"
      >
        <ChevronLeft class="w-5 h-5 text-gray-600" />
      </button>

      <div class="flex gap-2">
        <button 
          v-for="page in totalPages" 
          :key="page"
          @click="setPage(page)"
          class="w-10 h-10 rounded-lg text-sm font-bold transition-all border"
          :class="currentPage === page 
            ? 'bg-[#2f55a4] text-white border-[#2f55a4] shadow-md' 
            : 'bg-white text-gray-600 border-gray-200 hover:border-blue-400'"
        >
          {{ page }}
        </button>
      </div>

      <button 
        @click="setPage(currentPage + 1)"
        :disabled="currentPage === totalPages"
        class="p-2 rounded-lg bg-white border border-gray-200 disabled:opacity-30 disabled:cursor-not-allowed hover:bg-gray-50 transition-colors"
      >
        <ChevronRight class="w-5 h-5 text-gray-600" />
      </button>
    </div>

    <div v-if="allItems.length === 0" class="text-center py-20 text-gray-400">
      <Trash2 class="w-16 h-16 mx-auto mb-4 opacity-20" />
      <p>Folder sampah kosong.</p>
    </div>

  </div>
</template>

<style scoped>
.bg-custom-blue {
  background-color: #7fa6b3;
}
</style>