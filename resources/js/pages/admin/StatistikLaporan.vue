<script setup>
import AdminLayout from '@/layouts/AdminLayout.vue'
import { ref, computed } from 'vue'
import { Head, usePage } from '@inertiajs/vue3'
import { Eye, Download } from 'lucide-vue-next'
import { Pie } from 'vue-chartjs'
import {
  Chart as ChartJS,
  ArcElement,
  Tooltip,
  Legend
} from 'chart.js'

ChartJS.register(ArcElement, Tooltip, Legend)

defineOptions({
  layout: AdminLayout
})

const page = usePage()

const tipeDokumen = computed(() => page.props?.tipeDokumen ?? [])
const totalDownload = computed(() => page.props?.totalDownload ?? 0)
const totalArsip = computed(() => page.props?.totalArsip ?? 0)

const normalizeNama = (nama) => {
  if (!nama) return ''
  nama = nama.toLowerCase()

  if (nama.includes('foto')) return 'Foto'
  if (nama.includes('dokumen')) return 'Dokumen'
  if (nama.includes('video')) return 'Video'
  if (nama.includes('audio')) return 'Audio'

  return nama
}

const getTipeCount = (nama) => {
  return tipeDokumen.value.find(i => normalizeNama(i.nama) === nama)?.total ?? 0
}

const statistik = computed(() => ({
  dokumen: getTipeCount('Dokumen'),
  foto: getTipeCount('Foto'),
  video: getTipeCount('Video'),
  audio: getTipeCount('Audio'),
  download: totalDownload.value ?? 0,
  dilihat: totalArsip.value ?? 0
}))

const search = ref('')
const kategori = ref('')
const tanggal_awal = ref('')
const tanggal_akhir = ref('')

const aksesCepat = ref([
  { nama: 'Peraturan Daerah' },
  { nama: 'Galeri Foto' },
  { nama: 'Galeri Video' }
])

// ================= CHART =================
const chartData = computed(() => ({
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
        '#4f46e5',
        '#22c55e',
        '#f59e0b',
        '#ef4444'
      ],
      cutout: '60%' // biar donut
    }
  ]
}))

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      position: 'bottom'
    }
  }
}
</script>

<template>
<div>
<Head title="Statistik & Laporan - Admin" />

<div class="p-6 bg-[#f3f4f6] min-h-screen space-y-6">

  <h1 class="text-2xl font-bold text-gray-800">
    Statistik & Laporan Sistem
  </h1>

  

  <!-- STATISTIK -->
  <div class="grid md:grid-cols-3 gap-4">

    <!-- CHART (FIXED) -->
    <div class="bg-[#6f98a8] p-4 rounded-xl flex justify-center items-center">
      <div class="bg-white rounded-xl p-4 w-full h-[250px]">
        <Pie :data="chartData" :options="chartOptions" />
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
    <div class="space-y-3 flex flex-col">

      <div class="bg-[#6f98a8] p-4 rounded-xl flex items-center justify-between min-h-[110px]">
        <div>
          <div class="text-xs bg-white px-2 py-1 rounded w-fit mb-2">
            {{ statistik.download }}
          </div>
          <p class="text-white text-sm">Dokumen diunduh</p>
        </div>

        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center">
          <Download class="w-5 h-5 text-[#2f4fa2]" />
        </div>
      </div>

      <div class="bg-[#6f98a8] p-4 rounded-xl flex items-center justify-between min-h-[110px]">
        <div>
          <div class="text-xs bg-white px-2 py-1 rounded w-fit mb-2">
            {{ statistik.dilihat }}
          </div>
          <p class="text-white text-sm">Akumulasi Arsip</p>
        </div>

        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center">
          <Eye class="w-5 h-5 text-[#2f4fa2]" />
        </div>
      </div>

    </div>
  </div>


</div>
</div>
</template>