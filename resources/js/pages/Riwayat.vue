<script setup>
import { Head } from '@inertiajs/vue3'
import AuthLayout from '@/layouts/AuthLayout.vue'
// Gunakan ikon standard Lucide yang outline-nya bersih sesuai mockup
import { Search, FileText, Image as ImageIcon, Video, Clock } from 'lucide-vue-next'

defineOptions({
  layout: AuthLayout
})

const riwayatData = [
  {
    judul: 'Laporan Keuangan Kesekretariatan - Oktober 2025',
    waktu: '10.35',
    tanggal: '25 Februari 2026',
    icon: FileText
  },
  {
    judul: 'Dokumentasi Rapat Anggaran Pendapatan dan Belanja Daerah 2014',
    waktu: '10.35',
    tanggal: '25 Februari 2026',
    icon: ImageIcon
  },
  {
    judul: 'Video Rapat Penyusunan Peraturan Baru Dinas Sosial',
    waktu: '10.35',
    tanggal: '25 Februari 2026',
    icon: Video
  },
  {
    judul: 'Surat Edaran HUT RI Ke-80 Dinas Sosial',
    waktu: '10.35',
    tanggal: '25 Februari 2026',
    icon: FileText
  }
];
</script>

<template>
  <Head title="Riwayat" />

  <div class="p-8 bg-[#f3f4f6] min-h-screen">
    
    <h1 class="text-3xl font-bold text-gray-800 mb-8">Riwayat</h1>

    <div class="relative max-w-2xl mb-10">
      <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
        <Search class="h-5 w-5 text-gray-400" />
      </div>
      <input 
        type="text" 
        placeholder="Cari dalam riwayat..." 
        class="block w-full pl-12 pr-4 py-3 bg-white border border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-[#7fa6b3] outline-none transition-all text-sm"
      />
    </div>

    <div class="space-y-6">
      <div 
        v-for="(item, index) in riwayatData" 
        :key="index"
        class="bg-[#7fa6b3] p-6 rounded-[18px] shadow-md flex items-center border border-white/10 hover:brightness-95 transition-all cursor-pointer"
      >
        <div class="w-16 h-16 bg-white rounded-xl shadow-inner flex-shrink-0 flex items-center justify-center mr-7 border border-gray-100">
          <component :is="item.icon" class="w-8 h-8 text-gray-700 stroke-1" />
        </div>

        <div class="flex-1 text-white">
          <p class="font-semibold text-gray-950 text-base leading-tight mb-2.5">
            {{ item.judul }}
          </p>
          
          <div class="flex flex-wrap gap-2.5 items-center">
            <div class="flex items-center bg-white px-3.5 py-1.5 rounded-full shadow-sm">
              <Clock class="w-3 h-3 text-slate-500 mr-1.5 stroke-1" />
              <span class="text-[10px] font-medium text-slate-700">{{ item.waktu }}</span>
            </div>
            
            <div class="flex items-center bg-white px-3.5 py-1.5 rounded-full shadow-sm">
              <span class="text-[10px] font-medium text-slate-700">{{ item.tanggal }}</span>
            </div>
          </div>
        </div>

      </div>
    </div>

    <div class="flex justify-end pt-10 gap-1.5">
       <button v-for="p in ['<<', '<', '1', '2', '...', '>>']" :key="p"
               class="px-3 py-1.5 flex items-center justify-center rounded bg-white border border-gray-200 text-[10px] font-bold text-gray-500 hover:bg-[#7fa6b3] hover:text-white shadow-sm transition-all">
          {{ p }}
       </button>
    </div>

  </div>
</template>

<style scoped>
/* Menyesuaikan sedikit warna agar mirip dengan screenshot */
.bg-custom-blue {
  background-color: #7fa6b3;
}
</style>