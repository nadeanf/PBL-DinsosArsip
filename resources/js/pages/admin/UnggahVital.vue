<script setup lang="ts">
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/layouts/AdminLayout.vue';

defineOptions({ layout: AdminLayout });

const props = defineProps<{ folder: string }>();

const kategoriVital = [
   'Kebijakan dan program pemerintah tentang masalah sosial', 
  'Bantuan Sosial', 'Penghargaan kepada pahlawan', 'Perintis kemerdekaan', 
  'Taman Makam Pahlawan', 'Organisasi dan kelembagaan masyarakat sosial', 
  'Korban kekacauan, pengungsian dan rehabilitasi', 'Suku terasing', 
  'Pembinaan Komunitas Adat Terpencil (PKAT)'
];

const form = useForm({
  judul: '',
  nomor: '',
  tahun: '',
  kategori: '',
  lokasi: '',
  deskripsi: '',
  files: null as any,
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

          <div @click="triggerUpload"
            class="bg-white p-10 border-2 border-dashed text-center cursor-pointer rounded-2xl">
            <input type="file" ref="fileInput" class="hidden" @change="handleFileChange" multiple />
            <p class="font-bold">Klik untuk upload</p>
          </div>

          <input v-model="form.judul"
            class="w-full p-4 bg-white text-black rounded-xl" placeholder="Judul" />

          <input v-model="form.nomor"
            class="w-full p-4 bg-white text-black rounded-xl" placeholder="Nomor" />

          <input v-model="form.tahun"
            class="w-full p-4 bg-white text-black rounded-xl" placeholder="Tahun" />

          <select v-model="form.kategori"
            class="w-full p-4 bg-white text-black rounded-xl">
            <option value="">Pilih kategori</option>
            <option v-for="k in kategoriVital" :key="k">{{ k }}</option>
          </select>

          <input v-model="form.lokasi"
            class="w-full p-4 bg-white text-black rounded-xl" placeholder="Lokasi" />

          <textarea v-model="form.deskripsi"
            class="w-full p-4 bg-white text-black rounded-xl" placeholder="Deskripsi"> </textarea>

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