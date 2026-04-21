<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { Head, useForm } from '@inertiajs/vue3'
import AuthLayout from '@/layouts/AuthLayout.vue'

defineOptions({ layout: AuthLayout })

// ✅ props dari controller
const props = defineProps<{
  folder: string,
  kategoriData: any[]
}>()

const goBack = () => window.history.back()

// =====================
// DATA LAMA (TETAP)
// =====================
const kategoriAktifInaktif = {
  'Umum': ['Telekomunikasi'],
  'Pemerintahan': ['Otonomi Daerah'],
  'Kesejahteraan Rakyat': ['Kebijakan di bidang Sosial'],
  'Keuangan': ['Pendapatan']
}

const bidangList = [
  'Sekretariat',
  'Rehabilitasi Sosial',
  'Perlindungan dan Jaminan Sosial',
  'Pemberdayaan Sosial'
]

// =====================
// FORM
// =====================
const form = useForm({
  judul: '',
  nomor: '',
  tahun: '',
  status_akses: 'publik',
  kategori_kelompok: '',
  id_kategori: '',
  lokasi: '',
  deskripsi: '',
  files: null as any,
  bagian: '',
  folder: props.folder
})

// =====================
// COMPUTED
// =====================
const isVital = computed(() => props.folder?.toLowerCase() === 'vital')
const isPrivate = computed(() => form.status_akses === 'private')

const subKategoriList = computed(() => {
  return kategoriAktifInaktif[form.kategori_kelompok] ?? []
})

// =====================
// WATCH
// =====================
watch(() => form.kategori_kelompok, () => {
  form.id_kategori = ''
})

// =====================
// FILE
// =====================
const fileInput = ref<HTMLInputElement | null>(null)
const filePreviews = ref<any[]>([])

const triggerUpload = () => fileInput.value?.click()

const handleFileChange = (e: any) => {
  const files = Array.from(e.target.files || [])
  form.files = files

  filePreviews.value = files.map((file: any) => ({
    name: file.name,
    type: file.type,
    url: URL.createObjectURL(file)
  }))
}

// =====================
// SUBMIT
// =====================
const submit = () => {
  form
    .transform((data) => ({
      ...data,
      id_kategori: data.id_kategori
    }))
    .post('/arsip', {
      forceFormData: true
    })
}
</script>

<template>
  <Head :title="'Unggah ' + folder" />

  <div class="py-10 px-6">
    <div class="max-w-4xl mx-auto">
      <h1 class="text-4xl font-black mb-10 text-gray-800 uppercase tracking-tight">
        Unggah File {{ folder }}
      </h1>

      <form @submit.prevent="submit" class="bg-[#7fa1b1] p-10 rounded-[40px] shadow-lg">

        <!-- FILE -->
        <div class="space-y-2">
          <span class="inline-block bg-[#b8ccd5] text-gray-700 px-4 py-1 rounded-full text-sm font-bold ml-2">
            File Dokumen
          </span>

          <div @click="triggerUpload"
            class="bg-white rounded-[35px] p-12 border-2 border-dashed border-gray-300 flex flex-col items-center cursor-pointer">

            <div v-if="form.files && form.files.length > 0">
              {{ form.files.length }} File dipilih
            </div>

            <input type="file" ref="fileInput" class="hidden" @change="handleFileChange" multiple />

            <p>Klik untuk upload</p>
          </div>
        </div>

        <!-- FORM -->
        <div class="space-y-4 mt-6">

          <div>
            <label>Judul</label>
            <input v-model="form.judul" class="w-full p-3 border rounded" />
          </div>

          <div>
            <label>Nomor</label>
            <input v-model="form.nomor" class="w-full p-3 border rounded" />
          </div>

          <div>
            <label>Tahun</label>
            <input v-model="form.tahun" class="w-full p-3 border rounded" />
          </div>

          <!-- ===================== -->
          <!-- KATEGORI -->
          <!-- ===================== -->

          <!-- VITAL -->
          <div v-if="isVital">
            <label>Kategori Vital</label>
            <select v-model="form.id_kategori" class="w-full p-3 border rounded">
              <option value="">-- Pilih Kategori --</option>

              <option
                v-for="item in kategoriData"
                :key="item.id"
                :value="item.id"
              >
                {{ item.nama }}
              </option>
            </select>
          </div>

          <!-- AKTIF / INAKTIF -->
          <div v-else class="grid grid-cols-2 gap-4">

            <div>
              <label>Kelompok</label>
              <select v-model="form.kategori_kelompok" class="w-full p-3 border rounded">
                <option value="">-- Pilih --</option>
                <option v-for="(v,k) in kategoriAktifInaktif" :key="k" :value="k">
                  {{ k }}
                </option>
              </select>
            </div>

            <div>
              <label>Detail</label>
              <select v-model="form.id_kategori" class="w-full p-3 border rounded">
                <option value="">-- Pilih --</option>
                <option v-for="item in subKategoriList" :key="item" :value="item">
                  {{ item }}
                </option>
              </select>
            </div>

          </div>

          <!-- STATUS -->
          <div>
            <label>Status</label>
            <select v-model="form.status_akses" class="w-full p-3 border rounded">
              <option value="publik">Publik</option>
              <option value="private">Private</option>
            </select>
          </div>

          <div v-if="isPrivate">
            <label>Bidang</label>
            <select v-model="form.bagian" class="w-full p-3 border rounded">
              <option value="">-- Pilih --</option>
              <option v-for="b in bidangList" :key="b" :value="b">
                {{ b }}
              </option>
            </select>
          </div>

          <div>
            <label>Lokasi</label>
            <input v-model="form.lokasi" class="w-full p-3 border rounded" />
          </div>

          <div>
            <label>Deskripsi</label>
            <textarea v-model="form.deskripsi" class="w-full p-3 border rounded"></textarea>
          </div>

        </div>

        <!-- BUTTON -->
        <div class="flex justify-end gap-4 mt-10">
          <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded">
            Simpan
          </button>

          <button type="button" @click="goBack" class="bg-red-600 text-white px-6 py-2 rounded">
            Batal
          </button>
        </div>

      </form>
    </div>
  </div>
</template>