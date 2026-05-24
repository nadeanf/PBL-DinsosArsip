<script setup>
import AdminLayout from '@/layouts/AdminLayout.vue'
import { UploadCloud, X } from 'lucide-vue-next'
import { useForm } from '@inertiajs/vue3'
import { ref } from 'vue'

defineOptions({
  layout: AdminLayout
})

const props = defineProps({
  pengumuman: Object,
  trashed: Object
})

const activeTab = ref('tambah')

const form = useForm({
  judul: '',
  deskripsi: '',
  tanggal: '',
  file: null
})

// Edit state
const editingId = ref(null)
const editForm = useForm({
  judul: '',
  deskripsi: '',
  tanggal: '',
  file: null
})
const editFileInput = ref(null)
const editSelectedFile = ref(null)
const editFilePreviews = ref([])
const editIsDragging = ref(false)

// Delete state
const showDeleteModal = ref(false)
const deleteTargetId = ref(null)

// Permanent delete state
const showPermanentDeleteModal = ref(false)
const permanentDeleteTargetId = ref(null)

// PREVIEW MODAL
const previewModal = ref(false)
const selectedPreview = ref(null)

const getFileType = (path) => {
  if (!path) return 'FILE'

  const ext = path.split('.').pop()?.toLowerCase()

  if (ext === 'pdf') return 'PDF'

  if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext)) {
    return 'IMAGE'
  }

  return 'FILE'
}

const openPreview = (item) => {
  selectedPreview.value = item
  previewModal.value = true
}

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
      form.reset()

      selectedFile.value = null
      filePreviews.value = []

      alert('Pengumuman berhasil diupload!')
    }
  })
}

// EDIT HANDLERS
const handleEdit = (item) => {
  editingId.value = item.id
  editForm.judul = item.judul
  editForm.deskripsi = item.deskripsi
  editForm.tanggal = item.tanggal
  editSelectedFile.value = null
  editFilePreviews.value = item.file ? [{
    name: item.file.split('/').pop(),
    type: 'image/jpeg',
    url: `/storage/${item.file}`
  }] : []
}

const handleEditFileChange = (e) => {
  const files = Array.from(e.target.files || [])
  if (files.length > 0) {
    editSelectedFile.value = files[0]
    editForm.file = files[0]
    
    editFilePreviews.value = files.map((file) => ({
      name: file.name,
      type: file.type,
      url: URL.createObjectURL(file)
    }))
  }
}

const handleEditDragOver = (e) => {
  e.preventDefault()
  editIsDragging.value = true
}

const handleEditDragLeave = () => {
  editIsDragging.value = false
}

const handleEditDrop = (e) => {
  e.preventDefault()
  editIsDragging.value = false

  const files = Array.from(e.dataTransfer.files || [])
  if (files.length > 0) {
    editSelectedFile.value = files[0]
    editForm.file = files[0]
    
    editFilePreviews.value = files.map((file) => ({
      name: file.name,
      type: file.type,
      url: URL.createObjectURL(file)
    }))
  }
}

const handleUpdatePengumuman = () => {
  editForm.put(`/admin/pengumuman/${editingId.value}`, {
    forceFormData: true,
    
    onSuccess: () => {
  alert('Pengumuman berhasil diperbarui!')
},

onError: (errors) => {
  console.log(errors)
  alert('Gagal memperbarui pengumuman!')
}
  })
}

const handleCancelEdit = () => {
  editingId.value = null
  editForm.reset()
  editSelectedFile.value = null
  editFilePreviews.value = []
}

// DELETE HANDLERS
const showDeleteConfirm = (id) => {
  deleteTargetId.value = id
  showDeleteModal.value = true
}

const handleDeletePengumuman = () => {
  const form = useForm({})
  form.delete(`/admin/pengumuman/${deleteTargetId.value}`, {
    onSuccess: () => {
      showDeleteModal.value = false
      deleteTargetId.value = null
      alert('Pengumuman berhasil dihapus!')
    }
  })
}

