<script setup lang="ts">
import { ref, computed, watch } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AuthLayout from '@/layouts/AuthLayout.vue';

defineOptions({ layout: AuthLayout });

const props = defineProps<{ folder: string }>();

// 1. Data Kategori Statis
const kategoriVital = [
  'Kebijakan dan program pemerintah tentang masalah sosial', 
  'Bantuan Sosial', 'Penghargaan kepada pahlawan', 'Perintis kemerdekaan', 
  'Taman Makam Pahlawan', 'Organisasi dan kelembagaan masyarakat sosial', 
  'Korban kekacauan, pengungsian dan rehabilitasi', 'Suku terasing', 
  'Pembinaan Komunitas Adat Terpencil (PKAT)'
];

const kategoriAktifInaktif = {
  'Umum': [
    'Telekomunikasi', 'Perjalanan Dinas Dalam Negeri', 'Perjalanan Dinas Luar Negeri', 
    'Penggunaan Fasilitas Kantor', 'Rapat Pimpinan', 'Penyediaan Konsumsi', 
    'Pengurusan Kendaraan Dinas', 'Pengelolaan Jaringan Listrik, Air, Telepon dan Komputer',
    'Ketertiban dan Keamanan', 'Administrasi Pengelolaan Parkir', 
    'Administrasi Pakaian Dinas Pegawai, Satpam, Petugas Kebersihan, dan Pegawai Lainnya'
  ],
  'Pemerintahan': ['Otonomi Daerah', 'Pemerintahan Umum', 'Hukum'],
  'Kesejahteraan Rakyat': [
    'Kebijakan di bidang Sosial', 'Kesejahteraan Sosial Anak', 'Rehabilitasi Sosial',
    'Rehabilitasi Sosial Tuna Sosial', 'Rehabilitasi Sosial Korban Penyalahgunaan NAPZA',
    'Jaminan Sosial', 'Pemberdayaan komunitas adat terpencil', 
    'Penanggulangan Kemiskinan', 'Kepahlawanan dan Kesetiakawanan Sosial'
  ],
  'Keuangan': [
    'Surat Penyedia Dana (SPP, SPM dan SP2D)', 'Pendapatan', 'Belanja', 
    'Pembiayaan Daerah', 'Laporan Keuangan'
  ]
};

// 3. Data Bidang (TAMBAHAN)
const bidangList = [
  'Sekretariat',
  'Rehabilitasi Sosial',
  'Perlindungan dan Jaminan Sosial',
  'Pemberdayaan Sosial'
];

// 2. Form State
const form = useForm({
    judul: '',
    nomor: '',
    tahun: '',
    status_akses: 'Publik',
    kategori_kelompok: '',
    kategori: '',
    lokasi: '',
    deskripsi: '',
    files: null as any, 
    bidang: '', // TAMBAHAN
});

// Ganti baris 54 (logika isVital) menjadi ini:
const isVital = computed(() => props.folder?.toLowerCase() === 'vital');

// TAMBAHAN
const isPrivate = computed(() => form.status_akses === 'Private');

// Sub-kategori dinamis untuk Aktif/Inaktif
const subKategoriList = computed(() => {
    return form.kategori_kelompok ? kategoriAktifInaktif[form.kategori_kelompok] : [];
});

// Reset kategori jika kelompok berubah
watch(() => form.kategori_kelompok, () => {
    form.kategori = '';
});

const fileInput = ref<HTMLInputElement | null>(null);
const triggerUpload = () => fileInput.value?.click();
const handleFileChange = (e: any) => { form.files = e.target.files; };

const submit = () => {
    console.log("Data dikirim:", form.data());
};
</script>

<template>
  <Head :title="'Unggah ' + folder" />
  <div class="py-10 px-6">
    <div class="max-w-4xl mx-auto">
      <h1 class="text-4xl font-black mb-10 text-gray-800 uppercase tracking-tight">
        Unggah File {{ folder }}
      </h1>

      <form @submit.prevent="submit" class="bg-[#7fa1b1] p-10 rounded-[40px] shadow-lg">
        <div class="space-y-6">
          
          <div class="space-y-2">
            <span class="inline-block bg-[#b8ccd5] text-gray-700 px-4 py-1 rounded-full text-sm font-bold shadow-sm ml-2">
              File Dokumen
            </span>
            <div 
  @click="triggerUpload" 
  class="bg-white rounded-[35px] p-12 border-2 border-dashed border-gray-300 flex flex-col items-center justify-center cursor-pointer hover:bg-gray-50 transition-all shadow-inner relative"
>
  <div v-if="form.files && form.files.length > 0" class="mb-4 text-center absolute top-4">
    <span class="bg-blue-600 text-white px-4 py-1 rounded-full font-bold uppercase text-[10px] tracking-widest shadow-sm">
      {{ form.files.length }} Item Terpilih
    </span>
  </div>

  <input 
    type="file" 
    ref="fileInput" 
    class="hidden" 
    @change="handleFileChange" 
    webkitdirectory 
    directory
    multiple 
  />

  <div class="mb-4 text-black flex justify-center">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
      <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
    </svg>
  </div>

  <p class="font-bold text-gray-900 text-xl text-center leading-tight">
    Klik untuk unggah atau drag and drop
  </p>

  <p class="text-gray-600 text-sm mt-2 font-medium tracking-wide">
    PDF, DOC, XLS, JPG, PNG, MP3, MP4
  </p>

  <p class="text-gray-500 text-xs mt-8 font-normal italic">
    (Maks xx MB)
  </p>
