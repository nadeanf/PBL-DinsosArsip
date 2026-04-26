<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import AdminLayout from '@/layouts/AdminLayout.vue'
import { Search, Filter } from 'lucide-vue-next'

defineOptions({
  layout: AdminLayout
})

// Dummy 
const data = [
  { dokumen: '422/07', user: 'adib', tanggal: '24/08/26', divisi: 'Bendahara', status: 'pending' },
  { dokumen: '422/07', user: 'adib', tanggal: '24/08/26', divisi: 'Bendahara', status: 'approved' },
  { dokumen: '422/07', user: 'adib', tanggal: '24/08/26', divisi: 'Bendahara', status: 'rejected' },
  { dokumen: '422/07', user: 'adib', tanggal: '24/08/26', divisi: 'Bendahara', status: 'pending' },
  { dokumen: '422/07', user: 'adib', tanggal: '24/08/26', divisi: 'Bendahara', status: 'approved' },
  { dokumen: '422/07', user: 'adib', tanggal: '24/08/26', divisi: 'Bendahara', status: 'approved' },
  { dokumen: '422/07', user: 'adib', tanggal: '24/08/26', divisi: 'Bendahara', status: 'rejected' },
]

// Helper warna status
const statusClass = (status: string) => {
  if (status === 'pending') return 'bg-yellow-300 text-black'
  if (status === 'approved') return 'bg-green-400 text-black'
  if (status === 'rejected') return 'bg-red-400 text-black'
}
</script>

<template>
  <Head title="Persetujuan Akses" />

  <div class="p-6 bg-gray-100 min-h-screen">

    <!-- TITLE -->
    <h1 class="text-2xl font-bold text-gray-800 mb-6">
      Persetujuan Akses
    </h1>

    <!-- SEARCH + FILTER -->
    <div class="flex justify-between items-center mb-6">
      
      <div class="relative w-[400px]">
        <Search class="absolute left-3 top-3 w-5 h-5 text-gray-500" />
        <input
          type="text"
          placeholder="Cari..."
          class="w-full pl-10 pr-4 py-2 rounded-lg border shadow-sm focus:outline-none"
        />
      </div>

      <button class="flex items-center gap-2 text-gray-700 font-semibold">
        <Filter class="w-5 h-5" />
        Filter
      </button>

    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
      
      <!-- HEADER -->
      <div class="grid grid-cols-5 bg-[#2c52a7] text-white font-semibold text-sm px-6 py-3">
        <div>Dokumen</div>
        <div>User</div>
        <div>Tanggal</div>
        <div>Divisi</div>
        <div>Persetujuan</div>
      </div>

      <!-- DATA -->
      <div
        v-for="(item, index) in data"
        :key="index"
        class="grid grid-cols-5 px-6 py-4 border-b text-gray-700 text-sm items-center"
      >
        <div>{{ item.dokumen }}</div>
        <div>{{ item.user }}</div>
        <div>{{ item.tanggal }}</div>
        <div>{{ item.divisi }}</div>

        <div>
          <span
            class="px-3 py-1 rounded-full text-xs font-bold"
            :class="statusClass(item.status)"
          >
            {{ item.status === 'pending' ? 'Pending' :
               item.status === 'approved' ? 'Disetujui' :
               'Ditolak' }}
          </span>
        </div>
      </div>

    </div>

  </div>
</template>