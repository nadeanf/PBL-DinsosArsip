<script setup>
import { usePage } from '@inertiajs/vue3'
import AuthLayout from '@/layouts/AuthLayout.vue'
import DetailArsipContent from '@/components/arsip/DetailArsipContent.vue'

defineOptions({
  layout: AuthLayout
})

const { props } = usePage()

const doc = props.doc ?? {
  title: 'Nama File',
  jenis: 'PDF',
  tanggal: '01 Januari 2026',
  akses: 'Public'
}
</script>

<template>
  <DetailArsipContent :doc="doc" />
</template>