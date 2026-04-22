<script setup>
import { usePage } from '@inertiajs/vue3'
import UserLayout from '@/layouts/UserLayout.vue'
import DetailArsipContent from '@/components/DetailArsipContent.vue'

defineOptions({
  layout: UserLayout
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