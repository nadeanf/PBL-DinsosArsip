<script setup>
import AuthLayoutPimpinan from '@/layouts/AuthLayoutPimpinan.vue'
import { ref, computed } from 'vue'
import { Head, usePage } from '@inertiajs/vue3'
import { Eye, Download } from 'lucide-vue-next'
import { Pie, Bar } from 'vue-chartjs'
import {
  Chart as ChartJS,
  ArcElement,
  Tooltip,
  Legend,
  CategoryScale,
  LinearScale,
  BarElement
} from 'chart.js'

ChartJS.register(
  ArcElement, 
  Tooltip, 
  Legend,
  CategoryScale,
  LinearScale,
  BarElement
)

defineOptions({
  layout: AuthLayoutPimpinan
})

const page = usePage()

const tipeDokumen = computed(() => page.props?.tipeDokumen ?? [])
const totalDownload = computed(() => page.props?.totalDownload ?? 0)
const totalArsip = computed(() => page.props?.totalArsip ?? 0)

const users = computed(() => page.props?.users?.data ?? [])
const links = computed(() => page.props?.users?.links ?? [])

const totalUser = computed(() => page.props?.totalUser ?? 0)
const totalAktif = computed(() => page.props?.totalAktif ?? 0)
const totalAdmin = computed(() => page.props?.totalAdmin ?? 0)

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

  const kategoriData = computed(() => page.props?.kategoriStat ?? [])

const barChartData = computed(() => ({
  labels: kategoriData.value.map(i => i.nama),
  datasets: [
    {
      label: 'Jumlah Arsip',
      data: kategoriData.value.map(i => i.total),
      backgroundColor: '#3b82f6'
    }
  ]
}))

const barChartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      display: false
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

  <!-- CARD 2 (BAR CHART) -->
  <div class="bg-white rounded-2xl shadow p-6 space-y-4">
    <h2 class="font-semibold text-gray-700">
      Statistik Per Kategori
    </h2>

    <div class="h-[300px]">
      <Bar :data="barChartData" :options="barChartOptions" />
    </div>
  </div>

  
  <!-- TOTAL PENGGUNA AKTIF -->
<div class="space-y-5">

  <h2 class="bg-[#2f4fa2] text-white px-4 py-2 rounded-lg w-fit text-sm font-semibold">
    Total Pengguna Aktif
  </h2>

  <!-- CARD USER -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

    <!-- TOTAL USER -->
    <div class="bg-[#759fb1] p-4 rounded-2xl relative text-white shadow-sm border border-white/10">

      <div class="bg-white text-gray-800 px-3 py-0.5 rounded-full w-fit font-bold mb-2 text-[10px] shadow-inner">
        {{ totalUser }}
      </div>

      <p class="font-bold text-xs">Total Pengguna</p>

      <div class="absolute right-5 top-1/2 -translate-y-1/2 w-11 h-11 bg-white/90 rounded-xl shadow-sm"></div>

      <div class="mt-3 h-1.5 w-24 bg-white rounded-full opacity-80"></div>

    </div>

    <!-- USER AKTIF -->
    <div class="bg-[#759fb1] p-4 rounded-2xl relative text-white shadow-sm border border-white/10">

      <div class="bg-white text-gray-800 px-3 py-0.5 rounded-full w-fit font-bold mb-2 text-[10px] shadow-inner">
        {{ totalAktif }}
      </div>

      <p class="font-bold text-xs">Pengguna Aktif</p>

      <div class="absolute right-5 top-1/2 -translate-y-1/2 w-11 h-11 bg-white/90 rounded-xl shadow-sm"></div>

      <div class="mt-3 h-1.5 w-24 bg-white rounded-full opacity-80"></div>

    </div>

    <!-- ADMIN -->
    <div class="bg-[#759fb1] p-4 rounded-2xl relative text-white shadow-sm border border-white/10">

      <div class="bg-white text-gray-800 px-3 py-0.5 rounded-full w-fit font-bold mb-2 text-[10px] shadow-inner">
        {{ totalAdmin }}
      </div>

      <p class="font-bold text-xs">Administrator</p>

      <div class="absolute right-5 top-1/2 -translate-y-1/2 w-11 h-11 bg-white/90 rounded-xl shadow-sm"></div>

      <div class="mt-3 h-1.5 w-24 bg-white rounded-full opacity-80"></div>

    </div>

  </div>

  <!-- TABLE USER -->
  <div class="overflow-hidden rounded-[2rem] shadow-xl border border-gray-100 space-y-4">

    <h2 class="bg-[#2f4fa2] text-white px-4 py-2 rounded-lg w-fit text-sm font-semibold mb-2">
    Pengguna & Bagian Teraktif
  </h2>

    <table class="w-full text-left border-collapse">

      <thead>
        <tr class="bg-[#2f4fa2] text-white uppercase text-[10px] tracking-[0.15em]">
          <th class="px-6 py-5 font-bold text-center">Pengguna</th>
          <th class="px-6 py-5 font-bold text-center">Role</th>
          <th class="px-6 py-5 font-bold text-center">Status</th>
          <th class="px-6 py-5 font-bold text-center">Bagian</th>
          <th class="px-6 py-5 font-bold text-center">Total Dokumen</th>
        </tr>
      </thead>

      <tbody class="bg-[#759fb1] text-white">

        <tr
          v-for="user in users"
        :key="user.id"
        class="border-t border-white/20 hover:bg-white/10 transition"
      >

      <td class="px-6 py-4 text-center">
      <div class="font-bold text-sm leading-tight">
        {{ user.name }}
      </div>

      <div class="text-[10px] text-white/70 italic mt-0.5">
        {{ user.email }}
      </div>
      </td>

      <td class="px-6 py-4 text-center text-xs font-medium">
        {{ user.role }}
      </td>

      <td class="px-6 py-4 text-center">
        <span
          :class="user.is_active
            ? 'bg-green-100 text-green-700'
            : 'bg-red-100 text-red-700'"
          class="px-3 py-1 rounded-full text-[10px] font-bold"
        >
          {{ user.is_active ? 'Aktif' : 'Nonaktif' }}
        </span>
      </td>

      <td class="px-6 py-4 text-center text-[10px] italic leading-relaxed max-w-[200px] truncate">
        {{ user.bagian }}
      </td>

      <td class="px-6 py-4 text-center text-xs font-bold">
        {{ user.arsip_count }}
      </td>

    </tr>

      </tbody>

    </table>

  </div>

     <!-- PAGINATION -->
  <div class="flex justify-center items-center gap-2 pt-4 flex-wrap">

    <template v-for="link in links" :key="link.label">

      <button
        v-if="link.url"
        v-html="link.label"
        @click="$inertia.visit(link.url)"
        class="px-3 py-2 rounded-xl text-xs font-bold border transition"
        :class="[
          link.active
            ? 'bg-[#2f4fa2] text-white border-[#2f4fa2]'
            : 'bg-white text-gray-700 border-gray-200 hover:bg-[#2f4fa2] hover:text-white'
        ]"
      />

      <span
        v-else
        v-html="link.label"
        class="px-3 py-2 rounded-xl text-xs font-bold text-gray-400 border border-gray-200 bg-gray-100 cursor-not-allowed"
      />

    </template>

  </div>

</div>

</div>

</div>

</template>