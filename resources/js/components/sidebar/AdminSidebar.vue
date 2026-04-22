<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3'
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

const page = usePage()
</script>

<template>
  <aside class="w-64 min-h-screen bg-gradient-to-b from-[#dbe3e7] to-[#2f6f7e] p-4 flex flex-col justify-between">

    <!-- TOP -->
    <div>
      <!-- PROFILE -->
      <Link href="/edit-profile" class="flex items-center gap-3 mb-6">
        <div class="w-12 h-12 flex items-center justify-center rounded-full bg-[#b7d3d8]">
          <span class="text-xl">👤</span>
        </div>
        <div>
          <p class="text-sm font-semibold text-gray-800">Admin Arsip</p>
          <p class="text-xs text-gray-600">Administrator</p>
        </div>
      </Link>

      <hr class="mb-4 border-gray-400" />

      <div class="space-y-3">

        <!-- DASHBOARD -->
        <Link href="/admin/dashboard"
          :class="page.url === '/admin/dashboard'
            ? 'bg-[#2f4fa2] text-white shadow-md'
            : 'bg-gray-200 text-gray-800'"
          class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all">
          <span class="w-10 h-10 flex items-center justify-center rounded-xl bg-white">
            <Home :class="page.url === '/admin/dashboard' ? 'text-[#2f4fa2]' : 'text-gray-700'" class="w-5 h-5" />
          </span>
          <span class="text-sm font-medium">Dashboard</span>
        </Link>

        <!-- STATISTIK -->
        <Link href="/admin/statistik"
          :class="page.url.startsWith('/admin/statistik')
            ? 'bg-[#2f4fa2] text-white shadow-md'
            : 'bg-gray-200 text-gray-800'"
          class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all">
          <span class="w-10 h-10 flex items-center justify-center rounded-xl bg-white">
            <BarChart3 :class="page.url.startsWith('/admin/statistik') ? 'text-[#2f4fa2]' : 'text-gray-700'" class="w-5 h-5" />
          </span>
          <span class="text-sm font-medium">Statistik</span>
        </Link>

        <!-- UNGGAH -->
        <Link href="/unggah"
          :class="page.url.startsWith('/unggah')
            ? 'bg-[#2f4fa2] text-white shadow-md'
            : 'bg-gray-200 text-gray-800'"
          class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all">
          <span class="w-10 h-10 flex items-center justify-center rounded-xl bg-white">
            <Upload :class="page.url.startsWith('/unggah') ? 'text-[#2f4fa2]' : 'text-gray-700'" class="w-5 h-5" />
          </span>
          <span class="text-sm font-medium">Unggah</span>
        </Link>

        <!-- KELOLA ARSIP SAYA -->
        <Link href="/kelola-arsip"
          :class="page.url.startsWith('/kelola-arsip')
            ? 'bg-[#2f4fa2] text-white shadow-md'
            : 'bg-gray-200 text-gray-800'"
          class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all">
          <span class="w-10 h-10 flex items-center justify-center rounded-xl bg-white">
            <FileText :class="page.url.startsWith('/kelola-arsip') ? 'text-[#2f4fa2]' : 'text-gray-700'" class="w-5 h-5" />
          </span>
          <span class="text-sm font-medium">Kelola Arsip Saya</span>
        </Link>

        <!-- KELOLA ARSIP USER -->
        <Link href="/admin/kelola-user"
          :class="page.url.startsWith('/admin/kelola-user')
            ? 'bg-[#2f4fa2] text-white shadow-md'
            : 'bg-gray-200 text-gray-800'"
          class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all">
          <span class="w-10 h-10 flex items-center justify-center rounded-xl bg-white">
            <Users :class="page.url.startsWith('/admin/kelola-user') ? 'text-[#2f4fa2]' : 'text-gray-700'" class="w-5 h-5" />
          </span>
          <span class="text-sm font-medium">Kelola Arsip User</span>
        </Link>

        <!-- KATEGORI -->
        <Link href="/kategori"
          :class="page.url.startsWith('/kategori')
            ? 'bg-[#2f4fa2] text-white shadow-md'
            : 'bg-gray-200 text-gray-800'"
          class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all">
          <span class="w-10 h-10 flex items-center justify-center rounded-xl bg-white">
            <Folder :class="page.url.startsWith('/kategori') ? 'text-[#2f4fa2]' : 'text-gray-700'" class="w-5 h-5" />
          </span>
          <span class="text-sm font-medium">Kelola Kategori</span>
        </Link>

        <!-- PERSETUJUAN -->
        <Link href="/admin/persetujuan"
          :class="page.url.startsWith('/admin/persetujuan')
            ? 'bg-[#2f4fa2] text-white shadow-md'
            : 'bg-gray-200 text-gray-800'"
          class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all">
          <span class="w-10 h-10 flex items-center justify-center rounded-xl bg-white">
            <CheckCircle :class="page.url.startsWith('/admin/persetujuan') ? 'text-[#2f4fa2]' : 'text-gray-700'" class="w-5 h-5" />
          </span>
          <span class="text-sm font-medium">Persetujuan Akses</span>
        </Link>

        <!-- RIWAYAT -->
        <Link href="/riwayat"
          :class="page.url.startsWith('/riwayat')
            ? 'bg-[#2f4fa2] text-white shadow-md'
            : 'bg-gray-200 text-gray-800'"
          class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all">
          <span class="w-10 h-10 flex items-center justify-center rounded-xl bg-white">
            <History :class="page.url.startsWith('/riwayat') ? 'text-[#2f4fa2]' : 'text-gray-700'" class="w-5 h-5" />
          </span>
          <span class="text-sm font-medium">Riwayat</span>
        </Link>

        <!-- PENGUMUMAN -->
        <Link href="/admin/pengumuman"
          :class="page.url.startsWith('/admin/pengumuman')
            ? 'bg-[#2f4fa2] text-white shadow-md'
            : 'bg-gray-200 text-gray-800'"
          class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all">
          <span class="w-10 h-10 flex items-center justify-center rounded-xl bg-white">
            <Megaphone :class="page.url.startsWith('/admin/pengumuman') ? 'text-[#2f4fa2]' : 'text-gray-700'" class="w-5 h-5" />
          </span>
          <span class="text-sm font-medium">Pengumuman</span>
        </Link>

      </div>

      <!-- STORAGE -->
      <div class="mt-8 bg-white/70 p-4 rounded-xl text-xs shadow-inner">
        <p class="mb-3 font-semibold text-gray-800">Penyimpanan</p>
        <div class="w-full bg-gray-300 h-2.5 rounded-full overflow-hidden">
          <div class="bg-[#2f4fa2] h-full w-1/3 rounded-full"></div>
        </div>
        <p class="mt-2.5 text-[10px] text-gray-700">xx GB / xx GB (xx%)</p>
      </div>
    </div>

    <!-- LOGOUT -->
    <div class="mt-6">
      <Link href="/logout" method="post" as="button"
        class="flex items-center gap-3 bg-gray-200 px-3 py-2 rounded-lg w-full text-left hover:bg-gray-300 transition-all group">

        <span class="w-10 h-10 flex items-center justify-center rounded-xl bg-white">
          <LogOut class="w-5 h-5 text-red-600" />
        </span>

        <span class="text-sm font-semibold text-gray-800">
          Keluar
        </span>
      </Link>
    </div>

  </aside>
</template>