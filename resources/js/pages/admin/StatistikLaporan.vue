<script setup>
import { Head } from '@inertiajs/vue3'
import AdminLayout from '@/layouts/AdminLayout.vue'
import { 
  Download, 
  Eye, 
  Star, 
  FileText, 
  Image as ImageIcon, 
  Video, 
  Trash2,
  MoreHorizontal
} from 'lucide-vue-next'

defineOptions({
  layout: AdminLayout
})

// Data Dummy Pengguna Teraktif
const activeUsers = [
  { 
    id: 1, 
    name: 'Admin Arsip', 
    email: 'admin@arsip.go.id', 
    role: 'Admin', 
    status: 'Aktif', 
    joined: '04 Sep 2025', 
    docs: '124 File' 
  },
  { 
    id: 2, 
    name: 'Ahmad Yani', 
    email: 'ahmad.yani@pemerintah.go.id', 
    role: 'User', 
    status: 'Aktif', 
    joined: '15 Aug 2025', 
    docs: 'Draft Surat Edaran SPBE 2026, Laporan Akhir...' 
  },
  { 
    id: 3, 
    name: 'Siti Aminah', 
    email: 'siti.aminah@pemerintah.go.id', 
    role: 'User', 
    status: 'Aktif', 
    joined: '10 Jan 2026', 
    docs: 'Dokumen Perjanjian Kerja Sama Vendor...' 
  },
]
</script>