const handleCancelDelete = () => {
  showDeleteModal.value = false
  deleteTargetId.value = null
}

// RESTORE HANDLER
const handleRestorePengumuman = (id) => {
  if (confirm('Pulihkan pengumuman ini dari sampah?')) {
    const form = useForm({})
    form.post(`/admin/pengumuman/${id}/restore`, {
      onSuccess: () => {
        alert('Pengumuman berhasil dipulihkan!')
      }
    })
  }
}

// PERMANENT DELETE HANDLER
const showPermanentDeleteConfirm = (id) => {
  permanentDeleteTargetId.value = id
  showPermanentDeleteModal.value = true
}

const handlePermanentDeletePengumuman = () => {
  const form = useForm({})
  form.delete(`/admin/pengumuman/${permanentDeleteTargetId.value}/permanent`, {
    onSuccess: () => {
      showPermanentDeleteModal.value = false
      permanentDeleteTargetId.value = null
      alert('Pengumuman berhasil dihapus permanent!')
    }
  })
}

const handleCancelPermanentDelete = () => {
  showPermanentDeleteModal.value = false
  permanentDeleteTargetId.value = null
}
</script>

<template>
  <div class="flex justify-center items-start pt-10 min-h-screen">

    <div class="w-full max-w-4xl px-6">

      <!-- JUDUL -->
      <h1 class="text-4xl font-extrabold text-black mb-6">
        Pengumuman
      </h1>

      <!-- TAB -->
      <div class="flex gap-3 mb-6">

        <button
          @click="activeTab = 'tambah'"
          :class="[
            'px-6 py-3 rounded-2xl font-bold text-sm transition',
            activeTab === 'tambah'
              ? 'bg-[#2f55a4] text-white shadow-lg'
              : 'bg-white text-gray-700'
          ]"
        >
          Tambah Pengumuman
        </button>

        <button
          @click="activeTab = 'kelola'"
          :class="[
            'px-6 py-3 rounded-2xl font-bold text-sm transition',
            activeTab === 'kelola'
              ? 'bg-[#2f55a4] text-white shadow-lg'
              : 'bg-white text-gray-700'
          ]"
        >
          Kelola Pengumuman
        </button>

        <button
          @click="activeTab = 'sampah'"
          :class="[
            'px-6 py-3 rounded-2xl font-bold text-sm transition',
            activeTab === 'sampah'
              ? 'bg-red-600 text-white shadow-lg'
              : 'bg-white text-gray-700'
          ]"
        >
          Sampah
        </button>

      </div>

      <!-- ========================= -->
      <!-- TAMBAH PENGUMUMAN -->
      <!-- ========================= -->

      <div v-if="activeTab === 'tambah'">

        <div class="bg-[#7fa0ad] rounded-2xl p-10 shadow-xl">

          <!-- LABEL -->
          <div class="mb-2 ml-1">
            <span class="bg-[#bcd1da] text-black font-bold px-5 py-0.5 rounded-full text-[11px] uppercase tracking-wider">
              File Dokumen
            </span>
          </div>

          <!-- DROP AREA -->
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

            <!-- EMPTY -->
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

            <!-- INPUT -->
            <input
              type="file"
              ref="fileInput"
              class="hidden"
              @change="handleFileChange"
            />

          </div>

          <!-- FORM -->
          <div class="space-y-4 px-2 mt-8">

            <div>
              <label class="block text-sm font-bold text-black mb-1 ml-1">
                Judul Pengumuman
                <span class="text-red-600">*</span>
              </label>

              <input
                v-model="form.judul"
                type="text"
                class="w-full h-12 bg-white rounded-full border-none px-5 outline-none focus:ring-2 focus:ring-blue-200 text-sm shadow-sm"
              />
            </div>

            <div>
              <label class="block text-sm font-bold text-black mb-1 ml-1">
                Deskripsi
                <span class="text-red-600">*</span>
              </label>

              <input
                v-model="form.deskripsi"
                type="text"
                class="w-full h-12 bg-white rounded-full border-none px-5 outline-none focus:ring-2 focus:ring-blue-200 text-sm shadow-sm"
              />
            </div>

            <div>
              <label class="block text-sm font-bold text-black mb-1 ml-1">
                Tanggal
                <span class="text-red-600">*</span>
              </label>

              <input
                v-model="form.tanggal"
                type="date"
                class="w-full max-w-xs h-12 bg-white rounded-full border-none px-5 outline-none focus:ring-2 focus:ring-blue-200 text-sm shadow-sm"
              />
            </div>

          </div>

          <!-- BUTTON -->
          <div class="flex gap-4 mt-8 px-2">

            <button
              type="button"
              @click="submit"
              class="bg-[#bcd1da] px-8 py-3 rounded-full text-black font-bold text-sm shadow hover:bg-[#a8c1cc] transition active:scale-95"
            >
              Simpan Pengumuman
            </button>

            <button
              @click="
                selectedFile = null;
                filePreviews = [];
                form.reset();
              "
              class="bg-[#bcd1da] px-10 py-3 rounded-full text-black font-bold text-sm shadow hover:bg-[#a8c1cc] transition active:scale-95"
            >
              Batal
            </button>

          </div>

        </div>

      </div>

      <!-- ========================= -->
      <!-- KELOLA PENGUMUMAN -->
      <!-- ========================= -->

      <div v-if="activeTab === 'kelola'">

        <div class="bg-[#7fa0ad] rounded-2xl p-10 shadow-xl">

          <h2 class="text-2xl font-black text-white mb-8">
            Daftar Pengumuman
          </h2>

          <div
            v-if="pengumuman.data.length"
            class="grid grid-cols-1 md:grid-cols-2 gap-6"
          >

            <div
             v-for="item in pengumuman.data"
            :key="item.id"
            @click="openPreview(item)"
            class="bg-white rounded-[28px] p-5 shadow-lg min-h-[380px] flex flex-col justify-between hover:-translate-y-1 hover:shadow-2xl transition-all duration-300 cursor-pointer"
            >

              <!-- IMAGE -->
              <div class="mb-4">

                <img
                  v-if="item.file"
                  :src="`/storage/${item.file}`"
                  class="w-full h-44 object-cover rounded-2xl"
                />

              </div>

              <!-- TITLE -->
              <h2 class="font-black text-lg text-gray-900">
                {{ item.judul }}
              </h2>

              <!-- DESC -->
              <p class="text-sm text-gray-500 mt-2 line-clamp-2">
                {{ item.deskripsi }}
              </p>

              <!-- DATE -->
              <div class="mt-4 text-xs text-gray-400 font-semibold">
                {{ item.tanggal }}
              </div>

              <!-- BUTTON -->
              <div class="flex gap-3 mt-5">

                <button
                  @click.stop="handleEdit(item)"
                  class="bg-yellow-400 hover:bg-yellow-500 transition px-5 py-2 rounded-full text-sm font-bold"
                >
                  Edit
                </button>

                <button
                  @click.stop="showDeleteConfirm(item.id)"
                  class="bg-red-500 hover:bg-red-600 text-white transition px-5 py-2 rounded-full text-sm font-bold"
                >
                  Hapus
                </button>

              </div>

            </div>

          </div>

          <!-- EMPTY -->
          <div
            v-else
            class="bg-white rounded-3xl p-10 text-center"
          >

            <p class="text-gray-500 font-semibold">
              Belum ada pengumuman
            </p>

          </div>

          <!-- PAGINATION -->
