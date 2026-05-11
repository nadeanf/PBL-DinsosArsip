<script setup>
import AdminLayout from '@/layouts/AdminLayout.vue'
import { UploadCloud } from 'lucide-vue-next'
import { useForm } from '@inertiajs/vue3'
import { ref } from 'vue'

defineOptions({
  layout: AdminLayout
})

const form = useForm({
  judul: '',
  deskripsi: '',
  tanggal: '',
  file: null
})
const fileInput = ref(null)
const selectedFile = ref(null)
const filePreviews = ref([])
const isDragging = ref(false)

const triggerFileInput = () => {
  fileInput.value.click()
}

const setFiles = (files) => {
  selectedFile.value = files[0]

  form.file = files[0]

  filePreviews.value = files.map((file) => ({
    name: file.name,
    type: file.type,
    url: URL.createObjectURL(file)
  }))
}

const handleFileChange = (e) => {
  const files = Array.from(e.target.files || [])
  setFiles(files)
}

/* DRAG & DROP */
const handleDragOver = (e) => {
  e.preventDefault()
  isDragging.value = true
}

const handleDragLeave = () => {
  isDragging.value = false
}

const handleDrop = (e) => {
  e.preventDefault()
  isDragging.value = false

  const files = Array.from(e.dataTransfer.files || [])
  setFiles(files)
}

const submit = () => {
  form.post('/admin/pengumuman', {
    forceFormData: true,

    onSuccess: () => {
      // reset inertia form
      form.reset()

      // reset preview
      selectedFile.value = null
      filePreviews.value = []

      // notif
      alert('Pengumuman berhasil diupload!')
    }
  })
}
</script>

<template>
  <div class="flex justify-center items-start pt-10 min-h-screen">
    
    <div class="w-full max-w-4xl px-6">
      
      <h1 class="text-4xl font-extrabold text-black mb-6">
        Unggah Pengumuman
      </h1>

      <div class="bg-[#7fa0ad] rounded-2xl p-10 shadow-xl">

        <div class="mb-2 ml-1">
          <span class="bg-[#bcd1da] text-black font-bold px-5 py-0.5 rounded-full text-[11px] uppercase tracking-wider">
            File Dokumen
          </span>
        </div>

        <div
  @click="triggerFileInput"
  @dragover.prevent="handleDragOver"
  @dragleave="handleDragLeave"
  @drop="handleDrop"
  :class="[
    'rounded-[35px] p-12 border-2 border-dashed flex flex-col items-center justify-center cursor-pointer transition-all shadow-inner relative min-h-[260px]',
    isDragging
      ? 'border-blue-500 bg-blue-50 scale-[1.02]'
      : 'bg-white border-gray-300 hover:bg-gray-50'
  ]"
>

  <!-- jumlah file -->
  <div
    v-if="selectedFile"
    class="mb-4 text-center absolute top-4"
  >
    <span class="bg-blue-600 text-white px-4 py-1 rounded-full font-bold uppercase text-[10px]">
      1 Item Terpilih
    </span>
  </div>

  <!-- preview -->
  <div
    v-if="filePreviews.length"
    class="mt-6 grid grid-cols-2 md:grid-cols-3 gap-4 w-full"
  >

    <div
      v-for="(file, index) in filePreviews"
      :key="index"
      class="bg-white rounded-xl p-3 shadow text-center"
    >

      <!-- IMAGE -->
      <img
        v-if="file.type.startsWith('image')"
        :src="file.url"
        class="w-full h-32 object-cover rounded-lg mb-2"
      />

      <!-- NON IMAGE -->
      <div
        v-else
        class="text-gray-500 text-4xl mb-2"
      >
        📄
      </div>

      <p class="text-xs font-bold truncate">
        {{ file.name }}
      </p>

    </div>

  </div>

  <!-- input -->
  <input
    type="file"
    ref="fileInput"
    class="hidden"
    @change="handleFileChange"
  />

  <!-- empty -->
  <div
    v-if="!filePreviews.length"
    class="text-center flex flex-col items-center"
  >

    <div class="bg-[#7fa1b1]/20 p-6 rounded-full mb-4 shadow-inner">
      <UploadCloud class="w-12 h-12 text-[#2f55a4]" />
    </div>

    <p class="font-black text-gray-900 text-xl">
      Klik untuk unggah
    </p>

    <p class="text-gray-600 text-sm mt-1">
      atau drag & drop file di sini
    </p>

    <p class="text-gray-400 text-xs mt-2">
      PDF, DOC, XLS, JPG, PNG, MP3, MP4
    </p>

  </div>

</div>

        <div class="space-y-3 px-2">

          <div>
            <label class="block text-sm font-bold text-black mb-0.5 ml-1">Judul Pengumuman</label>
            <input 
              v-model="form.judul"
              type="text"
              class="w-full max-w-3xl h-7.5 bg-white rounded-full border-none px-5 outline-none focus:ring-2 focus:ring-blue-200 text-sm shadow-sm" 
            />
          </div>

          <div>
            <label class="block text-sm font-bold text-black mb-0.5 ml-1">Deskripsi</label>
            <input 
              v-model="form.deskripsi"
              type="text"
              class="w-full max-w-3xl h-7.5 bg-white rounded-full border-none px-5 outline-none focus:ring-2 focus:ring-blue-200 text-sm shadow-sm" 
            />
          </div>

          <div>
            <label class="block text-sm font-bold text-black mb-0.5 ml-1">Tanggal</label>
            <input 
              v-model="form.tanggal"
              type="date"
              class="w-full max-w-xs h-7.5 bg-white rounded-full border-none px-5 outline-none focus:ring-2 focus:ring-blue-200 text-sm shadow-sm" 
            />
          </div>

        </div>

        <div class="flex gap-4 mt-8 px-2">
          <button
            type="button"
            @click="submit"
            class="bg-[#bcd1da] px-8 py-2 rounded-full text-black font-bold text-sm shadow hover:bg-[#a8c1cc] transition active:scale-95">
            Simpan pengumuman
          </button>

          <button
            @click="
              selectedFile = null;
              filePreviews = [];
              form.reset();
            "
            class="bg-[#bcd1da] px-10 py-2 rounded-full text-black font-bold text-sm shadow hover:bg-[#a8c1cc] transition active:scale-95">
            Batal
          </button>
        </div>

      </div>

    </div>
  </div>
</template>