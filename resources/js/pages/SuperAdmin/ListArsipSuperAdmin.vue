<script setup>
import SuperAdminLayout from '@/layouts/SuperAdminLayout.vue'
import ListArsipContent from '@/components/ListArsipContent.vue'
import { Head } from '@inertiajs/vue3'

defineOptions({
  layout: SuperAdminLayout
})
</script>

<template>
  <Head title="Manajemen Arsip - Super Admin" />
  
  <div class="space-y-4">
    <div class="flex flex-col border-b border-gray-200 pb-4">
      <h1 class="text-2xl font-bold text-gray-900">Manajemen Arsip Global</h1>
      <p class="text-sm text-gray-500">Kelola, verifikasi, dan pantau seluruh dokumen yang tersimpan dalam sistem.</p>
    </div>

    <ListArsipContent />
  </div>
</template>