<div class="flex justify-center mt-8 gap-2 flex-wrap">

  <button
    v-for="(link, index) in pengumuman.links"
       :key="index"
          v-html="link.label"
            :disabled="!link.url"
            @click="$inertia.visit(link.url)"
            class="px-4 py-2 rounded-lg text-sm font-bold transition"
            :class="[
             link.active
            ? 'bg-blue-600 text-white'
            : 'bg-white text-gray-700 hover:bg-gray-100',
            !link.url && 'opacity-50 cursor-not-allowed'
            ]"
            />  

          </div>
        </div>

      </div>

      <!-- ========================= -->
      <!-- SAMPAH / TRASH -->
      <!-- ========================= -->

      <div v-if="activeTab === 'sampah'">

        <div class="bg-[#7fa0ad] rounded-2xl p-10 shadow-xl">

          <h2 class="text-2xl font-black text-white mb-8">
            Sampah Pengumuman
          </h2>

          <p class="text-white text-sm mb-6">
            Pengumuman di sini akan dihapus permanent setelah 30 hari.
          </p>

          <div
  v-if="trashed.data.length"
  class="grid grid-cols-1 md:grid-cols-2 gap-6"
>

            <div
              v-for="item in trashed.data"
              :key="item.id"
              class="bg-white rounded-[28px] p-5 shadow-lg opacity-75 min-h-[380px] flex flex-col justify-between hover:shadow-xl transition"
            >

              <!-- IMAGE -->
              <div class="mb-4">

                <img
                  v-if="item.file"
                  :src="`/storage/${item.file}`"
                  class="w-full h-44 object-cover rounded-2xl opacity-60"
                />

              </div>

              <!-- TITLE -->
              <h2 class="font-black text-lg text-gray-900">
                {{ item.judul }}
              </h2>

              <!-- DESC -->
              <p class="text-sm text-gray-500 mt-2">
                {{ item.deskripsi }}
              </p>

              <!-- DATE -->
              <div class="mt-4 text-xs text-gray-400 font-semibold">
                {{ item.tanggal }}
              </div>

              <!-- BUTTON -->
              <div class="flex gap-3 mt-5">

                <button
                  @click="handleRestorePengumuman(item.id)"
                  class="bg-green-500 hover:bg-green-600 text-white transition px-5 py-2 rounded-full text-sm font-bold flex-1"
                >
                  Pulihkan
                </button>

                <button
                  @click="showPermanentDeleteConfirm(item.id)"
                  class="bg-red-600 hover:bg-red-700 text-white transition px-5 py-2 rounded-full text-sm font-bold flex-1"
                >
                  Hapus Permanent
                </button>

              </div>

            </div>

          </div>

          <!-- PAGINATION SAMPAH -->
