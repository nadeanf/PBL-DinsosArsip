<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { Home, Upload, FileText, History, Trash2, LogOut } from 'lucide-vue-next'

const page = usePage()
const user = page.props.auth?.user

function getPhoto() {
  if (user?.photo) {
    return `/storage/${user.photo}`
  }
  return 'https://i.pravatar.cc/40'
}
</script>

<template>
  <aside class="w-64 min-h-screen bg-gradient-to-b from-[#dbe3e7] to-[#2f6f7e] p-4 flex flex-col justify-between">

    <div>
      <!-- PROFILE -->
      <Link href="/edit-profile" class="flex items-center gap-3 mb-6 group">
        
        <!-- FOTO DINAMIS -->
        <img 
          :src="getPhoto()" 
          class="w-10 h-10 rounded-full border border-gray-300 object-cover"
        />

        <div>
          <p class="text-sm font-semibold group-hover:text-gray-900 transition-colors">
            {{ user?.name || 'Guest' }}
          </p>
          <p class="text-xs text-gray-700">
            {{ user?.email || '-' }}
          </p>
        </div>
      </Link>

      <hr class="mb-4 border-gray-400" />

      <div class="space-y-3">

        <!-- DASHBOARD -->
        <Link href="/dashboard" 
          :class="page.url === '/dashboard' ? 'bg-[#2f4fa2] text-white shadow-md' : 'bg-gray-200 hover:bg-gray-300 text-gray-800'"
          class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all">
          <span class="w-10 h-10 flex items-center justify-center rounded-xl bg-white">
            <Home :class="page.url === '/dashboard' ? 'text-[#2f4fa2]' : 'text-gray-800'" class="w-5 h-5" />
          </span>
          <span class="text-sm font-medium">Beranda</span>
        </Link>

        <!-- UNGGAH -->
        <Link href="/unggah" 
          :class="page.url.startsWith('/unggah') ? 'bg-[#2f4fa2] text-white shadow-md' : 'bg-gray-200 hover:bg-gray-300 text-gray-800'"
          class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all">
          <span class="w-10 h-10 flex items-center justify-center rounded-xl bg-white">
            <Upload :class="page.url.startsWith('/unggah') ? 'text-[#2f4fa2]' : 'text-gray-800'" class="w-5 h-5" />
          </span>
          <span class="text-sm font-medium">Unggah</span>
        </Link>

        <!-- ARSIP -->
        <Link href="/kelola-arsip" 
          :class="page.url.startsWith('/kelola-arsip') ? 'bg-[#2f4fa2] text-white shadow-md' : 'bg-gray-200 hover:bg-gray-300 text-gray-800'"
          class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all">
          <span class="w-10 h-10 flex items-center justify-center rounded-xl bg-white shadow-inner">
            <FileText :class="page.url.startsWith('/kelola-arsip') ? 'text-[#2f4fa2]' : 'text-gray-800'" class="w-5 h-5" />
          </span>
          <span class="text-sm font-medium">Arsip Saya</span>
        </Link>

        <!-- RIWAYAT -->
        <Link href="/riwayat" 
          :class="page.url.startsWith('/riwayat') ? 'bg-[#2f4fa2] text-white shadow-md' : 'bg-gray-200 hover:bg-gray-300 text-gray-800'"
          class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all">
          <span class="w-10 h-10 flex items-center justify-center rounded-xl bg-white shadow-inner">
            <History :class="page.url.startsWith('/riwayat') ? 'text-[#2f4fa2]' : 'text-gray-800'" class="w-5 h-5" />
          </span>
          <span class="text-sm font-medium">Riwayat</span>
        </Link>

        <!-- SAMPAH -->
        <Link href="/sampah" 
          :class="page.url.startsWith('/sampah') ? 'bg-[#2f4fa2] text-white shadow-md' : 'bg-gray-200 hover:bg-gray-300 text-gray-800'"
          class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all">
          <span class="w-10 h-10 flex items-center justify-center rounded-xl bg-white shadow-inner">
            <Trash2 :class="page.url.startsWith('/sampah') ? 'text-[#2f4fa2]' : 'text-gray-800'" class="w-5 h-5" />
          </span>
          <span class="text-sm font-medium">Sampah</span>
        </Link>

        <!-- STORAGE -->
      <div class="mt-8 bg-white/70 p-4 rounded-xl text-xs shadow-inner">
        <p class="mb-3 font-semibold text-gray-800">Penyimpanan</p>
        <div class="w-full bg-gray-300 h-2.5 rounded-full overflow-hidden">
          <div class="bg-[#2f4fa2] h-full w-1/3 rounded-full"></div>
        </div>
        <p class="mt-2.5 text-[10px] text-gray-700">xx GB / xx GB (xx%)</p>
      </div>

      </div>
    </div>

    <!-- LOGOUT -->
    <Link href="/logout" method="post" as="button"
      class="flex items-center gap-3 bg-gray-200 px-3 py-2 rounded-lg w-full text-left hover:bg-gray-300 transition-all">
      <span class="w-10 h-10 flex items-center justify-center rounded-xl bg-white">
        <LogOut class="w-5 h-5 text-red-600" />
      </span>
      <span class="text-sm font-semibold text-red-700">Keluar</span>
    </Link>

  </aside>
</template>