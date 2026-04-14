<script setup>
import { ref, computed } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import AuthLayout from '@/layouts/AuthLayout.vue'
import { 
  Trash2, 
  RotateCcw, 
  AlertTriangle, 
  ChevronLeft, 
  ChevronRight,
  ChevronsLeft,
  ChevronsRight 
} from 'lucide-vue-next'

defineOptions({
  layout: AuthLayout
})

const props = defineProps({
  items: {
    type: Array,
    default: () => []
  }
})

// --- DATA DUMMY (Disesuaikan agar bisa mencoba banyak halaman) ---
const displayItems = ref([
  { id: 1, title: 'Dokumentasi Rapat Penandatanganan Peresmian', date: '22/01/2026', doc_no: 'DOK/1012/11/2026', type: 'PNG' },
  { id: 2, title: 'Dokumentasi Rapat Penandatanganan Peresmian', date: '22/01/2026', doc_no: 'DOK/1012/11/2026', type: 'PDF' },
  { id: 3, title: 'Dokumentasi Rapat Penandatanganan Peresmian', date: '22/01/2026', doc_no: 'DOK/1012/11/2026', type: 'XLS' },
  { id: 4, title: 'Arsip Laporan Tahunan 2026', date: '25/01/2026', doc_no: 'DOK/1012/11/2027', type: 'PDF' },
  { id: 5, title: 'Surat Keputusan Kadis', date: '26/01/2026', doc_no: 'DOK/1012/11/2028', type: 'DOC' },
  { id: 6, title: 'Data Inventaris Kantor 2026', date: '27/01/2026', doc_no: 'DOK/1012/11/2029', type: 'XLS' },
])

// --- LOGIK PAGINATION ---
const currentPage = ref(1)
const itemsPerPage = 5 // Per halaman tampil 3 item agar pagination muncul

const totalPages = computed(() => Math.ceil(displayItems.value.length / itemsPerPage))

const paginatedItems = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage
  const end = start + itemsPerPage
  return displayItems.value.slice(start, end)
})

// --- LOGIK MODAL ---
const showModal = ref(false)
const modalConfig = ref({ type: '', id: null, title: '', message: '' })

const openConfirm = (type, id) => {
  modalConfig.value = {
    type: type,
    id: id,
    title: type === 'restore' ? 'Pulihkan Arsip?' : 'Hapus Permanen?',
    message: type === 'restore' 
      ? 'Arsip akan dikembalikan ke daftar utama.' 
      : 'Data ini akan hilang selamanya dan tidak bisa dikembalikan.'
  }
  showModal.value = true
}

const handleExecute = () => {
  const { id } = modalConfig.value
  
  // Efek Berkurang 1 (Dummy filter)
  displayItems.value = displayItems.value.filter(item => item.id !== id)
  
  // Balik ke halaman sebelumnya jika halaman saat ini jadi kosong
  if (paginatedItems.value.length === 0 && currentPage.value > 1) {
    currentPage.value--
  }
  
  showModal.value = false
}
</script>

