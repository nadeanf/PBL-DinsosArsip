<script setup lang="ts">
import { ref, computed } from 'vue'
import { Head, useForm } from '@inertiajs/vue3'
import AuthLayout from '@/layouts/AuthLayout.vue'

defineOptions({ layout: AuthLayout })

const props = defineProps<{
  folder: string,
  kategoriData: any[]
}>()

const goBack = () => window.history.back()

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

const isPrivate = computed(() => form.status_akses === 'private')

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

// 🔥 ambil parent (kategori utama)
const parents = computed(() =>
  props.kategoriData.filter((item: any) => !item.parent_id)
)

// 🔥 ambil child
const getChildren = (parentId: number) => {
  return props.kategoriData.filter(
    (item: any) => item.parent_id === parentId
  )
}

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

        <!-- FILE UPLOAD -->
        <div class="space-y-2">
          <span class="inline-block bg-[#b8ccd5] text-gray-700 px-4 py-1 rounded-full text-sm font-bold shadow-sm ml-2">
            File Dokumen
          </span>

          <div
            @click="triggerUpload"
            class="bg-white rounded-[35px] p-12 border-2 border-dashed border-gray-300 flex flex-col items-center justify-center cursor-pointer hover:bg-gray-50 transition-all shadow-inner relative"
          >

            <div v-if="form.files && form.files.length > 0" class="mb-4 text-center absolute top-4">
              <span class="bg-blue-600 text-white px-4 py-1 rounded-full font-bold uppercase text-[10px]">
                {{ form.files.length }} Item Terpilih
              </span>
            </div>

            <div v-if="filePreviews.length" class="mt-6 grid grid-cols-2 md:grid-cols-3 gap-4 w-full">
              <div v-for="(file, index) in filePreviews" :key="index" class="bg-white rounded-xl p-3 shadow text-center">

                <img v-if="file.type.startsWith('image')" :src="file.url" class="w-full h-32 object-cover rounded-lg mb-2" />
                <div v-else class="text-gray-500 text-sm mb-2">📄 File</div>

                <p class="text-xs font-bold truncate">{{ file.name }}</p>
              </div>
            </div>

            <input type="file" ref="fileInput" class="hidden" @change="handleFileChange" multiple />

            <div v-if="!filePreviews.length" class="text-center">
              <p class="font-bold text-gray-900 text-xl">Klik untuk unggah atau drag and drop</p>
              <p class="text-gray-600 text-sm mt-2">PDF, DOC, XLS, JPG, PNG, MP3, MP4</p>
            </div>

          </div>
        </div>

        <!-- FORM INPUT -->
        <div class="space-y-4 mt-6">

  <div>
    <label class="block font-black mb-1 text-sm uppercase text-gray-800">Judul Dokumen</label>
    <input v-model="form.judul" class="w-full p-4 bg-white text-black rounded-2xl border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none" />
  </div>

  <div>
    <label class="block font-black mb-1 text-sm uppercase text-gray-800">Nomor</label>
    <input v-model="form.nomor" class="w-full p-4 bg-white text-black rounded-2xl border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none" />
  </div>

  <div>
    <label class="block font-black mb-1 text-sm uppercase text-gray-800">Tahun</label>
    <input v-model="form.tahun" class="w-full p-4 bg-white text-black rounded-2xl border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none" />
  </div>

  <div>
    <label class="block text-black font-black mb-1.5 ml-3 text-sm uppercase">Kategori</label>
    <select
      v-model="form.id_kategori"
      class="w-full p-4 bg-white text-black font-medium rounded-2xl border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none"
    >
      <option value="" class="text-black">-- Pilih Kategori --</option>
      <optgroup
        v-for="parent in parents"
        :key="parent.id"
        :label="parent.nama"
        class="text-black font-bold"
      >
        <option
          v-for="child in getChildren(parent.id)"
          :key="child.id"
          :value="child.id"
          class="text-black font-normal"
        >
          {{ child.nama }}
        </option>
      </optgroup>
    </select>
  </div>

  <div>
    <label class="block font-black mb-1 text-sm uppercase text-gray-800">Status Akses</label>
    <select v-model="form.status_akses" class="w-full p-4 bg-white text-black rounded-2xl border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none">
      <option value="publik" class="text-black">Publik</option>
      <option value="private" class="text-black">Private</option>
    </select>
  </div>

  <div v-if="isPrivate">
    <label class="block font-black mb-1 text-sm uppercase text-gray-800">Bidang</label>
    <input v-model="form.bagian" class="w-full p-4 bg-white text-black rounded-2xl border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none" />
  </div>

  <div>
    <label class="block font-black mb-1 text-sm uppercase text-gray-800">Lokasi</label>
    <input v-model="form.lokasi" class="w-full p-4 bg-white text-black rounded-2xl border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none" />
  </div>

  <div>
    <label class="block font-black mb-1 text-sm uppercase text-gray-800">Deskripsi</label>
    <textarea v-model="form.deskripsi" class="w-full p-4 bg-white text-black rounded-2xl border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none min-h-[100px]"></textarea>
  </div>

</div>

        <!-- BUTTON -->
        <div class="flex justify-end gap-4 mt-10">
          <button type="submit" class="bg-blue-700 text-white px-8 py-3 rounded-xl font-bold">
            Simpan
          </button>

          <button type="button" @click="goBack" class="bg-red-600 text-white px-8 py-3 rounded-xl font-bold">
            Batal
          </button>
        </div>

      </form>
    </div>
  </div>
</template>