<template>
  <Head title="Statistik & Laporan" />

  <div class="p-6 space-y-8 bg-gray-100 min-h-screen text-black">
    
    <h1 class="text-3xl font-bold text-black">Statistik & Laporan</h1>

    <div class="grid lg:grid-cols-4 gap-6">
      
      <div class="lg:col-span-3 bg-[#6a97ac] p-8 rounded-2xl shadow-sm flex flex-col md:flex-row items-center gap-10">
        <div class="relative w-56 h-56 flex-shrink-0">
          <svg viewBox="0 0 36 36" class="w-full h-full drop-shadow-lg">
            <path class="text-[#3498db]" stroke-dasharray="68, 100" stroke-width="8" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
            <path class="text-[#f1c40f]" stroke-dasharray="20, 100" stroke-dashoffset="-68" stroke-width="8" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
            <path class="text-[#e67e22]" stroke-dasharray="10, 100" stroke-dashoffset="-88" stroke-width="8" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
            <path class="text-[#2ecc71]" stroke-dasharray="2, 100" stroke-dashoffset="-98" stroke-width="8" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
          </svg>
          <div class="absolute inset-0 flex items-center justify-center flex-col text-[12px] font-black text-black leading-none bg-white/20 rounded-full m-8">
            <span class="text-black">DOKUMEN</span>
            <span class="text-black">68%</span>
          </div>
        </div>

        <div class="flex-1 space-y-3 w-full">
          <div class="bg-white p-3 rounded-xl flex justify-between items-center shadow-sm">
            <span class="font-bold text-black uppercase tracking-tight">Total Dokumen :</span>
            <span class="font-black text-black">9 Item</span>
          </div>
          <div class="bg-white p-3 rounded-xl flex justify-between items-center shadow-sm">
            <span class="font-bold text-black uppercase tracking-tight">Total Foto :</span>
            <span class="font-black text-black">10 Item</span>
          </div>
          <div class="bg-white p-3 rounded-xl flex justify-between items-center shadow-sm">
            <span class="font-bold text-black uppercase tracking-tight">Total Video :</span>
            <span class="font-black text-black">6 Item</span>
          </div>
          <div class="bg-white p-3 rounded-xl flex justify-between items-center shadow-sm">
            <span class="font-bold text-black uppercase tracking-tight">Total Audio :</span>
            <span class="font-black text-black">8 Item</span>
          </div>
        </div>
      </div>

      <div class="flex flex-col gap-4">
        <div class="bg-[#6a97ac] p-4 rounded-2xl flex items-center justify-between shadow-sm">
          <div>
            <div class="bg-white text-[10px] px-2 py-0.5 rounded w-fit mb-1 font-black text-black">3</div>
            <p class="text-sm font-bold text-black">Total Unduhan</p>
          </div>
          <div class="bg-white p-2 rounded-lg text-[#6a97ac]">
            <Download class="w-6 h-6 text-black" />
          </div>
        </div>
        <div class="bg-[#6a97ac] p-4 rounded-2xl flex items-center justify-between shadow-sm">
          <div>
            <div class="bg-white text-[10px] px-2 py-0.5 rounded w-fit mb-1 font-black text-black">12</div>
            <p class="text-sm font-bold text-black">Total Dilihat</p>
          </div>
          <div class="bg-white p-2 rounded-lg text-[#6a97ac]">
            <Eye class="w-6 h-6 text-black" />
          </div>
        </div>
        <div class="bg-[#6a97ac] p-4 rounded-2xl flex items-center justify-between shadow-sm">
          <div>
            <div class="bg-white text-[10px] px-2 py-0.5 rounded w-fit mb-1 font-black text-black">15</div>
            <p class="text-sm font-bold text-black">Total Favorit</p>
          </div>
          <div class="bg-white p-2 rounded-lg text-[#6a97ac]">
            <Star class="w-6 h-6 text-black" />
          </div>
        </div>
      </div>
    </div>

    <section>
      <div class="inline-block bg-[#2c5d73] text-white px-6 py-2 rounded-lg mb-6 font-bold tracking-wide">
        Akses Cepat
      </div>
      <div class="grid md:grid-cols-3 gap-6">
        <div class="bg-[#6a97ac] p-6 rounded-2xl shadow-sm flex flex-col items-center">
          <div class="bg-white p-4 rounded-2xl mb-4 shadow-inner">
            <FileText class="w-12 h-12 text-black" />
          </div>
          <div class="bg-red-400 w-full py-2 rounded-lg text-center font-black text-sm mb-2 text-black uppercase">Peraturan Daerah</div>
          <div class="bg-gray-300 w-3/4 py-1 rounded-full text-center text-[10px] font-black text-black">9 DOKUMEN</div>
        </div>
        
        <div class="bg-[#6a97ac] p-6 rounded-2xl shadow-sm flex flex-col items-center relative">
          <MoreHorizontal class="absolute top-4 right-4 text-black cursor-pointer" />
          <div class="bg-white p-4 rounded-2xl mb-4 shadow-inner">
            <ImageIcon class="w-12 h-12 text-black" />
          </div>
          <div class="bg-blue-400 w-full py-2 rounded-lg text-center font-black text-sm mb-2 text-black uppercase">Galeri Foto</div>
          <div class="bg-gray-300 w-3/4 py-1 rounded-full text-center text-[10px] font-black text-black">10 FOTO</div>
        </div>

        <div class="bg-[#6a97ac] p-6 rounded-2xl shadow-sm flex flex-col items-center relative">
          <MoreHorizontal class="absolute top-4 right-4 text-black cursor-pointer" />
          <div class="bg-white p-4 rounded-2xl mb-4 shadow-inner">
            <Video class="w-12 h-12 text-black" />
          </div>
          <div class="bg-yellow-400 w-full py-2 rounded-lg text-center font-black text-sm mb-2 text-black uppercase">Galeri Video</div>
          <div class="bg-gray-300 w-3/4 py-1 rounded-full text-center text-[10px] font-black text-black">6 VIDEO</div>
        </div>
      </div>
    </section>

    <section>
      <div class="inline-block bg-[#2c5d73] text-white px-6 py-2 rounded-t-xl font-bold tracking-wide">
        Pengguna Teraktif
      </div>
      <div class="bg-[#6a97ac] rounded-b-2xl rounded-tr-2xl overflow-hidden shadow-lg border border-white/20">
        <div class="overflow-x-auto">
          <table class="w-full text-left text-sm">
            <thead class="bg-[#2c5d73]/50 uppercase text-[11px] font-black tracking-widest border-b border-white/20">
              <tr>
                <th class="px-6 py-4 text-black">Pengguna</th>
                <th class="px-6 py-4 text-black">Role</th>
                <th class="px-6 py-4 text-black">Status</th>
                <th class="px-6 py-4 text-black">Bergabung</th>
                <th class="px-6 py-4 text-black">Action</th>
                <th class="px-6 py-4 text-black">Dokumen Terkait</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-white/10 bg-[#6a97ac]/50">
              <tr v-for="user in activeUsers" :key="user.id" class="hover:bg-white/20 transition-colors">
                <td class="px-6 py-4">
                  <div class="font-black text-black">{{ user.name }}</div>
                  <div class="text-[10px] font-bold text-black/70">{{ user.email }}</div>
                </td>
                <td class="px-6 py-4 font-black uppercase text-[11px] text-black">{{ user.role }}</td>
                <td class="px-6 py-4">
                  <span class="bg-blue-200 text-black px-3 py-0.5 rounded text-[10px] font-black uppercase">{{ user.status }}</span>
                </td>
                <td class="px-6 py-4 text-xs font-bold text-black">{{ user.joined }}</td>
                <td class="px-6 py-4">
                  <div class="flex gap-2">
                    <button class="p-1.5 bg-red-600 rounded hover:bg-red-700 transition shadow">
                      <Trash2 class="w-4 h-4 text-white" />
                    </button>
                    <button class="p-1.5 bg-blue-800 rounded hover:bg-blue-900 transition shadow">
                      <Eye class="w-4 h-4 text-white" />
                    </button>
                  </div>
                </td>
                <td class="px-6 py-4 text-[11px] max-w-[250px] font-bold text-black leading-tight italic">
                  {{ user.docs }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </section>

  </div>
</template>

<style scoped>
thead {
  backdrop-filter: blur(8px);
}
</style>