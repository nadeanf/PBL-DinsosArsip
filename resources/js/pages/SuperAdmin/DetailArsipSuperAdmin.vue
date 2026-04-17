<script setup>
import { computed } from 'vue'
import { usePage, Head } from '@inertiajs/vue3'
import DetailArsipContent from '@/components/DetailArsipContent.vue'
import SuperAdminLayout from '@/layouts/SuperAdminLayout.vue' // Ganti ke layout Super Admin

defineOptions({
  layout: SuperAdminLayout
})

const { props } = usePage()

// Data dokumen diambil dari props yang dikirim controller
const doc = computed(() => props.doc ?? {
  title: 'Nama File',
  jenis: 'PDF',
  tanggal: '01 Januari 2026',
  status: 'Public',
  // Tambahan info untuk Super Admin jika diperlukan
  owner: 'Admin IT',
  last_modified: '10 Februari 2026'
})
</script>

<template>
  <Head :title="`Detail - ${doc.title}`" />

  <div class="space-y-4">
    <div class="flex items-center gap-2 text-xs text-gray-500 mb-2">
      <span class="hover:text-[#2f4fa2] cursor-pointer">Manajemen Arsip</span>
      <span>/</span>
      <span class="text-gray-900 font-medium">Detail Dokumen</span>
    </div>

    <DetailArsipContent :doc="doc" />
    
    <div v-if="doc.status" class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-xl">
      <h3 class="text-sm font-bold text-blue-900 mb-2">Kontrol Administrasi</h3>
      <div class="flex gap-3">
        <button class="px-4 py-2 bg-[#2f4fa2] text-white text-xs rounded-lg hover:bg-blue-800 transition">
          Ubah Hak Akses
        </button>
        <button class="px-4 py-2 bg-red-600 text-white text-xs rounded-lg hover:bg-red-700 transition">
          Hapus Permanen
        </button>
      </div>
    </div>
  </div>
</template>