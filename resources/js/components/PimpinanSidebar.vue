<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { Home, BarChart3, Clock, LogOut, Folder } from 'lucide-vue-next'
import { computed } from 'vue' 


const props = defineProps({
    user: Object
})

const page = usePage()


const user = computed(() => props.user || page.props.auth?.user)
</script>

<template>
  <aside class="w-64 min-h-screen bg-gradient-to-b from-[#dbe3e7] to-[#2f6f7e] p-4 flex flex-col justify-between">

    <div>
      <Link href="/edit-profile" class="flex items-center gap-3 mb-6">
        <div class="w-12 h-12 flex items-center justify-center rounded-full bg-[#b7d3d8] overflow-hidden border-2 border-white shadow-sm">
          <img 
            v-if="user?.photo" 
            :src="`/storage/${user.photo}`" 
            class="w-full h-full object-cover"
          />
          <span v-else class="text-xl">👤</span>
        </div>
        <div>
          <p class="text-sm font-semibold text-gray-800">{{ user?.name || 'Pimpinan' }}</p>
          <p class="text-xs text-gray-600">{{ user?.email }}</p>
        </div>
      </Link>

      <hr class="mb-4 border-gray-400" />

      
      <div class="space-y-3">

        <Link href="/pimpinan/dashboard"
          :class="page.url === '/pimpinan/dashboard'
            ? 'bg-[#2f4fa2] text-white'
            : 'bg-gray-200 text-gray-800'"
          class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all">

          <span class="w-10 h-10 flex items-center justify-center rounded-xl bg-white">
            <Home class="w-5 h-5 text-[#2f4fa2]" />
          </span>

          <span class="text-sm font-medium">Dashboard</span>
        </Link>

        <Link href="/pimpinan/statistik"
          :class="page.url.startsWith('/pimpinan/statistik')
            ? 'bg-[#2f4fa2] text-white'
            : 'bg-gray-200 text-gray-800'"
          class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all">

          <span class="w-10 h-10 flex items-center justify-center rounded-xl bg-white">
            <BarChart3 class="w-5 h-5 text-gray-700" />
          </span>

          <span class="text-sm font-medium">Statistik</span>
        </Link>

       <Link href="/pimpinan/riwayat"
  :class="page.url.startsWith('/pimpinan/riwayat')
    ? 'bg-[#2f4fa2] text-white'
    : 'bg-gray-200 text-gray-800'"
  class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all">

  <span class="w-10 h-10 flex items-center justify-center rounded-xl bg-white">
    <Clock class="w-5 h-5 text-gray-700" />
  </span>

  <span class="text-sm font-medium">Riwayat</span>
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

      <span class="w-10 h-10 flex items-center justify-center rounded-xl bg-white">
        <LogOut class="w-5 h-5 text-red-600" />
      </span>

      <span class="text-sm font-semibold text-gray-800">Keluar</span>
    </Link>

  </aside>
</template>