<script setup>
import SuperAdminLayout from '@/layouts/SuperAdminLayout.vue' // ✅ Ganti ke SuperAdminLayout
import { ref } from 'vue'
import { Head } from '@inertiajs/vue3'
import { Trash2, Eye, Download } from 'lucide-vue-next'

defineOptions({
  layout: SuperAdminLayout
})

// state filter
const search = ref('')
const kategori = ref('')
const tanggal_awal = ref('')
const tanggal_akhir = ref('')

// DUMMY
const statistik = ref({
  dokumen: 68,
  foto: 20,
  video: 10,
  audio: 2,
  download: 45,
  dilihat: 120,
  favorit: 12
})

// DATA AKSES CEPAT
const aksesCepat = ref([
  { nama: 'Peraturan Daerah' },
  { nama: 'Galeri Foto' },
  { nama: 'Galeri Video' }
])

// DUMMY USER
const users = ref([
  {
    nama: 'Admin Arsip',
    role: 'Admin',
    status: 'Aktif',
    tanggal: '04 Sep 2025',
    dokumen: 12
  },
  {
    nama: 'User Dinas',
    role: 'User',
    status: 'Aktif',
    tanggal: '10 Okt 2025',
    dokumen: 8
  },
  {
    nama: 'Super Admin',
    role: 'Superadmin',
    status: 'Aktif',
    tanggal: '01 Jan 2025',
    dokumen: 25
  }
])


const handleDelete = (user) => {
  console.log('Hapus user:', user)
}

const handleView = (user) => {
  console.log('Lihat user:', user)
}
</script>

<template>
<Head title="Statistik & Laporan - Super Admin" />

<div class="p-6 bg-[#f3f4f6] min-h-screen space-y-6">

  <h1 class="text-2xl font-bold text-gray-800">
    Statistik & Laporan Sistem
  </h1>

  <div class="bg-[#2f6f7e] p-4 rounded-xl flex flex-wrap gap-3 items-center shadow-md">

    <div class="flex items-center bg-white px-3 py-2 rounded-lg text-sm flex-1">
      <span class="text-gray-400 mr-2">🔍</span>
      <input v-model="search" placeholder="Cari dokumen, nomor surat..." class="outline-none w-full"/>
    </div>

    <select v-model="kategori" class="bg-white px-3 py-2 rounded-lg text-sm">
      <option value="">Semua Kategori</option>
      <option>Dokumen</option>
      <option>Foto</option>
      <option>Video</option>
    </select>

    <input type="date" v-model="tanggal_awal" class="bg-white px-3 py-2 rounded-lg text-sm"/>
    <input type="date" v-model="tanggal_akhir" class="bg-white px-3 py-2 rounded-lg text-sm"/>

    <button class="bg-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-100 transition">
      Cari
    </button>
  </div>

  <!-- STATISTIK UTAMA -->
  <div class="grid md:grid-cols-3 gap-4">

    <!-- CHART -->
    <div class="bg-[#6f98a8] p-4 rounded-xl flex justify-center items-center">
      <div class="w-52 h-52 bg-white rounded-full flex items-center justify-center">
        <span class="text-gray-400 text-sm">Pie Chart</span>
      </div>
    </div>

    <!-- TOTAL -->
    <div class="bg-[#6f98a8] p-4 rounded-xl space-y-3">
      <div class="bg-white rounded px-3 py-2 text-sm">Dokumen : {{ statistik.dokumen }}</div>
      <div class="bg-white rounded px-3 py-2 text-sm">Foto : {{ statistik.foto }}</div>
      <div class="bg-white rounded px-3 py-2 text-sm">Video : {{ statistik.video }}</div>
      <div class="bg-white rounded px-3 py-2 text-sm">Audio : {{ statistik.audio }}</div>
    </div>

    <!-- PROGRESS -->
    <div class="space-y-3 flex flex-col justify-between h-full">

      <!-- DIUNDUH -->
      <div class="bg-[#6f98a8] p-4 rounded-xl flex items-center justify-between min-h-[110px]">

        <div class="flex flex-col justify-center">
          <div class="text-xs bg-white px-2 py-1 rounded w-fit mb-2 font-semibold text-gray-700">
            {{ statistik.download }}
          </div>
          <p class="text-white text-sm font-medium">Dokumen diunduh</p>
        </div>

        <!-- ICON -->
        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-md">
          <Download class="w-5 h-5 text-[#2f4fa2]" />
        </div>

      </div>

      <!-- DILIHAT -->
      <div class="bg-[#6f98a8] p-4 rounded-xl flex items-center justify-between min-h-[110px]">

        <div class="flex flex-col justify-center">
          <div class="text-xs bg-white px-2 py-1 rounded w-fit mb-2 font-semibold text-gray-700">
            {{ statistik.dilihat }}
          </div>
          <p class="text-white text-sm font-medium">Dokumen dilihat</p>
        </div>

        <!-- ICON -->
        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-md">
          <Eye class="w-5 h-5 text-[#2f4fa2]" />
          </div>
        </div>
      </div>
    </div>

  <!-- AKSES CEPAT -->
  <div>
    <h2 class="bg-[#2f4fa2] text-white px-4 py-1 rounded-md w-fit text-sm mb-3">
      Akses Cepat
    </h2>

    <div class="grid md:grid-cols-3 gap-4">

      <div v-for="(item, i) in aksesCepat" :key="i" class="bg-[#6f98a8] p-4 rounded-xl space-y-2">
        <div class="w-10 h-10 bg-gray-200 rounded"></div>
        <div class="bg-white text-xs px-2 py-1 rounded w-fit">{{ item.nama }}</div>
        <div class="h-2 bg-gray-300 rounded w-24"></div>
      </div>

    </div>
  </div>

  <!-- PENGGUNA TERAKTIF -->
  <div>
    <h2 class="bg-[#2f4fa2] text-white px-4 py-1 rounded-md w-fit text-sm mb-3">
      Pengguna Teraktif
    </h2>

    <div class="bg-[#6f98a8] rounded-xl p-4 overflow-x-auto">

      <table class="w-full text-xs text-white">
        <thead>
          <tr class="text-left border-b border-white/30">
            <th class="py-2">Pengguna</th>
            <th>Role</th>
            <th>Status</th>
            <th>Bergabung</th>
            <th>Action</th>
            <th>Dokumen</th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="(user, i) in users" :key="i" class="border-b border-white/20">
            <td class="py-2">{{ user.nama }}</td>
            <td>{{ user.role }}</td>
            <td>{{ user.status }}</td>
            <td>{{ user.tanggal }}</td>

            <!-- ICON -->
            <td>
              <div class="flex gap-2">
                <button 
                  @click.stop="handleDelete(user)"
                  class="p-1.5 rounded-lg hover:bg-red-100 hover:scale-110 transition"
                >
                  <Trash2 class="w-4 h-4 text-red-500" />
                </button>

                <button 
                  @click.stop="handleView(user)"
                  class="p-1.5 rounded-lg hover:bg-blue-100 hover:scale-110 transition"
                >
                  <Eye class="w-4 h-4 text-blue-500" />
                </button>
              </div>
            </td>

            <td>{{ user.dokumen }}</td>
          </tr>
        </tbody>
      </table>

    </div>
  </div>

</div>
</template>