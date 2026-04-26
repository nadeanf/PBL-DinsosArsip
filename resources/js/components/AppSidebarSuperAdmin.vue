<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { LayoutDashboard, BarChart3, Users, History, Settings, LogOut, ShieldCheck } from 'lucide-vue-next'

const page = usePage()
const user = page.props.auth?.user
</script>

<template>
  <aside class="w-64 min-h-screen bg-gradient-to-b from-[#dbe3e7] to-[#2f6f7e] p-4 flex flex-col justify-between">

    <div>
      <Link href="/edit-profile" class="flex items-center gap-3 mb-6">
        <div class="w-12 h-12 flex items-center justify-center rounded-full bg-[#b7d3d8]">
          <ShieldCheck class="w-6 h-6 text-[#2f4fa2]" />
        </div>
        <div>
          <p class="text-sm font-semibold text-gray-800">Super Admin</p>
          <p class="text-[10px] text-gray-600 truncate w-32">{{ user?.email || 'admin@dinsos.go.id' }}</p>
        </div>
      </Link>

      <hr class="mb-4 border-gray-400" />

      <div class="space-y-3">

        <Link href="/super-admin/dashboard"
          :class="page.url === '/super-admin/dashboard'
            ? 'bg-[#2f4fa2] text-white shadow-md'
            : 'bg-gray-200 text-gray-800'"
          class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all">
          <span class="w-10 h-10 flex items-center justify-center rounded-xl bg-white">
            <LayoutDashboard :class="page.url === '/super-admin/dashboard' ? 'text-[#2f4fa2]' : 'text-gray-700'" class="w-5 h-5" />
          </span>
          <span class="text-sm font-medium">Dashboard</span>
        </Link>

        <Link href="/super-admin/statistik"
          :class="page.url.startsWith('/super-admin/statistik')
            ? 'bg-[#2f4fa2] text-white shadow-md'
            : 'bg-gray-200 text-gray-800'"
          class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all">
          <span class="w-10 h-10 flex items-center justify-center rounded-xl bg-white">
            <BarChart3 :class="page.url.startsWith('/super-admin/statistik') ? 'text-[#2f4fa2]' : 'text-gray-700'" class="w-5 h-5" />
          </span>
          <span class="text-sm font-medium">Statistik</span>
        </Link>

        <Link href="/super-admin/kelolauser"
          :class="page.url.startsWith('/super-admin/kelolauser')
            ? 'bg-[#2f4fa2] text-white shadow-md'
            : 'bg-gray-200 text-gray-800'"
          class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all">
          <span class="w-10 h-10 flex items-center justify-center rounded-xl bg-white">
            <Users :class="page.url.startsWith('/super-admin/kelolauser') ? 'text-[#2f4fa2]' : 'text-gray-700'" class="w-5 h-5" />
          </span>
          <span class="text-sm font-medium">Kelola User</span>
        </Link>

        <Link href="/super-admin/riwayat"
          :class="page.url.startsWith('/super-admin/riwayat')
            ? 'bg-[#2f4fa2] text-white shadow-md'
            : 'bg-gray-200 text-gray-800'"
          class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all">
          <span class="w-10 h-10 flex items-center justify-center rounded-xl bg-white">
            <History :class="page.url.startsWith('/super-admin/riwayat') ? 'text-[#2f4fa2]' : 'text-gray-700'" class="w-5 h-5" />
          </span>
          <span class="text-sm font-medium">Riwayat</span>
        </Link>

        <Link href="/super-admin/pengaturan"
          :class="page.url.startsWith('/super-admin/pengaturan')
            ? 'bg-[#2f4fa2] text-white shadow-md'
            : 'bg-gray-200 text-gray-800'"
          class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all">
          <span class="w-10 h-10 flex items-center justify-center rounded-xl bg-white">
            <Settings :class="page.url.startsWith('/super-admin/pengaturan') ? 'text-[#2f4fa2]' : 'text-gray-700'" class="w-5 h-5" />
          </span>
          <span class="text-sm font-medium">Pengaturan</span>
        </Link>

      </div>

      <div class="mt-8 bg-white/70 p-4 rounded-xl text-xs shadow-inner">
        <p class="mb-3 font-semibold text-gray-800">Penyimpanan</p>
        <div class="w-full bg-gray-300 h-2.5 rounded-full overflow-hidden">
          <div class="bg-[#2f4fa2] h-full w-1/3 rounded-full"></div>
        </div>
        <p class="mt-2.5 text-[10px] text-gray-700">xx GB / xx GB (xx%)</p>
      </div>
    </div>

    <Link href="/logout" method="post" as="button"
      class="flex items-center gap-3 bg-gray-200 px-3 py-2 rounded-lg w-full text-left hover:bg-gray-300 transition-all group">
      <span class="w-10 h-10 flex items-center justify-center rounded-xl bg-white shadow-sm group-hover:bg-red-50">
        <LogOut class="w-5 h-5 text-red-600" />
      </span>
      <span class="text-sm font-semibold text-gray-800">Keluar</span>
    </Link>

  </aside>
</template>