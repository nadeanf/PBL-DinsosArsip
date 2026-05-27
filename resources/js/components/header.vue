<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3'
import { Menu } from 'lucide-vue-next'
import { ref } from 'vue'

const page = usePage()
const user = page.props.auth?.user
const mobileMenuOpen = ref(false)
</script>

<template>
  <nav class="h-16 md:h-20 bg-blue-900 text-white shadow flex items-center justify-between px-4 md:px-8">
    <!-- Logo Section -->
    <div class="flex items-center gap-2 md:gap-3">
      <img src="/image/logodinsos.png" alt="Logo" class="w-8 h-10 md:w-10 md:h-12" />
      
      <div class="leading-tight hidden sm:block">
        <h1 class="text-sm md:text-lg font-bold leading-tight">
          Dinas Sosial Boyolali
        </h1>
        <p class="text-xs md:text-sm opacity-80">
          Sistem Arsip Digital
        </p>
      </div>
    </div>

    <!-- Auth Links - Desktop -->
    <div class="hidden md:flex gap-3 items-center">
      <!-- kalau BELUM login -->
      <template v-if="!user">
        <Link 
          href="/login" 
          class="px-4 md:px-5 py-2 bg-white text-blue-900 rounded-lg text-sm font-semibold hover:bg-gray-100 transition-colors"
        >
          Masuk
        </Link>

        <Link 
          href="/register" 
          class="px-4 md:px-5 py-2 bg-blue-700 border border-white/30 rounded-lg text-sm font-semibold hover:bg-blue-600 transition-colors"
        >
          Daftar
        </Link>
      </template>
    </div>

    <!-- Mobile Menu Button -->
    <button
      v-if="!user"
      @click="mobileMenuOpen = !mobileMenuOpen"
      class="md:hidden p-2 hover:bg-blue-800 rounded-lg transition-colors"
    >
      <Menu class="w-5 h-5" />
    </button>

    <!-- Mobile Menu - Dropdown -->
    <div
      v-if="mobileMenuOpen && !user"
      class="absolute right-4 top-16 bg-blue-800 rounded-lg shadow-lg z-50 space-y-2 p-3 min-w-max"
    >
      <Link 
        href="/login" 
        class="block px-4 py-2 bg-white text-blue-900 rounded-lg text-sm font-semibold hover:bg-gray-100 transition-colors text-center"
      >
        Masuk
      </Link>

      <Link 
        href="/register" 
        class="block px-4 py-2 bg-blue-700 border border-white/30 rounded-lg text-sm font-semibold hover:bg-blue-600 transition-colors text-center"
      >
        Daftar
      </Link>
    </div>

  </nav>
</template>