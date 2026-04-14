<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AuthLayout from '@/layouts/AuthLayout.vue'
import { Pencil, Trash2, FileText } from 'lucide-vue-next'

defineOptions({
  layout: AuthLayout
})

const arsipData = Array(7).fill({
  judul: 'Surat Tugas Dinas Kabupaten Boyolali - Desember 2022',
  format: 'PDF',
  tanggal: '8/3/25'
});

const handleEdit = (index) => console.log('Edit:', index)
const handleDelete = (index) => confirm('Hapus?') && console.log('Delete:', index)
</script>

<template>
  <Head title="Kelola Arsip Saya" />

  <div class="p-8 bg-[#f3f4f6] min-h-screen">
    <h1 class="text-3xl font-bold text-gray-800 mb-10">Kelola Arsip Saya</h1>

    <div class="space-y-6 w-full max-w-[1400px]">
      <div 
        v-for="(item, index) in arsipData" 
        :key="index"
        class="flex items-center group w-full"
      >
        <div class="flex-grow bg-[#7fa6b3] p-5 rounded-[18px] shadow-md flex items-center gap-6 border border-white/10 hover:brightness-95 transition-all">
          <div class="w-24 h-14 bg-white rounded-xl flex-shrink-0 shadow-inner border border-gray-100 flex items-center justify-center">
            <FileText class="w-6 h-6 text-gray-400 opacity-20" />
          </div>
          
          <div class="flex-1 text-white">
            <h3 class="text-base font-semibold text-gray-950 leading-tight mb-2.5">
              {{ item.judul }}
            </h3>
            <div class="flex gap-2.5">
              <span class="bg-white px-4 py-1.5 rounded-full text-[10px] font-bold text-slate-700 shadow-sm uppercase">
                {{ item.format }}
              </span>
              <span class="bg-white px-4 py-1.5 rounded-full text-[10px] font-bold text-slate-700 shadow-sm">
                {{ item.tanggal }}
              </span>
            </div>
          </div>
        </div>

        <div class="flex gap-4 ml-10 flex-shrink-0">
          <button 
            @click="handleEdit(index)"
            class="w-12 h-12 bg-gray-200 hover:bg-gray-300 rounded-xl flex items-center justify-center transition-all shadow-sm group/edit"
          >
            <Pencil class="w-5 h-5 text-gray-800 stroke-[1.5px] group-hover/edit:scale-110 transition-transform" />
          </button>
          
          <button 
            @click="handleDelete(index)"
            class="w-12 h-12 bg-gray-200 hover:bg-red-100 rounded-xl flex items-center justify-center transition-all shadow-sm group/del"
          >
            <Trash2 class="w-5 h-5 text-red-600 stroke-[1.5px] group-hover/del:scale-110 transition-transform" />
          </button>
        </div>

      </div>
    </div>

    <div class="flex justify-end pt-10 gap-1.5 w-full max-w-[1400px]">
       <button v-for="p in ['<<', '<', '1', '2', '...', '>>']" :key="p"
               class="px-3.5 py-2 flex items-center justify-center rounded-lg bg-white border border-gray-200 text-[10px] font-bold text-gray-500 hover:bg-[#7fa6b3] hover:text-white shadow-sm transition-all">
          {{ p }}
       </button>
    </div>
  </div>
</template>