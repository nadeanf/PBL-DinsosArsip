<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AuthLayout from '@/layouts/AuthLayout.vue'
import { Trash2, RotateCcw, AlertTriangle } from 'lucide-vue-next'

defineOptions({
  layout: AuthLayout
})

// Dummy Data - Nanti dioper dari Controller lewat Props
const items = [
  { id: 1, title: 'Dokumentasi Rapat Penandatanganan Peresmian', date: '22/10/2023', doc_no: 'DOK/1012/11/2026', type: 'PNG' },
  { id: 2, title: 'Dokumentasi Rapat Penandatanganan Peresmian', date: '22/10/2023', doc_no: 'DOK/1012/11/2026', type: 'PDF' },
  { id: 3, title: 'Dokumentasi Rapat Penandatanganan Peresmian', date: '22/10/2023', doc_no: 'DOK/1012/11/2026', type: 'XLS' },
]

// Function untuk aksi (napi pakai router.post atau router.delete)
const handleRestore = (id) => {
  if (confirm('Pulihkan dokumen ini?')) {
    // router.post(`/sampah/${id}/restore`)
  }
}

const handleDeletePermanent = (id) => {
  if (confirm('Hapus permanen? Data tidak bisa dikembalikan!')) {
    // router.delete(`/sampah/${id}/force-delete`)
  }
}
</script>

<template>
  <Head title="Sampah" />

  <div class="p-6 bg-[#f3f4f6] min-h-screen space-y-6">
    
    <h1 class="text-3xl font-bold text-gray-800">Sampah</h1>

    <div class="bg-gradient-to-r from-[#e11d48] to-[#F4870B] to-[#F4320B] text-white p-4 rounded-xl shadow-md flex items-start gap-4">
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
        v-for="item in items"
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
            <span class="text-[10px] text black font-medium">
              Dihapus : {{ item.date }}
            </span>
            <span class="text-[10px] text black font-medium">
              No. Dokumen : {{ item.doc_no }}
            </span>
          </div>
        </div>

        <div class="flex flex-col items-center md:items-end gap-6 w-full md:w-auto">
          <span class="text-[10px] px-4 py-1 rounded-full shadow-sm font-bold border border-gray-200 uppercase tracking-wider">
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

    <div v-if="items.length === 0" class="text-center py-20 text-gray-400">
      <Trash2 class="w-16 h-16 mx-auto mb-4 opacity-20" />
      <p>Folder sampah kosong.</p>
    </div>

  </div>
</template>

<style scoped>
/* Menyesuaikan sedikit warna agar mirip dengan screenshot */
.bg-custom-blue {
  background-color: #7fa6b3;
}
</style>