<div
  v-if="trashed.links"
  class="flex justify-center mt-8 gap-2 flex-wrap"
>

  <button
    v-for="(link, index) in trashed.links"
    :key="index"
    v-html="link.label"
    :disabled="!link.url"
    @click="$inertia.visit(link.url)"
    class="px-4 py-2 rounded-lg text-sm font-bold transition"
    :class="[
      link.active
        ? 'bg-red-600 text-white'
        : 'bg-white text-gray-700 hover:bg-gray-100',
      !link.url && 'opacity-50 cursor-not-allowed'
    ]"
  />

</div>

          <!-- EMPTY -->
          <div
            v-else
            class="bg-white rounded-3xl p-10 text-center"
          >

            <p class="text-gray-500 font-semibold">
              Sampah kosong
            </p>

          </div>

        </div>

      </div>

      

    </div>
    <div
      v-if="editingId"
      class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4"
    >

      <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto p-8 shadow-2xl">

        <div class="flex justify-between items-center mb-6">
          <h2 class="text-2xl font-black text-gray-900">
            Edit Pengumuman
          </h2>
          <button
            @click="handleCancelEdit"
            class="text-gray-400 hover:text-gray-600 transition"
          >
            <X class="w-6 h-6" />
          </button>
        </div>

        <!-- FILE UPLOAD -->
        <div class="mb-6">
          <div class="mb-2 ml-1">
            <span class="bg-blue-100 text-blue-900 font-bold px-5 py-0.5 rounded-full text-[11px] uppercase tracking-wider">
              File Dokumen (Opsional)
            </span>
          </div>

          <div
            @click="editFileInput.click()"
            @dragover.prevent="handleEditDragOver"
            @dragleave="handleEditDragLeave"
            @drop="handleEditDrop"
            :class="[
              'rounded-2xl p-8 border-2 border-dashed flex flex-col items-center justify-center cursor-pointer transition-all shadow-inner',
              editIsDragging
                ? 'border-blue-500 bg-blue-50 scale-[1.02]'
                : 'bg-gray-50 border-gray-300 hover:bg-gray-100'
            ]"
          >

            <!-- preview -->
            <div
              v-if="editFilePreviews.length"
              class="grid grid-cols-2 gap-4 w-full"
            >

              <div
                v-for="(file, index) in editFilePreviews"
                :key="index"
                class="bg-white rounded-xl p-3 shadow text-center relative"
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


            <!-- EMPTY -->
            <div
              v-if="!editFilePreviews.length"
              class="text-center flex flex-col items-center"
            >

              <div class="bg-blue-100 p-4 rounded-full mb-3">
                <UploadCloud class="w-8 h-8 text-blue-600" />
              </div>

              <p class="font-bold text-gray-900">
                Klik atau drag file
              </p>

              <p class="text-gray-500 text-sm mt-1">
                PDF, DOC, XLS, JPG, PNG, MP3, MP4
              </p>

            </div>

            <!-- INPUT -->
            <input
              type="file"
              ref="editFileInput"
              class="hidden"
              @change="handleEditFileChange"
            />

          </div>
        </div>

        <!-- FORM -->
        <div class="space-y-4 mb-6">

          <div>
            <label class="block text-sm font-bold text-gray-900 mb-2">
              Judul Pengumuman
              <span class="text-red-600">*</span>
            </label>

            <input
              v-model="editForm.judul"
              type="text"
              class="w-full h-11 bg-gray-50 rounded-lg border border-gray-300 px-4 outline-none focus:ring-2 focus:ring-blue-400 text-sm"
            />
          </div>

          <div>
            <label class="block text-sm font-bold text-gray-900 mb-2">
              Deskripsi
              <span class="text-red-600">*</span>
            </label>

            <input
              v-model="editForm.deskripsi"
              type="text"
              class="w-full h-11 bg-gray-50 rounded-lg border border-gray-300 px-4 outline-none focus:ring-2 focus:ring-blue-400 text-sm"
            />
          </div>

          <div>
            <label class="block text-sm font-bold text-gray-900 mb-2">
              Tanggal
              <span class="text-red-600">*</span>
            </label>

            <input
              v-model="editForm.tanggal"
              type="date"
              class="w-full h-11 bg-gray-50 rounded-lg border border-gray-300 px-4 outline-none focus:ring-2 focus:ring-blue-400 text-sm"
            />
          </div>

        </div>

        <!-- BUTTON -->
        <div class="flex gap-3">

          <button
            @click="handleUpdatePengumuman"
            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-bold text-sm transition"
          >
            Simpan Perubahan
          </button>

          <button
            @click="handleCancelEdit"
            class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-900 px-6 py-3 rounded-lg font-bold text-sm transition"
          >
            Batal
          </button>

        </div>

      </div>

    </div>

    <!-- ========================= -->
    <!-- MODAL KONFIRMASI HAPUS -->
    <!-- ========================= -->
    <div
      v-if="showDeleteModal"
      class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4"
    >

      <div class="bg-white rounded-2xl max-w-sm w-full p-8 shadow-2xl">

        <div class="flex justify-center mb-4">
          <div class="bg-red-100 p-4 rounded-full">
            <X class="w-8 h-8 text-red-600" />
          </div>
        </div>

        <h2 class="text-xl font-black text-center text-gray-900 mb-2">
          Hapus Pengumuman?
        </h2>

        <p class="text-center text-gray-500 text-sm mb-8">
          Apakah Anda yakin ingin menghapus pengumuman ini? Tindakan ini tidak dapat dibatalkan.
        </p>

        <!-- BUTTON -->
        <div class="flex gap-3">

          <button
            @click="handleDeletePengumuman"
            class="flex-1 bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-bold text-sm transition"
          >
            Ya, Hapus
          </button>

          <button
            @click="handleCancelDelete"
            class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-900 px-6 py-3 rounded-lg font-bold text-sm transition"
          >
            Batal
          </button>

        </div>

      </div>

    </div>

    <!-- ========================= -->
    <!-- MODAL KONFIRMASI HAPUS PERMANENT -->
    <!-- ========================= -->
    <div
      v-if="showPermanentDeleteModal"
      class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4"
    >

      <div class="bg-white rounded-2xl max-w-sm w-full p-8 shadow-2xl">

        <div class="flex justify-center mb-4">
          <div class="bg-red-100 p-4 rounded-full">
            <X class="w-8 h-8 text-red-600" />
          </div>
        </div>

        <h2 class="text-xl font-black text-center text-gray-900 mb-2">
          Hapus Permanent?
        </h2>

        <p class="text-center text-gray-500 text-sm mb-8">
          Pengumuman ini akan dihapus selamanya dan tidak dapat dipulihkan!
        </p>

        <!-- BUTTON -->
        <div class="flex gap-3">

          <button
            @click="handlePermanentDeletePengumuman"
            class="flex-1 bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-bold text-sm transition"
          >
            Ya, Hapus Selamanya
          </button>

          <button
            @click="handleCancelPermanentDelete"
            class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-900 px-6 py-3 rounded-lg font-bold text-sm transition"
          >
            Batal
          </button>

        </div>

      </div>

    </div>

  </div>
  <!-- ========================= -->
