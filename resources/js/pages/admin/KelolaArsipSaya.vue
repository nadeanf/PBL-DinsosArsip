<script setup>
import { ref } from 'vue'
import { Head } from '@inertiajs/vue3'
import AdminLayout from '@/layouts/AdminLayout.vue'
// Menambahkan ikon Eye dan FileText (untuk konten kartu)
import { 
  Pencil, 
  Trash2, 
  FileText, 
  Eye 
} from 'lucide-vue-next'

defineOptions({
  layout: AdminLayout
})

const archives = ref([
  { id: 1, judul: 'Surat Tugas Dinas Kabupaten Boyolali - Desember 2022', tipe: 'PDF', tgl: '8/3/25' },
  { id: 2, judul: 'Surat Tugas Dinas Kabupaten Boyolali - Desember 2022', tipe: 'PDF', tgl: '8/3/25' },
  { id: 3, judul: 'Surat Tugas Dinas Kabupaten Boyolali - Desember 2022', tipe: 'PDF', tgl: '8/3/25' },
  { id: 4, judul: 'Surat Tugas Dinas Kabupaten Boyolali - Desember 2022', tipe: 'PDF', tgl: '8/3/25', active: true },
  { id: 5, judul: 'Surat Tugas Dinas Kabupaten Boyolali - Desember 2022', tipe: 'PDF', tgl: '8/3/25' },
  { id: 6, judul: 'Surat Tugas Dinas Kabupaten Boyolali - Desember 2022', tipe: 'PDF', tgl: '8/3/25' },
  { id: 7, judul: 'Surat Tugas Dinas Kabupaten Boyolali - Desember 2022', tipe: 'PDF', tgl: '8/3/25' },
])
</script>

<template>
  <Head title="Kelola Arsip Saya" />

  <div class="p-8 bg-white min-h-screen">
    <div class="mb-10">
      <h1 class="text-4xl font-black text-black">
        Kelola Arsip Saya
      </h1>
    </div>

    <div class="space-y-4 max-w-6xl">
      <div 
        v-for="item in archives" 
        :key="item.id" 
        class="flex items-center gap-4"
      >
        <div 
          :class="[
            'flex-1 flex items-center gap-6 p-5 rounded-2xl border-4 transition-all',
            item.active 
              ? 'bg-[#73a2b8] border-[#3498db] shadow-md' 
              : 'bg-[#73a2b8] border-transparent'
          ]"
        >
          <div class="w-24 h-16 bg-white rounded-lg flex-shrink-0 flex items-center justify-center shadow-inner">
            <FileText class="w-8 h-8 text-gray-400" />
          </div>
          
          <div class="flex flex-col gap-2 text-white">
            <h3 class="font-bold text-gray-950 text-lg leading-tight">
              {{ item.judul }}
            </h3>
            <div class="flex gap-3">
              <span class="bg-white px-3 py-0.5 rounded-full text-[10px] font-bold text-gray-600 border border-gray-200">
                {{ item.tipe }}
              </span>
              <span class="bg-white px-3 py-0.5 rounded-full text-[10px] font-bold text-gray-600 border border-gray-200">
                {{ item.tgl }}
              </span>
            </div>
          </div>
        </div>

        <div class="flex gap-3 flex-shrink-0">
          <button class="w-12 h-12 bg-white hover:bg-gray-100 border border-gray-200 rounded-xl flex items-center justify-center shadow-sm transition">
            <Eye class="w-6 h-6 text-gray-600" />
          </button>
          
          <button class="w-12 h-12 bg-[#e0e0e0] hover:bg-gray-300 rounded-xl flex items-center justify-center shadow-sm transition">
            <Pencil class="w-5 h-5 text-black" />
          </button>

          <button class="w-12 h-12 bg-[#e0e0e0] hover:bg-red-500 rounded-xl flex items-center justify-center shadow-sm transition group">
            <Trash2 class="w-5 h-5 text-red-600 group-hover:text-white" />
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
h1, h3, span {
  font-family: 'Inter', sans-serif;
}
</style>