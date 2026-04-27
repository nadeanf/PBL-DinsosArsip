<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { ref, reactive, computed } from 'vue'
import AdminLayout from '@/layouts/AdminLayout.vue'
import { Plus, Trash2 } from 'lucide-vue-next'

defineOptions({ layout: AdminLayout })

/* DATA DUMMY*/

const kategoriData = reactive({
  aktif_inaktif: {
    "Umum": [
      "Telekomunikasi",
      "Perjalanan Dinas Dalam Negeri",
      "Perjalanan Dinas Luar Negeri",
      "Penggunaan Fasilitas Kantor",
      "Rapat Pimpinan",
      "Penyediaan Konsumsi",
      "Pengurusan Kendaraan Dinas",
      "Pemeliharaan Gedung, Taman, dan Peralatan Kantor",
      "Pengelolaan Jaringan Listrik, Air, Telepon dan Komputer",
      "Ketertiban dan Keamanan",
      "Administrasi Pengelolaan Parkir",
      "Administrasi Pakaian Dinas Pegawai, Satpam, Petugas Kebersihan, dan Pegawai Lainnya"
    ],

    "Pemerintahan": [
      "Otonomi Daerah",
      "Pemerintahan Umum",
      "Hukum"
    ],

    "Kesejahteraan Rakyat": [
      "Kebijakan di bidang Sosial yang dilakukan oleh Pemerintah Daerah",
      "Kesejahteraan Sosial Anak",
      "Rehabilitasi Sosial",
      "Rehabilitasi Sosial Tuna Sosial",
      "Rehabilitasi Sosial Korban Penyalahgunaan NAPZA",
      "Pengumpulan dan Pengelolaan sumber dana bantuan sosial",
      "Perlindungan Sosial Korban Tindak Kekerasan dan Pekerja Migran",
      "Perlindungan Sosial Korban Bencana Sosial",
      "Perlindungan Sosial Korban Bencana Alam",
      "Jaminan Sosial",
      "Pemberdayaan keluarga dan kelembagaan Sosial",
      "Pemberdayaan komunitas adat terpencil",
      "Penanggulangan Kemiskinan Perkotaan dan Perdesaan",
      "Kepahlawanan, Keperintisan dan Kesetiakawanan Sosial"
    ],

    "Keuangan": [
      "Surat Penyedia Dana (SPP, SPM dan SP2D): UP, GU, TU, LS",
      "Pendapatan",
      "Belanja",
      "Pembiayaan Daerah",
      "Dokumen Penatausahaan Keuangan",
      "Pertanggungjawaban Penggunaan Dana",
      "Daftar Gaji",
      "Kartu Gaji",
      "Data Rekening Bendahara Umum Daerah (BUD)",
      "Laporan Keuangan"
    ]
  },

  vital: [
    "Kebijakan dan program pemerintah tentang masalah sosial",
    "Bantuan Sosial",
    "Penghargaan kepada pahlawan",
    "Perintis kemerdekaan",
    "Taman Makam Pahlawan",
    "Organisasi dan kelembagaan masyarakat sosial",
    "Korban kekacauan, pengungsian dan rehabilitasi",
    "Program Keluarga Harapan",
    "Suku terasing",
    "Pembinaan Komunitas Adat Terpencil (PKAT)"
  ]
})

/* STATE */

const mode = ref<'aktif' | 'vital'>('aktif')
const showModal = ref(false)
const newItem = ref('')
const selectedKelompok = ref('Umum')

/* COMPUTED */

const kelompokList = computed(() => Object.keys(kategoriData.aktif_inaktif))

/* ACTION */

const tambahKategori = () => {
  if (!newItem.value.trim()) return

  if (mode.value === 'aktif') {
    kategoriData.aktif_inaktif[selectedKelompok.value].push(newItem.value)
  } else {
    kategoriData.vital.push(newItem.value)
  }

  newItem.value = ''
  showModal.value = false
}

const hapusItem = (kelompok: string | null, index: number) => {
  if (mode.value === 'aktif' && kelompok) {
    kategoriData.aktif_inaktif[kelompok].splice(index, 1)
  } else {
    kategoriData.vital.splice(index, 1)
  }
}
</script>

<template>
  <Head title="Kelola Kategori" />

  <div class="p-6">

    <!-- HEADER -->
    <div class="flex items-center gap-4 mb-6">
      <h1 class="text-3xl font-extrabold text-gray-800">
        Kelola Kategori
      </h1>

      <!-- SWITCH -->
      <select v-model="mode"
        class="bg-gray-200 px-4 py-1 rounded-full text-sm font-semibold">
        <option value="aktif">Aktif / Inaktif</option>
        <option value="vital">Vital</option>
      </select>

      <!-- BUTTON TAMBAH -->
      <button 
        @click="showModal = true"
        class="w-10 h-10 flex items-center justify-center bg-blue-600 text-white rounded-full shadow-md hover:scale-110 transition">
        <Plus class="w-5 h-5" />
      </button>
    </div>

    <!-- AKTIF-->
    <div v-if="mode === 'aktif'" class="space-y-8">

      <div v-for="(items, kelompok) in kategoriData.aktif_inaktif" :key="kelompok">
        
        <h2 class="text-xl font-bold mb-3 text-gray-700">
          {{ kelompok }}
        </h2>

        <div class="space-y-3">
          <div v-for="(item, i) in items" :key="i"
            class="flex items-center justify-between">

            <div class="bg-[#6f97a8] text-white px-6 py-3 rounded-xl w-full shadow-md">
              {{ item }}
            </div>

            <button 
              @click="hapusItem(kelompok, i)"
              class="ml-3 bg-gray-200 hover:bg-red-500 hover:text-white p-2 rounded-lg transition">
              <Trash2 class="w-4 h-4" />
            </button>

          </div>
        </div>

      </div>

    </div>

    <!-- VITAL -->
    <div v-else class="space-y-3">

      <div v-for="(item, i) in kategoriData.vital" :key="i"
        class="flex items-center justify-between">

        <div class="bg-[#6f97a8] text-white px-6 py-3 rounded-xl w-full shadow-md">
          {{ item }}
        </div>

        <button 
          @click="hapusItem(null, i)"
          class="ml-3 bg-gray-200 hover:bg-red-500 hover:text-white p-2 rounded-lg transition">
          <Trash2 class="w-4 h-4" />
        </button>

      </div>

    </div>

  </div>

  <!-- MODAL -->
  <div v-if="showModal" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-2xl w-[400px] shadow-xl">

      <h2 class="text-xl font-bold mb-4">Tambah Kategori</h2>

      <!-- PILIH KELOMPOK -->
      <div v-if="mode === 'aktif'" class="mb-3">
        <select v-model="selectedKelompok" class="w-full p-2 border rounded-lg">
          <option v-for="k in kelompokList" :key="k">{{ k }}</option>
        </select>
      </div>

      <!-- INPUT -->
      <input 
        v-model="newItem"
        type="text"
        placeholder="Masukkan kategori..."
        class="w-full p-3 border rounded-lg mb-4"
      />

      <!-- BUTTON -->
      <div class="flex justify-end gap-3">
        <button 
          @click="showModal = false"
          class="px-4 py-2 bg-gray-300 rounded-lg">
          Batal
        </button>

        <button 
          @click="tambahKategori"
          class="px-4 py-2 bg-blue-600 text-white rounded-lg">
          Simpan
        </button>
      </div>

    </div>
  </div>

</template>