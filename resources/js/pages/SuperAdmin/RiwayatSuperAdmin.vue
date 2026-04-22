<script setup>
import SuperAdminLayout from '@/layouts/SuperAdminLayout.vue' // ✅ Ganti ke layout Super Admin
import RiwayatContent from '@/components/RiwayatContent.vue'
import { Head } from '@inertiajs/vue3'


defineOptions({ 
    layout: SuperAdminLayout 
})
</script>

<template>
    <Head title="Riwayat - Super Admin" />
    
    <RiwayatContent />
</template>