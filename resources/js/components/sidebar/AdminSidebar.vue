<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3'
import { Home, BarChart3, Upload, FileText, Users, Folder, CheckCircle, History, Trash2, Megaphone, LogOut} from 'lucide-vue-next'
import { computed } from 'vue'


const props = defineProps({
    user: Object
})

const page = usePage()


const user = computed(() => props.user || page.props.auth?.user)
</script>

<template>
  <aside class="w-full min-h-screen overflow-x-hidden bg-gradient-to-b from-[#dbe3e7] to-[#2f6f7e] p-3 md:p-4 flex flex-col justify-between">
    <div class="w-full overflow-x-hidden">
      <Link href="/edit-profile" class="flex items-center gap-3 mb-6">
        <div class="w-12 h-12 flex items-center justify-center rounded-full bg-[#b7d3d8] overflow-hidden border-2 border-white shadow-sm">
          <img 
            v-if="user?.photo" 
            :src="`/storage/${user.photo}`" 
            class="w-full h-full object-cover"
          />
          <span v-else class="text-xl">👤</span>
        </div>
        <div class="min-w-0">
          <p class="text-sm font-semibold text-gray-800 truncate">{{ user?.name || 'Super Admin' }}</p>
          <p class="text-xs text-gray-600 truncate">{{ user?.email }}</p>
        </div>
      </Link>

      <hr class="mb-4 border-gray-400" />

      <div class="space-y-3">

        <!-- DASHBOARD -->
        <Link href="/admin/dashboard"
          :class="page.url === '/admin/dashboard'
            ? 'bg-[#2f4fa2] text-white shadow-md'
            : 'bg-gray-200 text-gray-800'"
          class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all w-full overflow-hidden">
          <span class="w-10 h-10 shrink-0 flex items-center justify-center rounded-xl bg-white">
            <Home :class="page.url === '/admin/dashboard' ? 'text-[#2f4fa2]' : 'text-gray-700'" class="w-5 h-5" />
          </span>
          <span class="text-sm font-medium">Dashboard</span>
        </Link>

        <!-- STATISTIK -->
        <Link href="/admin/statistik"
          :class="page.url.startsWith('/admin/statistik')
            ? 'bg-[#2f4fa2] text-white shadow-md'
            : 'bg-gray-200 text-gray-800'"
          class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all w-full overflow-hidden">
          <span class="w-10 h-10 shrink-0 flex items-center justify-center rounded-xl bg-white">
            <BarChart3 :class="page.url.startsWith('/admin/statistik') ? 'text-[#2f4fa2]' : 'text-gray-700'" class="w-5 h-5" />
          </span>
          <span class="text-sm font-medium">Statistik</span>
        </Link>

        <!-- UNGGAH -->
      <Link href="/admin/UnggahAdmin"
  :class="
    page.url.startsWith('/admin/UnggahAdmin') ||
    page.url.startsWith('/admin/unggah')
      ? 'bg-[#2f4fa2] text-white shadow-md'
      : 'bg-gray-200 text-gray-800'
  "
  class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all w-full overflow-hidden"
>
  <span class="w-10 h-10 shrink-0 flex items-center justify-center rounded-xl bg-white">
    <Upload
      :class="
        page.url.startsWith('/admin/UnggahAdmin') ||
        page.url.startsWith('/admin/unggah')
          ? 'text-[#2f4fa2]'
          : 'text-gray-700'
      "
      class="w-5 h-5"
    />
  </span>

  <span class="text-sm font-medium">Unggah</span>
