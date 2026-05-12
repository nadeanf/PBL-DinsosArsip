<script setup>
import SuperAdminLayout from '@/layouts/SuperAdminLayout.vue' // ✅ Ganti ke SuperAdminLayout
import { ref, computed } from 'vue'
import { Head, usePage } from '@inertiajs/vue3'
import { Trash2, Eye, Download } from 'lucide-vue-next'

import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  ArcElement
} from 'chart.js'
import { Pie } from 'vue-chartjs'
ChartJS.register(
  Title,
  Tooltip,
  Legend,
  ArcElement
)

defineOptions({
  layout: SuperAdminLayout
})


// state filter
const search = ref('')
const kategori = ref('')
const tanggal_awal = ref('')
const tanggal_akhir = ref('')
const page = usePage()

const statistik = computed(() => page.props.statistik)
const users = ref(page.props.users)

// DATA AKSES CEPAT
const aksesCepat = ref([
  { nama: 'Peraturan Daerah' },
  { nama: 'Galeri Foto' },
  { nama: 'Galeri Video' }
])



const handleDelete = (user) => {
  console.log('Hapus user:', user)
}

const handleView = (user) => {
  console.log('Lihat user:', user)
}
const pieData = computed(() => ({
  labels: ['Dokumen', 'Foto', 'Video', 'Audio'],
  datasets: [
    {
      data: [
        statistik.value.dokumen,
        statistik.value.foto,
        statistik.value.video,
        statistik.value.audio
      ],
      backgroundColor: [
        '#2f4fa2',
        '#4f9da6',
        '#f59e0b',
        '#10b981'
      ],
      borderWidth: 1
    }
  ]
}))

const pieOptions = {
  responsive: true,
  plugins: {
    legend: {
      position: 'bottom'
    }
  }
}
</script>

<template>
<Head title="Statistik & Laporan - Super Admin" />

<div class="p-6 bg-[#f3f4f6] min-h-screen space-y-6">

  <h1 class="text-2xl font-bold text-gray-800">
    Statistik & Laporan Sistem
  </h1>

  <!-- STATISTIK UTAMA -->
  <div class="grid md:grid-cols-3 gap-4">

    <!-- CHART -->
  <div class="bg-[#6f98a8] p-4 rounded-xl flex justify-center items-center">
    <div class="w-72 bg-white p-4 rounded-xl">
      <Pie :data="pieData" :options="pieOptions" />
    </div>
  </div>

    <!-- TOTAL -->
    <div class="bg-[#6f98a8] p-4 rounded-xl space-y-3">
      <div class="bg-white rounded px-3 py-2 text-sm">Dokumen : {{ statistik?.dokumen }}</div>
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

</div>
</template>