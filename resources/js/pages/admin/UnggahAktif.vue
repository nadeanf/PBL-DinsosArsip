<script setup lang="ts">
import { ref, computed, watch } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/layouts/AdminLayout.vue';

defineOptions({ layout: AdminLayout });

const props = defineProps<{ folder: string }>();

const kategoriAktifInaktif: Record<string, string[]> = {
  'Umum': [
    'Telekomunikasi','Perjalanan Dinas Dalam Negeri','Perjalanan Dinas Luar Negeri',
    'Penggunaan Fasilitas Kantor','Rapat Pimpinan','Penyediaan Konsumsi',
    'Pengurusan Kendaraan Dinas','Pengelolaan Jaringan Listrik',
    'Ketertiban dan Keamanan','Administrasi Parkir'
  ],
  'Pemerintahan': ['Otonomi Daerah','Pemerintahan Umum','Hukum'],
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
});

const subKategoriList = computed(() => {
  return form.kategori_kelompok
    ? kategoriAktifInaktif[form.kategori_kelompok]
    : [];
});

watch(() => form.kategori_kelompok, () => {
  form.kategori = '';
});

const fileInput = ref<HTMLInputElement | null>(null);
const triggerUpload = () => fileInput.value?.click();
const handleFileChange = (e: any) => form.files = e.target.files;

const submit = () => {
  console.log(form.data());
};

const goBack = () => window.history.back();
</script>

<template>
  <Head :title="'Unggah ' + folder" />

  <div class="py-10 px-6">
    <div class="max-w-4xl mx-auto">

      <h1 class="text-4xl font-black mb-10 text-gray-800">
        Unggah File {{ folder }}
      </h1>

      <form @submit.prevent="submit"
        class="bg-[#7fa1b1] text-gray-900 p-10 rounded-[40px] shadow-lg">

        <div class="space-y-6">

          <!-- Upload -->
          <div @click="triggerUpload"
            class="bg-white p-10 border-2 border-dashed text-center cursor-pointer rounded-2xl">
            <input type="file" ref="fileInput" class="hidden" @change="handleFileChange" multiple />
            <p class="font-bold">Klik untuk upload</p>
          </div>

          <input v-model="form.judul" placeholder="Judul"
            class="w-full p-4 bg-white text-black rounded-xl" />

          <input v-model="form.nomor" placeholder="Nomor"
            class="w-full p-4 bg-white text-black rounded-xl" />

          <input v-model="form.tahun" placeholder="Tahun"
            class="w-full p-4 bg-white text-black rounded-xl" />

          <select v-model="form.kategori_kelompok"
            class="w-full p-4 bg-white text-black rounded-xl">
            <option value="">Pilih Kelompok</option>
            <option v-for="(v,k) in kategoriAktifInaktif" :key="k">{{ k }}</option>
          </select>

          <select v-model="form.kategori"
            class="w-full p-4 bg-white text-black rounded-xl">
            <option value="">Pilih Detail</option>
            <option v-for="k in subKategoriList" :key="k">{{ k }}</option>
          </select>

          <select v-model="form.status_akses"
            class="w-full p-4 bg-white text-black rounded-xl">
            <option>Publik</option>
            <option>Private</option>
          </select>

          <input v-model="form.lokasi" placeholder="Lokasi"
            class="w-full p-4 bg-white text-black rounded-xl" />

          <textarea v-model="form.deskripsi"
            class="w-full p-4 bg-white text-black rounded-xl" placeholder="Deskripsi" ></textarea>

          <div class="flex justify-end gap-4">
            <button type="submit"
              class="bg-blue-600 text-white px-6 py-2 rounded-xl">
              Simpan
            </button>
            <button type="button" @click="goBack"
              class="bg-red-500 text-white px-6 py-2 rounded-xl">
              Batal
            </button>
          </div>

        </div>
      </form>
    </div>
  </div>
</template>