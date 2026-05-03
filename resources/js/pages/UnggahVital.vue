<script setup lang="ts">
import { ref, computed } from 'vue'
import { Head, useForm } from '@inertiajs/vue3'
import UserLayout from '@/layouts/UserLayout.vue'
import { UploadCloud } from 'lucide-vue-next'
import TreeDropdown from '@/components/TreeDropdown.vue'

defineOptions({ layout: UserLayout })

const props = defineProps<{
  folder: string,
  kategoriData: any[]
}>()

/* FORM */
const form = useForm({
  judul: '',
  nomor: '',
  tahun: '',
  status_akses: 'publik',
  id_kategori: '',
  lokasi: '',
  deskripsi: '',
  files: null as any,
  bagian: '',
  folder: props.folder
})

/* PRIVATE */
const isPrivate = computed(() => form.status_akses === 'private')

/* BIDANG */
const bidangList = [
  'Sekretariat',
  'Rehabilitasi Sosial',
  'Perlindungan dan Jaminan Sosial',
  'Pemberdayaan Sosial'
]

/* FILE UPLOAD */
const fileInput = ref<HTMLInputElement | null>(null)
const filePreviews = ref<any[]>([])
const isDragging = ref(false)

const triggerUpload = () => fileInput.value?.click()

const handleFiles = (files: File[]) => {
  form.files = files

  filePreviews.value = files.map((file: any) => ({
    name: file.name,
    type: file.type,
    url: URL.createObjectURL(file)
  }))
}

const handleFileChange = (e: any) => {
  handleFiles(Array.from(e.target.files || []))
}

/* DRAG & DROP */
const handleDrop = (e: DragEvent) => {
  e.preventDefault()
  isDragging.value = false

  const files = Array.from(e.dataTransfer?.files || [])
  handleFiles(files)
}

const handleDragOver = (e: DragEvent) => {
  e.preventDefault()
  isDragging.value = true
}

const handleDragLeave = () => {
  isDragging.value = false
}

/* ACTION */
const goBack = () => window.history.back()

const submit = () => {
  form.post('/arsip', {
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

      <form @submit.prevent="submit"
        class="bg-[#7fa1b1] p-10 rounded-[40px] shadow-lg">

        <!-- FILE UPLOAD -->
        <div class="space-y-2">
          <span class="inline-block bg-[#b8ccd5] text-gray-700 px-4 py-1 rounded-full text-sm font-bold ml-2">
            File Dokumen
          </span>

          <div
            @click="triggerUpload"
            @drop="handleDrop"
            @dragover="handleDragOver"
            @dragleave="handleDragLeave"
            class="bg-white rounded-[35px] p-12 border-2 border-dashed flex flex-col items-center justify-center cursor-pointer transition-all shadow-inner relative"
            :class="isDragging ? 'border-blue-500 bg-blue-50' : 'border-gray-300 hover:bg-gray-50'"
          >

            <!-- jumlah file -->
            <div v-if="form.files && form.files.length > 0"
              class="mb-4 text-center absolute top-4">
              <span class="bg-blue-600 text-white px-4 py-1 rounded-full font-bold uppercase text-[10px]">
                {{ form.files.length }} Item Terpilih
              </span>
            </div>

            <!-- preview -->
            <div v-if="filePreviews.length"
              class="mt-6 grid grid-cols-2 md:grid-cols-3 gap-4 w-full">

              <div v-for="(file, index) in filePreviews"
                :key="index"
                class="bg-white rounded-xl p-3 shadow text-center">

                <img v-if="file.type.startsWith('image')"
                  :src="file.url"
                  class="w-full h-32 object-cover rounded-lg mb-2" />

                <div v-else class="text-gray-500 text-sm mb-2">
                  📄 File
                </div>

                <p class="text-xs font-bold truncate">
                  {{ file.name }}
                </p>
              </div>
            </div>

            <!-- input -->
            <input type="file"
              ref="fileInput"
              class="hidden"
              @change="handleFileChange"
              multiple />

            <!-- empty -->
            <div v-if="!filePreviews.length"
              class="text-center flex flex-col items-center">

              <div class="bg-[#7fa1b1]/20 p-6 rounded-full mb-4 shadow-inner">
                <UploadCloud class="w-12 h-12 text-[#2f55a4]" />
              </div>

              <p class="font-black text-gray-900 text-xl">
                Klik atau Drag File
              </p>

              <p class="text-gray-600 text-sm mt-1">
                Drop file di sini
              </p>

              <p class="text-gray-400 text-xs mt-2">
                PDF, DOC, XLS, JPG, PNG, MP3, MP4
              </p>
            </div>

          </div>
        </div>

        <!-- FORM -->
        <div class="space-y-4 mt-6">

          <input v-model="form.judul" placeholder="Judul"
            class="w-full p-4 bg-white rounded-2xl border" />

          <input v-model="form.nomor" placeholder="Nomor"
            class="w-full p-4 bg-white rounded-2xl border" />

          <input v-model="form.tahun" placeholder="Tahun"
            class="w-full p-4 bg-white rounded-2xl border" />

          <!-- kategori -->
          <select v-model="form.id_kategori"
            class="w-full p-4 bg-white rounded-2xl border">
            <option value="">-- Pilih Kategori --</option>
            <option v-for="item in props.kategoriData"
              :key="item.id"
              :value="item.id">
              {{ item.nama }}
            </option>
          </select>

          <!-- status -->
          <select v-model="form.status_akses"
            class="w-full p-4 bg-white rounded-2xl border">
            <option value="publik">Publik</option>
            <option value="private">Private</option>
          </select>

          <!-- bidang -->
          <select v-if="isPrivate"
            v-model="form.bagian"
            class="w-full p-4 bg-white rounded-2xl border">
            <option value="">-- Pilih Bidang --</option>
            <option v-for="b in bidangList" :key="b">
              {{ b }}
            </option>
          </select>

          <input v-model="form.lokasi" placeholder="Lokasi"
            class="w-full p-4 bg-white rounded-2xl border" />

          <textarea v-model="form.deskripsi"
            class="w-full p-4 bg-white rounded-2xl border"></textarea>

        </div>

        <!-- BUTTON -->
        <div class="flex justify-end gap-4 mt-10">
          <button type="submit"
            class="bg-blue-700 text-white px-8 py-3 rounded-xl font-bold">
            Simpan
          </button>

          <button type="button"
            @click="goBack"
            class="bg-red-600 text-white px-8 py-3 rounded-xl font-bold">
            Batal
          </button>
        </div>

      </form>
    </div>
  </div>
</template>