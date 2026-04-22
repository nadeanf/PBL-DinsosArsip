<script setup>
import { computed } from 'vue' 
import { usePage } from '@inertiajs/vue3'
import DetailArsipContent from '@/components/DetailArsipContent.vue'
import AuthLayoutPimpinan from '@/layouts/AuthLayoutPimpinan.vue'

defineOptions({
  layout: AuthLayoutPimpinan
})

const { props } = usePage()


const doc = computed(() => props.doc ?? {
  title: 'Nama File',
  jenis: 'PDF',
  tanggal: '01 Januari 2026',
  status: 'Public' // Diubah dari 'akses' ke 'status'
})
</script>

<template>
  <DetailArsipContent :doc="doc" />
</template>