</div>
          </div>

          <div class="space-y-4">
            <div>
              <label class="block text-black font-black mb-1.5 ml-3 text-sm uppercase">Judul Dokumen</label>
              <input v-model="form.judul" type="text" placeholder="Masukkan Judul Dokumen" 
                class="w-full p-4 bg-white text-black font-medium rounded-2xl border border-gray-300 shadow-sm outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <div class="grid grid-cols-1 gap-4">
              <div>
                <label class="block text-black font-black mb-1.5 ml-3 text-sm uppercase">Nomor Dokumen</label>
                <input v-model="form.nomor" type="text" placeholder="Nomor dokumen..." class="w-full p-4 bg-white text-black font-medium rounded-2xl border border-gray-300 shadow-sm outline-none" />
              </div>
              <div>
                <label class="block text-black font-black mb-1.5 ml-3 text-sm uppercase">Tahun Arsip</label>
                <input v-model="form.tahun" type="text" placeholder="Masukkan tahun arsip..." class="w-full p-4 bg-white text-black font-medium rounded-2xl border border-gray-300 shadow-sm outline-none" />
              </div>
            </div>

            <div v-if="isVital">
              <label class="block text-black font-black mb-1.5 ml-3 text-sm uppercase">Kategori Vital</label>
              <select v-model="form.kategori" class="w-full p-4 bg-white text-black font-medium rounded-2xl border border-gray-300 shadow-sm outline-none appearance-none">
                <option value="">-- Pilih Kategori Vital --</option>
                <option v-for="item in kategoriVital" :key="item" :value="item">{{ item }}</option>
              </select>
            </div>

            <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-black font-black mb-1.5 ml-3 text-sm uppercase">Kelompok Kategori</label>
                <select v-model="form.kategori_kelompok" class="w-full p-4 bg-white text-black font-medium rounded-2xl border border-gray-300 shadow-sm outline-none appearance-none">
                  <option value="">-- Pilih Kelompok --</option>
                  <option v-for="(subs, parent) in kategoriAktifInaktif" :key="parent" :value="parent">{{ parent }}</option>
                </select>
              </div>
              <div>
                <label class="block text-black font-black mb-1.5 ml-3 text-sm uppercase">Detail Kategori</label>
                <select v-model="form.kategori" :disabled="!form.kategori_kelompok" class="w-full p-4 bg-white text-black font-medium rounded-2xl border border-gray-300 shadow-sm outline-none appearance-none disabled:bg-gray-100">
                  <option value="">-- Pilih Detail --</option>
                  <option v-for="item in subKategoriList" :key="item" :value="item">{{ item }}</option>
                </select>
              </div>
            </div>

            <div class="grid grid-cols-1 gap-4">
              <div>
                <label class="block text-black font-black mb-1.5 ml-3 text-sm uppercase">Status Akses</label>
                <select v-model="form.status_akses" class="w-full p-4 bg-white text-black font-medium rounded-2xl border border-gray-300 shadow-sm outline-none appearance-none">
                  <option>Publik</option>
                  <option>Private</option>
                </select>
              </div>

              <!-- TAMBAHAN: MUNCUL HANYA SAAT PRIVATE -->
              <div v-if="isPrivate">
                <label class="block text-black font-black mb-1.5 ml-3 text-sm uppercase">Bidang</label>
                <select v-model="form.bidang" class="w-full p-4 bg-white text-black font-medium rounded-2xl border border-gray-300 shadow-sm outline-none appearance-none">
                  <option value="">-- Pilih Bidang --</option>
                  <option v-for="item in bidangList" :key="item" :value="item">{{ item }}</option>
                </select>
              </div>

              <div>
                <label class="block text-black font-black mb-1.5 ml-3 text-sm uppercase">Lokasi Hardcopy</label>
                <input v-model="form.lokasi" type="text" placeholder="Rak..." class="w-full p-4 bg-white text-black font-medium rounded-2xl border border-gray-300 shadow-sm outline-none" />
              </div>
            </div>

            <div>
              <label class="block text-black font-black mb-1.5 ml-3 text-sm uppercase tracking-wide">Deskripsi</label>
              <textarea v-model="form.deskripsi" rows="3" placeholder="Tambahkan keterangan..." 
                class="w-full p-4 bg-white text-black font-medium rounded-2xl border border-gray-300 shadow-sm outline-none"></textarea>
            </div>
          </div>

          <div class="flex justify-end gap-4 mt-10">
            <button type="submit" :class="isVital ? 'bg-[#4a7a96]' : 'bg-[#2f55a4]'" class="text-white px-10 py-3.5 rounded-2xl font-black shadow-md hover:opacity-90 transition-all uppercase text-sm tracking-widest">
              Simpan File
            </button>
            <button type="button" @click="window.history.back()" class="bg-[#ff0000] text-white px-10 py-3.5 rounded-full font-black shadow-md hover:bg-red-700 transition-all uppercase text-sm tracking-widest">
              Batal
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>