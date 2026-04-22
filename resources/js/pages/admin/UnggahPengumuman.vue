<script setup>
import { ref } from 'vue'
import { Head } from '@inertiajs/vue3'
import AdminLayout from '@/layouts/AdminLayout.vue'
import { UploadCloud } from 'lucide-vue-next'

defineOptions({
  layout: AdminLayout
})

const form = ref({
  judul: '',
  deskripsi: '',
  tanggal: '',
  file: null
})

const handleFileUpload = (event) => {
  form.value.file = event.target.files[0]
}
</script>

<template>
  <Head title="Unggah Pengumuman" />

  <div class="p-8 bg-white min-h-screen">
    <div class="mb-10">
      <h1 class="text-4xl font-black text-black">
        Unggah Pengumuman
      </h1>
    </div>

    <div class="max-w-5xl bg-[#7fa6b3] rounded-[30px] p-10 shadow-lg relative border-4 border-[#3498db]/20">
      
      <div class="absolute -top-3 left-1/2 -translate-x-1/2 w-6 h-6 bg-white border-4 border-[#3498db] rounded-full"></div>

      <form @submit.prevent="" class="space-y-8">
        
        <div>
          <span class="inline-block bg-[#b3c8cf] text-gray-800 px-6 py-1.5 rounded-full font-bold text-sm mb-4 shadow-sm">
            File Dokumen
          </span>
          
          <div class="bg-white rounded-[25px] p-12 flex flex-col items-center justify-center border-2 border-dashed border-gray-300 hover:border-[#3498db] transition-colors cursor-pointer group relative">
            <input 
              type="file" 
              @change="handleFileUpload"
              class="absolute inset-0 opacity-0 cursor-pointer"
            />
            
            <UploadCloud class="w-16 h-16 text-black mb-4" />
            
            <p class="text-lg font-bold text-black mb-1">
              Klik untuk unggah atau drag and drop
            </p>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">
              PDF, DOC, XLS, JPG, PNG, MP3, MP4
            </p>
            <p class="text-xs text-gray-400">
              (Maks 10 MB)
            </p>
          </div>
        </div>

        <div class="space-y-6">
          <div class="space-y-2">
            <label class="block font-bold text-gray-900 ml-2">Judul Pengumuman</label>
            <input 
              v-model="form.judul"
              type="text" 
              class="w-full bg-white border-none rounded-full px-6 py-3 shadow-inner focus:ring-2 focus:ring-[#3498db] outline-none"
            />
          </div>

          <div class="space-y-2">
            <label class="block font-bold text-gray-900 ml-2">Deskripsi</label>
            <input 
              v-model="form.deskripsi"
              type="text" 
              class="w-full bg-white border-none rounded-full px-6 py-3 shadow-inner focus:ring-2 focus:ring-[#3498db] outline-none"
            />
          </div>

          <div class="space-y-2">
            <label class="block font-bold text-gray-900 ml-2">Tanggal</label>
            <input 
              v-model="form.tanggal"
              type="date" 
              class="w-full bg-white border-none rounded-full px-6 py-3 shadow-inner focus:ring-2 focus:ring-[#3498db] outline-none text-gray-600"
            />
          </div>
        </div>

        <div class="flex gap-4 pt-4">
          <button 
            type="submit"
            class="bg-[#b3c8cf] hover:bg-white hover:text-[#7fa6b3] text-gray-800 font-bold px-8 py-2.5 rounded-full transition-all shadow-md active:scale-95"
          >
            Simpan pengumuman
          </button>
          
          <button 
            type="button"
            class="bg-[#b3c8cf] hover:bg-red-500 hover:text-white text-gray-800 font-bold px-10 py-2.5 rounded-full transition-all shadow-md active:scale-95"
          >
            Batal
          </button>
        </div>

      </form>
    </div>
  </div>
</template>

<style scoped>
/* Menyesuaikan font agar lebih tegas sesuai mockup */
h1, label, p, span, button {
  font-family: 'Inter', sans-serif;
}

/* Custom style untuk menghilangkan icon date default jika ingin lebih bersih */
input[type="date"]::-webkit-calendar-picker-indicator {
  cursor: pointer;
  padding: 5px;
  filter: invert(0.5);
}
</style>