<template>
  <Head title="Sampah" />

  <div class="p-6 bg-[#f3f4f6] min-h-screen flex flex-col">
    <div class="flex-1 space-y-6">
      <h1 class="text-3xl font-bold text-gray-800 font-sans">Sampah</h1>

      <div class="bg-gradient-to-r from-[#b91c1c] via-[#F4320B] to-[#F4870B] text-white p-4 rounded-xl shadow-md flex items-start gap-4">
        <AlertTriangle class="w-6 h-6 shrink-0 mt-1" />
        <div>
          <p class="font-bold text-lg leading-none">Warning Sign</p>
          <p class="text-sm opacity-90 mt-1">Sistem ini akan otomatis dihapus sebelum 30 hari.</p>
        </div>
      </div>

      <div class="space-y-4">
        <div
          v-for="item in paginatedItems"
          :key="item.id"
          class="bg-[#7fa6b3] rounded-2xl p-5 shadow-md flex flex-col md:flex-row items-center gap-4 border-b-4 border-gray-400/50"
        >
          <div class="bg-white p-3 rounded-lg border-2 border-red-500 shadow-inner">
            <AlertTriangle class="w-10 h-10 text-red-600" />
          </div>

          <div class="flex-1 space-y-2 text-center md:text-left">
            <p class="font-bold text-gray-900 text-lg leading-tight uppercase">{{ item.title }}</p>
            <div class="flex flex-wrap justify-center md:justify-start gap-3">
              <span class="text-[10px] text-gray-800 font-bold uppercase">Dihapus : {{ item.date }}</span>
              <span class="text-[10px] text-gray-800 font-bold uppercase">No. Dokumen : {{ item.doc_no }}</span>
            </div>
          </div>

          <div class="flex flex-col items-center md:items-end gap-6 w-full md:w-auto">
            <span class="text-[10px] px-4 py-1 rounded-full shadow-sm font-bold border border-gray-200 uppercase">
              {{ item.type }}
            </span>

            <div class="flex gap-3">
              <button @click="openConfirm('restore', item.id)" class="bg-white hover:bg-gray-100 text-gray-800 px-6 py-2 rounded-full text-xs font-bold shadow-md transition-all flex items-center gap-2">
                <RotateCcw class="w-3 h-3" /> Pulihkan
              </button>
              <button @click="openConfirm('delete', item.id)" class="bg-[#e11d48] hover:bg-red-700 text-white px-6 py-2 rounded-full text-xs font-bold shadow-md transition-all flex items-center gap-2">
                <Trash2 class="w-3 h-3" /> Hapus Permanen
              </button>
            </div>
          </div>
        </div>
      </div>

      <div v-if="displayItems.length === 0" class="text-center py-20 text-gray-400">
        <Trash2 class="w-16 h-16 mx-auto mb-4 opacity-20" />
        <p>Folder sampah kosong.</p>
      </div>
    </div>

    <div v-if="totalPages > 1" class="flex justify-end items-center gap-2 mt-8 mb-4">
      
      <button 
        @click="currentPage = 1" 
        :disabled="currentPage === 1"
        class="w-10 h-10 flex items-center justify-center rounded-xl bg-white border border-gray-200 shadow-sm disabled:opacity-30 hover:bg-gray-50 transition-all"
      >
        <ChevronsLeft class="w-4 h-4 text-gray-600" />
      </button>

      <button 
        @click="currentPage--" 
        :disabled="currentPage === 1"
        class="w-10 h-10 flex items-center justify-center rounded-xl bg-white border border-gray-200 shadow-sm disabled:opacity-30 hover:bg-gray-50 transition-all"
      >
        <ChevronLeft class="w-4 h-4 text-gray-600" />
      </button>

      <div class="flex gap-2">
        <button 
          v-for="page in totalPages" 
          :key="page"
          @click="currentPage = page"
          :class="[
            'w-10 h-10 flex items-center justify-center rounded-xl font-bold text-sm transition-all border shadow-sm',
            currentPage === page 
              ? 'bg-[#2f6f7e] text-white border-[#2f6f7e]' 
              : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50'
          ]"
        >
          {{ page }}
        </button>
      </div>

      <button 
        @click="currentPage++" 
        :disabled="currentPage === totalPages"
        class="w-10 h-10 flex items-center justify-center rounded-xl bg-white border border-gray-200 shadow-sm disabled:opacity-30 hover:bg-gray-50 transition-all"
      >
        <ChevronRight class="w-4 h-4 text-gray-600" />
      </button>

      <button 
        @click="currentPage = totalPages" 
        :disabled="currentPage === totalPages"
        class="w-10 h-10 flex items-center justify-center rounded-xl bg-white border border-gray-200 shadow-sm disabled:opacity-30 hover:bg-gray-50 transition-all"
      >
        <ChevronsRight class="w-4 h-4 text-gray-600" />
      </button>

    </div>
  </div>

  <div v-if="showModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
    <div class="relative bg-[#94B3C1] border-2 border-gray-400 w-full max-w-sm rounded-[40px] overflow-hidden p-10 text-center shadow-[2px_1px_15px_rgba(0,0,0,1)]"
         style="background-image: url('/image/moroccan-flower-dark.png'); 
                background-blend-mode: soft-light; 
                background-size: 180px; 
                opacity: 0.9; 
                background-repeat: repeat;">
                <div class="bg-white w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6 border-2 border-red-500 shadow-md">
        <AlertTriangle class="w-12 h-12 text-red-600" />
      </div>

      <h3 class="text-2xl font-black text-gray-900 mb-3 tracking-tight">{{ modalConfig.title }}</h3>
      <p class="text-sm font-semibold text-gray-800 mb-10 leading-relaxed px-4">{{ modalConfig.message }}</p>

      <div class="flex gap-4">
        <button 
          @click="showModal = false" 
          class="flex-1 bg-[#D1D5DB] hover:bg-gray-400 text-gray-900 font-bold py-3.5 rounded-2xl transition shadow-md active:scale-95"
        >
          Batalkan
        </button>
        <button 
          @click="handleExecute" 
          class="flex-1 bg-[#E11D48] hover:bg-red-700 text-white font-bold py-3.5 rounded-2xl transition shadow-md active:scale-95"
        >
          Iya
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Tambahan agar font terasa lebih clean */
.font-sans {
  font-family: 'Inter', 'Segoe UI', sans-serif;
}
</style>
