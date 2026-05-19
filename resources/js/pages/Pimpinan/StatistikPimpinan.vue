<script setup>
import AuthLayoutPimpinan from '@/layouts/AuthLayoutPimpinan.vue'
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
  layout: AuthLayoutPimpinan
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
<Head title="Statistik & Laporan - Pimpinan" />

<div class="p-6 bg-[#f3f4f6] min-h-screen space-y-6">

  <h1 class="text-2xl font-bold text-gray-800">
    Statistik & Laporan Sistem
  </h1>

  

 <!-- STATISTIK BARU (1 CARD SAJA) -->
<div class="bg-white rounded-2xl shadow p-6 space-y-6">

  <div class="flex justify-between items-center">

    <div class="text-xs text-gray-500">
      Total Arsip: {{ totalArsip }}
    </div>
  </div>

  <div class="grid md:grid-cols-2 gap-6 items-center">

    <div class="h-[240px]">
      <Pie :data="chartData" :options="chartOptions" />
    </div>

    <div class="space-y-3">
      <div class="flex justify-between bg-gray-50 p-3 rounded-xl">
        <span>Dokumen</span>
        <b>{{ statistik.dokumen }}</b>
      </div>

      <div class="flex justify-between bg-gray-50 p-3 rounded-xl">
        <span>Foto</span>
        <b>{{ statistik.foto }}</b>
      </div>

      <div class="flex justify-between bg-gray-50 p-3 rounded-xl">
        <span>Video</span>
        <b>{{ statistik.video }}</b>
      </div>

      <div class="flex justify-between bg-gray-50 p-3 rounded-xl">
        <span>Audio</span>
        <b>{{ statistik.audio }}</b>
      </div>
    </div>

  </div>

  <div class="grid grid-cols-2 gap-4 pt-4 border-t">

    <div class="flex justify-between bg-blue-50 p-3 rounded-xl">
      <span>Download</span>
      <b>{{ statistik.download }}</b>
    </div>

    <div class="flex justify-between bg-green-50 p-3 rounded-xl">
      <span>Dilihat</span>
      <b>{{ statistik.dilihat }}</b>
    </div>

  </div>

</div>

    </div>
  </div>
</template>