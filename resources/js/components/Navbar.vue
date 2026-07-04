<script setup>
import { usePage } from '@inertiajs/vue3'
import { computed } from 'vue'
import { Menu, X } from 'lucide-vue-next'
import { ref } from 'vue'

const page = usePage()
const emit = defineEmits(['toggle-sidebar'])
const mobileMenuOpen = ref(false)

// Menggunakan computed agar reaktif saat pindah halaman
const title = computed(() => page.props.title || '')

const toggleSidebar = () => {
  emit('toggle-sidebar')
  mobileMenuOpen.value = !mobileMenuOpen.value
}
</script>

<template>
  <header class="h-16 md:h-16 bg-blue-900 text-white shadow flex items-center px-4 md:px-6 relative">
    
    <!-- Mobile Menu Button -->
    <button
      @click="toggleSidebar"
      class="md:hidden p-2 hover:bg-blue-800 rounded-lg transition-colors mr-2"
      aria-label="Toggle menu"
    >
      <Menu v-if="!mobileMenuOpen" class="w-5 h-5" />
      <X v-else class="w-5 h-5" />
    </button>

    <!-- Logo and Title -->
    <div class="flex items-center gap-2 md:gap-3">
      <img src="/image/logodinsos.png" alt="Logo" class="w-8 h-10 md:w-10 md:h-12" />

      <div class="leading-tight hidden sm:block">
        <p class="text-sm md:text-lg font-bold">Dinas Sosial Boyolali</p>
        <p class="text-xs md:text-sm opacity-80">Sistem Digital Arsip dan Layanan Informasi Dinas Sosial Boyolali</p>
      </div>
    </div>

    <!-- Page Title - Hidden on small screens -->
    <div class="absolute inset-0 flex items-center justify-center pointer-events-none hidden md:flex">
      <p class="text-lg md:text-xl font-semibold uppercase tracking-wider text-white truncate">
        {{ title }}
      </p>
    </div>

  </header>
</template>