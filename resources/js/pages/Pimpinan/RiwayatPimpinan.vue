<script setup>
import AuthLayoutPimpinan from '@/layouts/AuthLayoutPimpinan.vue'
import RiwayatContent from '@/components/RiwayatContent.vue'
import { Head } from '@inertiajs/vue3'

defineOptions({ layout: AuthLayoutPimpinan })
</script>

<template>
    <Head title="Riwayat - Pimpinan" />
    <RiwayatContent />
</template>