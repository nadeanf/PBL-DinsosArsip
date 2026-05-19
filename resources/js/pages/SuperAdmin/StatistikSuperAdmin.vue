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
  cutout: '60%',
  plugins: {
    legend: {
      position: 'bottom',
      labels: {
        boxWidth: 15,
        padding: 20
      }
    }
  }
}
</script>

<template>
<Head title="Statistik & Laporan - Super Admin" />

<div class="p-6 bg-[#f3f4f6] min-h-screen space-y-6">


 <!-- CARD STATISTIK -->
<div class="bg-white rounded-[2rem] p-6 shadow-md border border-gray-200">

  <h2 class="text-xl font-extrabold text-gray-800 mb-5">
    Statistik & Laporan Sistem
  </h2>

  <div class="grid md:grid-cols-2 gap-4 items-center">

    <!-- CHART -->
    <div class="flex flex-col items-center">

      <div class="self-start text-xs text-gray-500 mb-3">
        Total Arsip:
        <span class="font-bold text-gray-800">
          {{
            statistik.dokumen +
            statistik.foto +
            statistik.video +
            statistik.audio
          }}
        </span>
      </div>

      <div class="w-[220px]">
        <Pie :data="pieData" :options="pieOptions" />
      </div>

    </div>

    <!-- LIST DATA -->
    <div class="space-y-3">

      <div class="bg-gray-100 rounded-xl px-4 py-3 flex justify-between items-center">
        <span class="text-sm text-gray-700 font-medium">
          Dokumen
        </span>

        <span class="text-lg font-black text-gray-900">
          {{ statistik.dokumen }}
        </span>
      </div>

      <div class="bg-gray-100 rounded-xl px-4 py-3 flex justify-between items-center">
        <span class="text-sm text-gray-700 font-medium">
          Foto
        </span>

        <span class="text-lg font-black text-gray-900">
          {{ statistik.foto }}
        </span>
      </div>

      <div class="bg-gray-100 rounded-xl px-4 py-3 flex justify-between items-center">
        <span class="text-sm text-gray-700 font-medium">
          Video
        </span>

        <span class="text-lg font-black text-gray-900">
          {{ statistik.video }}
        </span>
      </div>

      <div class="bg-gray-100 rounded-xl px-4 py-3 flex justify-between items-center">
        <span class="text-sm text-gray-700 font-medium">
          Audio
        </span>

        <span class="text-lg font-black text-gray-900">
          {{ statistik.audio }}
        </span>
      </div>

    </div>

  </div>

  <!-- GARIS -->
  <div class="border-t border-gray-200 my-5"></div>

  <!-- BOTTOM -->
  <div class="grid md:grid-cols-2 gap-4">

    <!-- DOWNLOAD -->
    <div class="bg-[#eef3fb] rounded-xl px-4 py-3 flex justify-between items-center">

      <div class="flex items-center gap-2">
        <Download class="w-4 h-4 text-[#2f4fa2]" />

        <span class="text-sm text-gray-700 font-medium">
          Download
        </span>
      </div>

      <span class="text-xl font-black text-gray-900">
        {{ statistik.download }}
      </span>

    </div>

    <!-- DILIHAT -->
    <div class="bg-[#eef7ef] rounded-xl px-4 py-3 flex justify-between items-center">

      <div class="flex items-center gap-2">
        <Eye class="w-4 h-4 text-green-700" />

        <span class="text-sm text-gray-700 font-medium">
          Dilihat
        </span>
      </div>

      <span class="text-xl font-black text-gray-900">
        {{ statistik.dilihat }}
      </span>

    </div>

  </div>

</div>
</div>
</template>