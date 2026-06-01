<script setup lang="ts">
import { Head, usePage, router } from '@inertiajs/vue3'
import AdminLayout from '@/layouts/AdminLayout.vue'
import { Search } from 'lucide-vue-next'
import { ref, watch } from 'vue'
import { computed } from 'vue'

defineOptions({
  layout: AdminLayout
})

const page = usePage()
const search = ref(page.props.filters?.search || '')
const loading = ref(false)

const doSearch = () => {
  router.get('/admin/persetujuan', {
    search: search.value
  }, {
    preserveState: true,
    replace: true
  })
}

// ambil data dari controller
const data = computed(() => page.props.requests?.data || [])
const links = computed(() => page.props.requests?.links || [])

// helper warna
const statusClass = (status: string) => {
  if (status === 'pending') return 'bg-yellow-300 text-black'
  if (status === 'approved') return 'bg-green-400 text-black'
  if (status === 'rejected') return 'bg-red-400 text-black'
}

// update status
const updateStatus = (id: number, status: string) => {
  loading.value = true
  router.post(`/admin/persetujuan/${id}`, {
    status: status
  }, {
    preserveScroll: true,
    preserveState: false,
    onFinish: () => {
      loading.value = false
    }
  })
}
</script>

<template>
  <Head title="Persetujuan Akses" />
  
  <div class="p-6 bg-gray-100 min-h-screen">

    <!-- TITLE -->
    <h1 class="text-2xl font-bold text-gray-800 mb-6">
      Persetujuan Akses
    </h1>

    <!-- SEARCH + FILTER -->
    <div class="flex justify-between items-center mb-6">
      
      <div class="relative w-[400px]">
        <Search class="absolute left-3 top-3 w-5 h-5 text-gray-500" />
      <input
  v-model="search"
  @keyup.enter="doSearch"
  type="text"
  placeholder="Cari..."
  class="w-full pl-10 pr-4 py-2 rounded-lg border shadow-sm focus:outline-none"
/>
      </div>

    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
      
      <!-- HEADER -->
      <div class="grid grid-cols-5 bg-[#2c52a7] text-white font-semibold text-sm px-6 py-3">
        <div>Dokumen</div>
        <div>User</div>
        <div>Tanggal</div>
        <div>Divisi</div>
        <div>Persetujuan</div>
      </div>

      <!-- EMPTY STATE -->
      <div v-if="data.length === 0" class="px-6 py-12 text-center text-gray-500">
        <p class="text-lg">Tidak ada permintaan akses</p>
      </div>

      <!-- DATA -->
      <template v-else>
        <div
          v-for="(item, index) in data"
          :key="item.id"
          class="grid grid-cols-5 px-6 py-4 border-b text-gray-700 text-sm items-center"
        >
          <div>{{ item.arsip?.judul }}</div>
          <div>{{ item.user?.name }}</div>
          <div>{{ item.created_at }}</div>
          <div>{{ item.user?.bagian }}</div>

          <div class="flex gap-2 items-center">
            <template v-if="item.status === 'pending'">
              <button
                @click="updateStatus(item.id, 'approved')"
                :disabled="loading"
                class="bg-green-500 hover:bg-green-600 disabled:opacity-50 disabled:cursor-not-allowed text-white px-3 py-1 rounded text-xs transition"
              >
                {{ loading ? 'Proses...' : 'Approve' }}
              </button>

              <button
                @click="updateStatus(item.id, 'rejected')"
                :disabled="loading"
                class="bg-red-500 hover:bg-red-600 disabled:opacity-50 disabled:cursor-not-allowed text-white px-3 py-1 rounded text-xs transition"
              >
                {{ loading ? 'Proses...' : 'Tolak' }}
              </button>
            </template>

            <span
              v-else
              class="px-3 py-1 rounded-full text-xs font-bold"
              :class="statusClass(item.status)"
            >
              {{ item.status === 'approved' ? 'Disetujui' : 'Ditolak' }}
            </span>
          </div>
        </div>

        <!-- PAGINATION (DI LUAR LOOP!) -->
        <div class="flex justify-center mt-6 gap-2 flex-wrap pb-4">
          <button
            v-for="link in links"
            :key="link.label"
            v-html="link.label"
            :disabled="!link.url"
            @click="router.visit(link.url)"
            class="px-3 py-1 border rounded text-sm"
          />
        </div>
      </template>
    </div>

  </div>
</template>