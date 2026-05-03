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

/* STATE */
const isPrivate = computed(() => form.status_akses === 'private')
const isDragging = ref(false)

const showDropdown = ref(false)

const selectedKategoriName = computed(() => {
  const findName = (data: any[]): any => {
    for (let item of data) {
      if (item.id == form.id_kategori) return item.nama
      if (item.children_recursive) {
        const found = findName(item.children_recursive)
        if (found) return found
      }
    }
  }
  return findName(kategoriTree.value) || ''
})

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

const triggerUpload = () => fileInput.value?.click()

const setFiles = (files: File[]) => {
  form.files = files

  filePreviews.value = files.map((file: any) => ({
    name: file.name,
    type: file.type,
    url: URL.createObjectURL(file)
  }))
}

const handleFileChange = (e: any) => {
  const files = Array.from(e.target.files || [])
  setFiles(files)
}

/* DRAG & DROP */
const handleDragOver = (e: DragEvent) => {
  e.preventDefault()
  isDragging.value = true
}

const handleDragLeave = () => {
  isDragging.value = false
}

const handleDrop = (e: DragEvent) => {
  e.preventDefault()
  isDragging.value = false

  const files = Array.from(e.dataTransfer?.files || [])
  setFiles(files)
}

/* KATEGORI (DB) */
const parents = computed(() =>
  props.kategoriData.filter((item: any) => !item.parent_id)
)

const getChildren = (parentId: number) => {
  return props.kategoriData.filter(
    (item: any) => item.parent_id === parentId
  )
}

    const buildTree = (data: any[], parentId: number | null = null) => {
      return data
        .filter(item => item.parent_id === parentId)
        .map(item => ({
          ...item,
          children_recursive: buildTree(data, item.id)
        }))
    }

const kategoriTree = computed(() => buildTree(props.kategoriData))

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
          <span class="inline-block bg-[#b8ccd5] text-gray-700 px-4 py-1 rounded-full text-sm font-bold shadow-sm ml-2">
            File Dokumen
          </span>

          <div
            @click="triggerUpload"
            @dragover.prevent="handleDragOver"
            @dragleave="handleDragLeave"
            @drop="handleDrop"
            :class="[
              'rounded-[35px] p-12 border-2 border-dashed flex flex-col items-center justify-center cursor-pointer transition-all shadow-inner relative',
              isDragging 
                ? 'border-blue-500 bg-blue-50 scale-[1.02]' 
                : 'bg-white border-gray-300 hover:bg-gray-50'
            ]"
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
        </div>

        <!-- FORM -->
        <div class="space-y-4 mt-6">

          <div>
            <label class="block font-black mb-1 text-sm uppercase text-gray-800">
              Judul Dokumen
            </label>
            <input v-model="form.judul"
              class="w-full p-4 bg-white text-black rounded-2xl border border-gray-300" />
          </div>

          <div>
            <label class="block font-black mb-1 text-sm uppercase text-gray-800">
              Nomor
            </label>
            <input v-model="form.nomor"
              class="w-full p-4 bg-white text-black rounded-2xl border border-gray-300" />
          </div>

          <div>
            <label class="block font-black mb-1 text-sm uppercase text-gray-800">
              Tahun
            </label>
            <input v-model="form.tahun"
              class="w-full p-4 bg-white text-black rounded-2xl border border-gray-300" />
          </div>

          <!-- kategori -->
          <div>
            <label class="block font-black mb-1 text-sm uppercase text-gray-800">
              Kategori
            </label>

            <div class="relative">

              <!-- BUTTON -->
              <div
                @click.stop="showDropdown = !showDropdown"
                class="w-full p-4 bg-white text-black rounded-2xl border border-gray-300 cursor-pointer flex justify-between items-center"
              >
                <span>
              {{ selectedKategoriName || 'Pilih Kategori' }}
            </span>
                <span>▼</span>
              </div>

              <!-- DROPDOWN -->
              <div
                v-show="showDropdown"
                class="absolute left-0 top-full mt-2 w-full bg-white border rounded-xl shadow-lg max-h-[300px] overflow-y-auto z-[9999]"
              >

                <TreeDropdown
              :data="kategoriTree"
              v-model="form.id_kategori"
            />

              </div>

            </div>
          </div>

          <!-- status -->
          <div>
            <label class="block font-black mb-1 text-sm uppercase text-gray-800">
              Status Akses
            </label>

            <select v-model="form.status_akses"
              class="w-full p-4 bg-white text-black rounded-2xl border border-gray-300">
              <option value="publik">Publik</option>
              <option value="private">Private</option>
            </select>
          </div>

          <!-- bidang -->
          <div v-if="isPrivate">
            <label class="block font-black mb-1 text-sm uppercase text-gray-800">
              Bidang
            </label>

            <select v-model="form.bagian"
              class="w-full p-4 bg-white text-black rounded-2xl border border-gray-300">

              <option value="">-- Pilih Bidang --</option>

              <option v-for="b in bidangList"
                :key="b"
                :value="b">
                {{ b }}
              </option>

            </select>
          </div>

          <div>
            <label class="block font-black mb-1 text-sm uppercase text-gray-800">
              Lokasi
            </label>
            <input v-model="form.lokasi"
              class="w-full p-4 bg-white text-black rounded-2xl border border-gray-300" />
          </div>

          <div>
            <label class="block font-black mb-1 text-sm uppercase text-gray-800">
              Deskripsi
            </label>
            <textarea v-model="form.deskripsi"
              class="w-full p-4 bg-white text-black rounded-2xl border border-gray-300"></textarea>
          </div>

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