<!-- PREVIEW MODAL -->
<!-- ========================= -->
<div
  v-if="previewModal && selectedPreview"
  class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-6"
  @click.self="previewModal = false"
>

  <div
    class="bg-white w-full max-w-5xl rounded-[30px] shadow-2xl overflow-hidden flex flex-col md:flex-row"
  >

    <!-- LEFT -->
    <div
      class="w-full md:w-1/2 bg-gray-100 flex items-center justify-center p-6"
    >

      <!-- IMAGE -->
      <img
        v-if="selectedPreview.file && getFileType(selectedPreview.file) === 'IMAGE'"
        :src="`/storage/${selectedPreview.file}`"
        class="max-h-[400px] object-contain rounded-xl shadow"
      />

      <!-- PDF -->
      <iframe
        v-else-if="selectedPreview.file && getFileType(selectedPreview.file) === 'PDF'"
        :src="`/storage/${selectedPreview.file}`"
        class="w-full h-[400px] rounded-xl"
      ></iframe>

      <!-- EMPTY -->
      <div
        v-else
        class="text-slate-400 font-bold text-center"
      >
        No preview tersedia
      </div>

    </div>

    <!-- RIGHT -->
    <div class="w-full md:w-1/2 p-8 flex flex-col">

      <div class="flex items-start justify-between mb-5">

        <span
          class="bg-[#8db1c9]/15 text-[#5b87a3] text-[11px] font-black uppercase tracking-wider px-3 py-1 rounded-full"
        >
          {{
            new Date(selectedPreview.tanggal).toLocaleDateString('id-ID', {
              day: 'numeric',
              month: 'long',
              year: 'numeric'
            })
          }}
        </span>

        <button
          @click="previewModal = false"
          class="text-2xl font-black text-slate-400 hover:text-black"
        >
          ×
        </button>

      </div>

      <!-- TITLE -->
      <h2 class="text-3xl font-black text-slate-800 mb-4 leading-tight">
        {{ selectedPreview.judul }}
      </h2>

      <!-- DESC -->
      <p class="text-slate-600 leading-relaxed overflow-y-auto">
        {{ selectedPreview.deskripsi }}
      </p>

    </div>

  </div>

</div>
</template>