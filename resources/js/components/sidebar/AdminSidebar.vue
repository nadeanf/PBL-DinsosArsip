<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import {
  Home,
  BarChart3,
  Upload,
  FileText,
  Users,
  Folder,
  CheckCircle,
  History,
  Megaphone,
  LogOut
} from 'lucide-vue-next'

const menu = [
  { name: 'Dashboard', icon: Home, href: '/admin/dashboard' },
  { name: 'Statistik', icon: BarChart3, href: '/admin/statistik' }, 
  { name: 'Unggah', icon: Upload, href: '/admin/unggah' },
  { name: 'Kelola Arsip Saya', icon: FileText, href: '/admin/arsip' },
  { name: 'Kelola Arsip User', icon: Users, href: '/admin/arsip-user' },
  { name: 'Kelola Kategori', icon: Folder, href: '/kategori' },
  { name: 'Persetujuan Akses', icon: CheckCircle, href: '/admin/persetujuan' },
  { name: 'Lihat Riwayat', icon: History, href: '/admin/riwayat' },
  { name: 'Pengumuman', icon: Megaphone, href: '/pengumuman' },
]
</script>

<template>
  <aside class="w-[260px] min-h-screen bg-gradient-to-b from-[#8db1c9] to-[#2c52a7] text-white flex flex-col">

    <!-- PROFILE -->
    <div class="p-6 border-b border-white/30">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-white/30 flex items-center justify-center">
          <span class="text-xl">👤</span>
        </div>
        <div>
          <p class="text-sm font-bold">Admin Arsip</p>
          <p class="text-xs opacity-70">Administrator</p>
        </div>
      </div>
    </div>

    <!-- MENU -->
    <nav class="flex-1 p-4 space-y-2">
      <Link
        v-for="item in menu"
        :key="item.name"
        :href="item.href"
        class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm font-semibold bg-white/20 hover:bg-white/40 transition"
      >
        <component :is="item.icon" class="w-5 h-5" />
        {{ item.name }}
      </Link>
    </nav>

    <!-- STORAGE -->
    <div class="p-4">
      <div class="bg-white/30 p-3 rounded-lg text-xs">
        <p class="font-bold mb-1">Penyimpanan</p>
        <p>XX GB / XX GB (xx%)</p>
      </div>
    </div>

    <!-- LOGOUT -->
    <div class="p-4">
      <button
        class="w-full flex items-center justify-center gap-2 bg-white text-[#2c52a7] font-bold py-2 rounded-lg hover:bg-gray-100 transition"
      >
        <LogOut class="w-4 h-4" />
        Keluar
      </button>
    </div>

  </aside>
</template>