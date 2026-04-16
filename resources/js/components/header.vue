<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3'

const page = usePage()
const user = page.props.auth?.user
</script>

<template>
  <nav class="h-20 bg-blue-900 text-white shadow flex items-center justify-between px-8">
    
    <!-- KIRI -->
    <div class="flex items-center gap-3">
      <img src="image/logodinsos.png" alt="Logo" class="w-10 h-12" />
      
      <div>
        <h1 class="text-lg font-bold leading-tight">
          Dinas Sosial Boyolali
        </h1>
        <p class="text-sm opacity-80">
          Sistem Arsip Digital
        </p>
      </div>
    </div>

    <!-- KANAN -->
    <div class="flex gap-3 items-center">
      
      <!-- kalau BELUM login -->
      <template v-if="!user">
        <Link 
          href="/login" 
          class="px-5 py-2 bg-white text-blue-900 rounded-lg text-sm font-semibold hover:bg-gray-100"
        >
          Masuk
        </Link>

        <Link 
          href="/register" 
          class="px-5 py-2 bg-blue-700 border border-white/30 rounded-lg text-sm font-semibold hover:bg-blue-600"
        >
          Daftar
        </Link>
      </template>

      <!-- kalau SUDAH login -->
      <template v-else>
  <div class="flex items-center gap-3">
    
    <Link 
      href="/dashboard"
      class="px-5 py-2 bg-white text-blue-900 rounded-lg text-sm font-semibold hover:bg-gray-100"
    >
      Dashboard
    </Link>

    <Link 
      href="/logout" 
      method="post" 
      as="button"
      class="px-5 py-2 bg-red-500 rounded-lg text-sm font-semibold hover:bg-red-400"
    >
      Logout
    </Link>

  </div>
</template>

    </div>

  </nav>
</template>