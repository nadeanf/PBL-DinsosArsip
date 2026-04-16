<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { Home, Upload, FileText, History, Trash2, LogOut } from 'lucide-vue-next'

// ambil data user dari inertia
const page = usePage()
const user = page.props.auth?.user
const isActive = (route) => computed(() => page.url.startsWith(route))
</script>

<template>
  <aside class="w-64 min-h-screen bg-gradient-to-b from-[#dbe3e7] to-[#2f6f7e] p-4 flex flex-col justify-between">

    <!-- ATAS -->
    <div>

      <!-- PROFILE (BISA DIKLIK) -->
      <Link href="/edit-profile" class="flex items-center gap-3 mb-6">
        <img
          :src="'https://i.pravatar.cc/40'"
          class="w-10 h-10 rounded-full"
        />

        <div>
          <!-- ambil nama user -->
          <p class="text-sm font-semibold">
            {{ user?.name || 'Guest' }}
          </p>

          <!-- email -->
          <p class="text-xs text-gray-700">
            {{ user?.email || '-' }}
          </p>
        </div>
      </Link>

      <hr class="mb-4 border-gray-400" />

      <!-- MENU -->
      <div class="space-y-3">

        <Link href="/dashboard" 
          :class="page.url === '/dashboard' ? 'bg-[#2f4fa2] text-white' : 'bg-gray-200 hover:bg-gray-300 text-gray-800'"
          class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all">
          <span class="w-10 h-10 flex items-center justify-center rounded-xl" :class="page.url === '/dashboard' ? 'bg-white/20' : 'bg-white'">
            <Home class="w-5 h-5" />
          </span>
          <span class="text-sm">Beranda</span>
        </Link>

        <Link href="/unggah" 
          :class="page.url.startsWith('/unggah') ? 'bg-[#2f4fa2] text-white' : 'bg-gray-200 hover:bg-gray-300 text-gray-800'"
          class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all">
          <span class="w-10 h-10 flex items-center justify-center rounded-xl" :class="page.url.startsWith('/unggah') ? 'bg-white/20' : 'bg-white'">
            <Upload class="w-5 h-5" />
          </span>
          <span class="text-sm">Unggah</span>
        </Link>

        <Link href="/kelola-arsip" 
          :class="page.url.startsWith('/kelola-arsip') ? 'bg-[#2f4fa2] text-white' : 'bg-gray-200 hover:bg-gray-300 text-gray-800'"
          class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all">
          <span class="w-10 h-10 flex items-center justify-center rounded-xl" :class="page.url.startsWith('/kelola-arsip') ? 'bg-white/20' : 'bg-white'">
            <FileText class="w-5 h-5" />
          </span>
          <span class="text-sm">Arsip Saya</span>
        </Link>

        <Link href="/riwayat" 
          :class="page.url.startsWith('/riwayat') ? 'bg-[#2f4fa2] text-white' : 'bg-gray-200 hover:bg-gray-300 text-gray-800'"
          class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all">
          <span class="w-10 h-10 flex items-center justify-center rounded-xl" :class="page.url.startsWith('/riwayat') ? 'bg-white/20' : 'bg-white'">
            <History class="w-5 h-5" />
          </span>
          <span class="text-sm">Riwayat</span>
        </Link>

        <Link href="/sampah" 
          :class="page.url.startsWith('/sampah') ? 'bg-[#2f4fa2] text-white' : 'bg-gray-200 hover:bg-gray-300 text-gray-800'"
          class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all">
          <span class="w-10 h-10 flex items-center justify-center rounded-xl" :class="page.url.startsWith('/sampah') ? 'bg-white/20' : 'bg-white'">
            <Trash2 class="w-5 h-5" />
          </span>
          <span class="text-sm">Sampah</span>
        </Link>

      </div>

      <!-- STORAGE -->
      <div class="mt-6 bg-white/70 p-3 rounded-lg text-xs">
        <p class="mb-2 font-semibold">Penyimpanan</p>

        <div class="w-full bg-gray-300 h-2 rounded-full overflow-hidden">
          <div class="bg-black h-full w-1/3"></div>
        </div>

        <p class="mt-2 text-[10px]">
          xx GB / xx GB (xx%)
        </p>
      </div>

    </div>

    <!-- LOGOUT -->
    <Link
      href="/logout"
      method="post"
      as="button"
      class="flex items-center gap-3 bg-gray-200 px-3 py-2 rounded-lg w-full text-left hover:bg-gray-300"
    >
      <span class="w-10 h-10 flex items-center justify-center rounded-xl bg-white">
        <LogOut class="w-5 h-5 text-gray-800" />
      </span>
      <span class="text-sm">Keluar</span>
    </Link>

  </aside>
</template>