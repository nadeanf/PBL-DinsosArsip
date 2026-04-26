<script setup>
import AdminLayout from '@/layouts/AdminLayout.vue'
import RiwayatContent from '@/components/RiwayatContent.vue'
import { Head } from '@inertiajs/vue3'

defineOptions({ layout: AdminLayout })
</script>

<template>
    <Head title="Riwayat - Pimpinan" />
    <RiwayatContent />
</template>