<script setup>
import SuperAdminLayout from '@/layouts/SuperAdminLayout.vue' // ✅ Ganti ke SuperAdminLayout
import { ref } from 'vue'
import { Head } from '@inertiajs/vue3'
import { Trash2, Eye } from 'lucide-vue-next'

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

  <div class="grid md:grid-cols-3 gap-4">

    <div class="bg-[#6f98a8] p-4 rounded-xl flex justify-center items-center shadow-sm">
      <div class="w-52 h-52 bg-white rounded-full flex items-center justify-center border-4 border-white/30">
        <span class="text-gray-400 text-sm">Pie Chart</span>
      </div>
    </div>

    <div class="bg-[#6f98a8] p-4 rounded-xl space-y-3 shadow-sm">
      <div class="bg-white rounded px-3 py-2 text-sm flex justify-between">
        <span>Dokumen :</span> <span class="font-bold text-[#2f6f7e]">{{ statistik.dokumen }}</span>
      </div>
      <div class="bg-white rounded px-3 py-2 text-sm flex justify-between">
        <span>Foto :</span> <span class="font-bold text-[#2f6f7e]">{{ statistik.foto }}</span>
      </div>
      <div class="bg-white rounded px-3 py-2 text-sm flex justify-between">
        <span>Video :</span> <span class="font-bold text-[#2f6f7e]">{{ statistik.video }}</span>
      </div>
      <div class="bg-white rounded px-3 py-2 text-sm flex justify-between">
        <span>Audio :</span> <span class="font-bold text-[#2f6f7e]">{{ statistik.audio }}</span>
      </div>
    </div>

    <div class="space-y-3">

      <div class="bg-[#6f98a8] p-3 rounded-xl shadow-sm">
        <div class="text-xs bg-white px-2 py-1 rounded w-fit mb-1 font-bold text-[#2f6f7e]">{{ statistik.download }}</div>
        <p class="text-white text-xs mb-2 font-medium">Dokumen diunduh</p>
        <div class="h-2 bg-white/30 rounded overflow-hidden">
            <div class="h-full bg-white w-[45%]"></div>
        </div>
      </div>

      <div class="bg-[#6f98a8] p-3 rounded-xl shadow-sm">
        <div class="text-xs bg-white px-2 py-1 rounded w-fit mb-1 font-bold text-[#2f6f7e]">{{ statistik.dilihat }}</div>
        <p class="text-white text-xs mb-2 font-medium">Dokumen dilihat</p>
        <div class="h-2 bg-white/30 rounded overflow-hidden">
            <div class="h-full bg-white w-[75%]"></div>
        </div>
      </div>

      <div class="bg-[#6f98a8] p-3 rounded-xl shadow-sm">
        <div class="text-xs bg-white px-2 py-1 rounded w-fit mb-1 font-bold text-[#2f6f7e]">{{ statistik.favorit }}</div>
        <p class="text-white text-xs mb-2 font-medium">Favorit</p>
        <div class="h-2 bg-white/30 rounded overflow-hidden">
            <div class="h-full bg-white w-[30%]"></div>
        </div>
      </div>

    </div>

  </div>

  <div>
    <h2 class="bg-[#2f4fa2] text-white px-4 py-1 rounded-md w-fit text-sm mb-3 font-semibold shadow-sm">
      Akses Cepat
    </h2>

    <div class="grid md:grid-cols-3 gap-4">

      <div v-for="(item, i) in aksesCepat" :key="i" class="bg-[#6f98a8] p-4 rounded-xl space-y-2 shadow-sm border border-transparent hover:border-white/50 transition">
        <div class="w-10 h-10 bg-gray-100/50 rounded flex items-center justify-center">
            <span class="text-white text-[10px]">Icon</span>
        </div>
        <div class="bg-white text-xs px-2 py-1 rounded w-fit font-bold text-[#2f6f7e]">{{ item.nama }}</div>
        <div class="h-2 bg-white/30 rounded w-24"></div>
      </div>

    </div>
  </div>

  <div>
    <h2 class="bg-[#2f4fa2] text-white px-4 py-1 rounded-md w-fit text-sm mb-3 font-semibold shadow-sm">
      Pengguna Teraktif (Manajemen User)
    </h2>

    <div class="bg-[#6f98a8] rounded-xl p-4 overflow-x-auto shadow-md border border-white/20">

      <table class="w-full text-xs text-white">
        <thead>
          <tr class="text-left border-b border-white/30">
            <th class="py-3 px-2">Pengguna</th>
            <th>Role</th>
            <th>Status</th>
            <th>Bergabung</th>
            <th class="text-center">Action</th>
            <th class="text-right px-2">Dokumen</th>
          </tr>
        </thead>

        <tbody class="divide-y divide-white/10">
          <tr v-for="(user, i) in users" :key="i" class="hover:bg-white/5 transition">
            <td class="py-3 px-2 font-medium">{{ user.nama }}</td>
            <td>
                <span class="bg-white/20 px-2 py-0.5 rounded text-[10px]">{{ user.role }}</span>
            </td>
            <td>
                <span class="flex items-center gap-1">
                    <span class="w-1.5 h-1.5 bg-green-400 rounded-full"></span>
                    {{ user.status }}
                </span>
            </td>
            <td>{{ user.tanggal }}</td>

            <td>
              <div class="flex gap-2 justify-center">
                <button 
                  @click.stop="handleDelete(user)"
                  class="p-1.5 rounded-lg bg-white/10 hover:bg-red-500 transition group"
                  title="Hapus Pengguna"
                >
                  <Trash2 class="w-4 h-4 text-red-200 group-hover:text-white" />
                </button>

                <button 
                  @click.stop="handleView(user)"
                  class="p-1.5 rounded-lg bg-white/10 hover:bg-blue-500 transition group"
                  title="Lihat Detail"
                >
                  <Eye class="w-4 h-4 text-blue-200 group-hover:text-white" />
                </button>
              </div>
            </td>

            <td class="text-right px-2 font-bold">{{ user.dokumen }}</td>
          </tr>
        </tbody>
      </table>

    </div>
  </div>

</div>
</template>