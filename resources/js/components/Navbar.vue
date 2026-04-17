<script setup>
import { usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

const page = usePage()

// Menggunakan computed agar reaktif saat pindah halaman
const title = computed(() => page.props.title || '')
</script>

<template>
  <header class="h-20 bg-blue-900 text-white shadow flex items-center px-6 relative">
    
    <div class="flex items-center gap-3">
      <img src="/image/logodinsos.png" class="w-10 h-12" />

      <div class="leading-tight">
        <p class="text-lg font-bold">Dinas Sosial Boyolali</p>
        <p class="text-sm opacity-80">Sistem Arsip Digital</p>
      </div>
    </div>

    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
      <p class="text-xl font-semibold uppercase tracking-wider text-white">
        {{ title }}
      </p>
    </div>

    <div class="ml-auto">
        </div>

  </header>
</template>