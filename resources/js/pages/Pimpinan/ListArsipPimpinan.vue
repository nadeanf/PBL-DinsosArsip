<script setup>
import AuthLayoutPimpinan from '@/layouts/AuthLayoutPimpinan.vue'
import ListArsipContent from '@/components/ListArsipContent.vue'
import { usePage } from '@inertiajs/vue3'

defineOptions({
  layout: AuthLayoutPimpinan
})

const { arsip, kategori, filters } = usePage().props
</script>

<template>
  <ListArsipContent 
    :arsip="arsip"
    :kategori="kategori"
    :filters="filters"
  />
</template>