<script setup>
import { ref, computed } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import AdminLayout from '@/layouts/AdminLayout.vue'
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
  layout: AdminLayout
})

const props = defineProps({
  items: {
    type: Array,
    default: () => []
  }
})

/* FORMAT TYPE FILE */
const getType = (files) => {
  if (!files || files.length === 0) return 'FILE'
  const ext = files[0].path_file.split('.').pop()?.toUpperCase()
  return ext || 'FILE'
}

/* DUMMY */
const displayItems = ref(
  props.items.map(item => ({
    id: item.id,
    title: item.judul,
    date: item.deleted_at 
      ? new Date(item.deleted_at).toLocaleDateString()
      : '-',
    doc_no: item.nomor || '-',
    type: getType(item.files)
  }))
)


const currentPage = ref(1)
const itemsPerPage = 5

const totalPages = computed(() => Math.ceil(displayItems.value.length / itemsPerPage))

const paginatedItems = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage
  const end = start + itemsPerPage
  return displayItems.value.slice(start, end)
})


const showModal = ref(false)
const modalConfig = ref({ type: '', id: null, title: '', message: '' })

const openConfirm = (type, id) => {
  modalConfig.value = {
    type,
    id,
    title: type === 'restore' ? 'Pulihkan Arsip?' : 'Hapus Permanen?',
    message: type === 'restore' 
      ? 'Arsip akan dikembalikan ke daftar utama.' 
      : 'Data ini akan hilang selamanya dan tidak bisa dikembalikan.'
  }
  showModal.value = true
}


const handleExecute = () => {
  const { id, type } = modalConfig.value

  if (type === 'restore') {
    router.post(`/arsip/${id}/restore`, {}, {
      onSuccess: () => {
        displayItems.value = displayItems.value.filter(item => item.id !== id)
      }
    })
  } else {
    router.delete(`/arsip/${id}/force`, {
      onSuccess: () => {
        displayItems.value = displayItems.value.filter(item => item.id !== id)
      }
    })
  }

  // FIX pagination 
  if (paginatedItems.value.length === 1 && currentPage.value > 1) {
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

      <!-- WARNING -->
      <div class="bg-gradient-to-r from-[#b91c1c] via-[#F4320B] to-[#F4870B] text-white p-4 rounded-xl shadow-md flex items-start gap-4">
        <AlertTriangle class="w-6 h-6 shrink-0 mt-1" />
        <div>
          <p class="font-bold text-lg leading-none">Warning Sign</p>
          <p class="text-sm opacity-90 mt-1">Sistem ini akan otomatis dihapus sebelum 30 hari.</p>
        </div>
      </div>

      <!-- LIST -->
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
            <p class="font-bold text-gray-900 text-lg uppercase">{{ item.title }}</p>

            <div class="flex flex-wrap justify-center md:justify-start gap-3">
              <span class="text-[10px] font-bold uppercase">Dihapus : {{ item.date }}</span>
              <span class="text-[10px] font-bold uppercase">No. Dokumen : {{ item.doc_no }}</span>
            </div>
          </div>

          <div class="flex flex-col items-center md:items-end gap-6 w-full md:w-auto">
            <span class="text-[10px] px-4 py-1 rounded-full font-bold border uppercase">
              {{ item.type }}
            </span>

            <div class="flex gap-3">
              <button @click="openConfirm('restore', item.id)"
                class="bg-white px-6 py-2 rounded-full text-xs font-bold flex items-center gap-2">
                <RotateCcw class="w-3 h-3" /> Pulihkan
              </button>

              <button @click="openConfirm('delete', item.id)"
                class="bg-[#e11d48] text-white px-6 py-2 rounded-full text-xs font-bold flex items-center gap-2">
                <Trash2 class="w-3 h-3" /> Hapus Permanen
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- EMPTY -->
      <div v-if="displayItems.length === 0" class="text-center py-20 text-gray-400">
        <Trash2 class="w-16 h-16 mx-auto mb-4 opacity-20" />
        <p>Folder sampah kosong.</p>
      </div>
    </div>

    <!-- PAGINATION -->
    <div v-if="totalPages > 1" class="flex justify-end items-center gap-2 mt-8 mb-4">
      <button @click="currentPage = 1" :disabled="currentPage === 1"><ChevronsLeft /></button>
      <button @click="currentPage--" :disabled="currentPage === 1"><ChevronLeft /></button>

      <button 
        v-for="page in totalPages" 
        :key="page"
        @click="currentPage = page">
        {{ page }}
      </button>

      <button @click="currentPage++" :disabled="currentPage === totalPages"><ChevronRight /></button>
      <button @click="currentPage = totalPages" :disabled="currentPage === totalPages"><ChevronsRight /></button>
    </div>
  </div>

  <!-- MODAL -->
  <div v-if="showModal" class="fixed inset-0 flex items-center justify-center bg-black/40">
    <div class="bg-white p-6 rounded-xl text-center">
      <h3 class="font-bold mb-2">{{ modalConfig.title }}</h3>
      <p>{{ modalConfig.message }}</p>

      <div class="flex gap-3 mt-4 justify-center">
        <button @click="showModal = false">Batal</button>
        <button @click="handleExecute" class="text-red-600 font-bold">
          Iya
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped>
.font-sans {
  font-family: 'Inter', 'Segoe UI', sans-serif;
}
</style>