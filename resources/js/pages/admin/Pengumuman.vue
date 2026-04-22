<script setup>
import AdminLayout from '@/layouts/AdminLayout.vue'
import { ref } from 'vue'

defineOptions({
  layout: AdminLayout
})

const judul = ref('')
const deskripsi = ref('')
const tanggal = ref('')
const fileInput = ref(null)
const selectedFile = ref(null)

// Logic Upload
const handleFileChange = (e) => {
  selectedFile.value = e.target.files[0]
}

const handleDrop = (e) => {
  selectedFile.value = e.dataTransfer.files[0]
}

const triggerFileInput = () => {
  fileInput.value.click()
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
          @dragover.prevent 
          @drop.prevent="handleDrop"
          class="bg-white rounded-[40px] h-60 flex flex-col items-center justify-center text-center mb-8 cursor-pointer border-2 border-transparent hover:border-blue-100 transition-all group shadow-inner"
        >
          <input type="file" ref="fileInput" class="hidden" @change="handleFileChange" />
          
          <svg class="w-14 h-14 text-black mb-3 group-hover:scale-105 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
          </svg>

          <p class="text-lg font-bold text-black px-4 truncate max-w-full">
            {{ selectedFile ? selectedFile.name : 'Klik untuk unggah atau drag and drop' }}
          </p>
          <p v-if="!selectedFile" class="text-xs text-black opacity-70">
            PDF, DOC, XLS, JPG, PNG, MP3, MP4
          </p>
          <p class="text-xs text-black mt-4 font-medium opacity-60">(Maks xx MB)</p>
        </div>

        <div class="space-y-3 px-2">

          <div>
            <label class="block text-sm font-bold text-black mb-0.5 ml-1">Judul Pengumuman</label>
            <input 
              v-model="judul"
              type="text"
              class="w-full max-w-3xl h-7.5 bg-white rounded-full border-none px-5 outline-none focus:ring-2 focus:ring-blue-200 text-sm shadow-sm" 
            />
          </div>

          <div>
            <label class="block text-sm font-bold text-black mb-0.5 ml-1">Deskripsi</label>
            <input 
              v-model="deskripsi"
              type="text"
              class="w-full max-w-3xl h-7.5 bg-white rounded-full border-none px-5 outline-none focus:ring-2 focus:ring-blue-200 text-sm shadow-sm" 
            />
          </div>

          <div>
            <label class="block text-sm font-bold text-black mb-0.5 ml-1">Tanggal</label>
            <input 
              v-model="tanggal"
              type="date"
              class="w-full max-w-xs h-7.5 bg-white rounded-full border-none px-5 outline-none focus:ring-2 focus:ring-blue-200 text-sm shadow-sm" 
            />
          </div>

        </div>

        <div class="flex gap-4 mt-8 px-2">
          <button
            class="bg-[#bcd1da] px-8 py-2 rounded-full text-black font-bold text-sm shadow hover:bg-[#a8c1cc] transition active:scale-95">
            Simpan pengumuman
          </button>

          <button
            @click="selectedFile = null; judul = ''; deskripsi = ''; tanggal = ''"
            class="bg-[#bcd1da] px-10 py-2 rounded-full text-black font-bold text-sm shadow hover:bg-[#a8c1cc] transition active:scale-95">
            Batal
          </button>
        </div>

      </div>

    </div>
  </div>
</template>