</Link>

        <!-- KELOLA ARSIP SAYA -->
        <Link href="/admin/kelola-arsip-role-admin"
          :class="
            page.url.startsWith('/admin/kelola-arsip-role-admin') ||
            page.url.startsWith('/admin/edit-dokumen')
              ? 'bg-[#2f4fa2] text-white shadow-md'
              : 'bg-gray-200 text-gray-800'
          "
          class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all w-full overflow-hidden"
        >
          <span class="w-10 h-10 shrink-0 flex items-center justify-center rounded-xl bg-white">
            <FileText 
              :class="
                page.url.startsWith('/admin/kelola-arsip-role-admin') ||
                page.url.startsWith('/admin/edit-dokumen')
                  ? 'text-[#2f4fa2]'
                  : 'text-gray-700'
              "
              class="w-5 h-5"
            />
          </span>

          <span class="text-sm font-medium">Kelola Arsip Saya</span>
        </Link>

        <!-- KELOLA ARSIP USER -->
        <Link href="/admin/kelola-arsip-user"
          :class="page.url.startsWith('/admin/kelola-arsip-user')
            ? 'bg-[#2f4fa2] text-white shadow-md'
            : 'bg-gray-200 text-gray-800'"
          class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all w-full overflow-hidden">
          <span class="w-10 h-10 shrink-0 flex items-center justify-center rounded-xl bg-white">
            <Users :class="page.url.startsWith('/admin/kelola-arsip-user') ? 'text-[#2f4fa2]' : 'text-gray-700'" class="w-5 h-5" />
          </span>
          <span class="text-sm font-medium">Kelola Arsip User</span>
        </Link>

        <!-- KATEGORI -->
        <Link href="/admin/kelola-kategori"
          :class="page.url.startsWith('/admin/kelola-kategori')
            ? 'bg-[#2f4fa2] text-white shadow-md'
            : 'bg-gray-200 text-gray-800'"
          class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all w-full overflow-hidden">

          <span class="w-10 h-10 shrink-0 flex items-center justify-center rounded-xl bg-white">
            <Folder 
              :class="page.url.startsWith('/admin/kelola-kategori') 
                ? 'text-[#2f4fa2]' 
                : 'text-gray-700'" 
              class="w-5 h-5" 
            />
          </span>

          <span class="text-sm font-medium">Kelola Kategori</span>
        </Link>

        <!-- PERSETUJUAN -->
        <Link href="/admin/persetujuan"
          :class="page.url.startsWith('/admin/persetujuan')
            ? 'bg-[#2f4fa2] text-white shadow-md'
            : 'bg-gray-200 text-gray-800'"
          class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all w-full overflow-hidden">
          <span class="w-10 h-10 shrink-0 flex items-center justify-center rounded-xl bg-white">
            <CheckCircle :class="page.url.startsWith('/admin/persetujuan') ? 'text-[#2f4fa2]' : 'text-gray-700'" class="w-5 h-5" />
          </span>
          <span class="text-sm font-medium">Persetujuan Akses</span>
        </Link>

        <!-- RIWAYAT -->
        <Link href="/admin/riwayat"
          :class="page.url.startsWith('/admin/riwayat')
            ? 'bg-[#2f4fa2] text-white shadow-md'
            : 'bg-gray-200 text-gray-800'"
          class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all w-full overflow-hidden">
          <span class="w-10 h-10 shrink-0 flex items-center justify-center rounded-xl bg-white">
            <History :class="page.url.startsWith('/admin/riwayat') ? 'text-[#2f4fa2]' : 'text-gray-700'" class="w-5 h-5" />
          </span>
          <span class="text-sm font-medium">Riwayat</span>
        </Link>

        <!-- SAMPAH -->
        <Link href="/admin/sampah-admin"
          :class="page.url.startsWith('/admin/sampah-admin')
            ? 'bg-[#2f4fa2] text-white shadow-md'
            : 'bg-gray-200 text-gray-800'"
          class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all w-full overflow-hidden">

          <span class="w-10 h-10 shrink-0 flex items-center justify-center rounded-xl bg-white">
            <Trash2 
              :class="page.url.startsWith('/admin/sampah-admin') 
                ? 'text-[#2f4fa2]' 
                : 'text-gray-700'" 
              class="w-5 h-5" 
            />
          </span>

          <span class="text-sm font-medium">Sampah</span>
        </Link>

        <!-- PENGUMUMAN -->
        <Link href="/admin/pengumuman"
          :class="page.url.startsWith('/admin/pengumuman')
            ? 'bg-[#2f4fa2] text-white shadow-md'
            : 'bg-gray-200 text-gray-800'"
          class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all w-full overflow-hidden">
          <span class="w-10 h-10 shrink-0 flex items-center justify-center rounded-xl bg-white">
            <Megaphone :class="page.url.startsWith('/admin/pengumuman') ? 'text-[#2f4fa2]' : 'text-gray-700'" class="w-5 h-5" />
          </span>
          <span class="text-sm font-medium">Pengumuman</span>
        </Link>

      </div>
    </div>

    <!-- LOGOUT -->
    <div class="mt-6">
      <Link href="/logout" method="post" as="button"
        class="flex items-center gap-3 bg-gray-200 px-3 py-2 rounded-lg w-full text-left hover:bg-gray-300 transition-all group">

        <span class="w-10 h-10 shrink-0 flex items-center justify-center rounded-xl bg-white">
          <LogOut class="w-5 h-5 text-red-600" />
        </span>

        <span class="text-sm font-semibold text-gray-800">
          Keluar
        </span>
      </Link>
    </div>

  </aside>
</template>