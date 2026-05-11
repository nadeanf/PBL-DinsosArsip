<script setup lang="ts">
import { ref, computed, watch, nextTick } from 'vue'
import { Head, router, usePage } from '@inertiajs/vue3'
import AdminLayout from '@/layouts/AdminLayout.vue'
import { FileText, FileImage, File, FileSpreadsheet } from 'lucide-vue-next'

defineOptions({ layout: AdminLayout })

const page = usePage()

const filterJenis = ref('')

const getFileType = (path: string) => {
  if (!path) return 'FILE'
  const ext = path.split('.').pop()?.toLowerCase()

  if (['jpg','jpeg','png','gif','webp'].includes(ext)) return 'IMAGE'
  if (ext === 'pdf') return 'PDF'
  if (['doc','docx'].includes(ext)) return 'DOC'
  if (['xls','xlsx'].includes(ext)) return 'EXCEL'
  return 'FILE'
}

const allDocuments = ref(
  (page.props.arsip || []).map((item: any) => ({
    id: item.id,
    title: item.judul,
    nomor: item.nomor,
    deskripsi: item.deskripsi,
    kategori: item.kategori?.nama || '-',
    jenis: item.jenis_arsip || '-',
    tahun: item.tahun,
    lokasi: item.lokasi,
    status: item.status_akses,
    user: item.user?.name || '-',

    files: item.files || [],
    format: item.files?.length
      ? getFileType(item.files[0].path_file)
      : 'FILE',

    tanggal: item.created_at
      ? new Date(item.created_at).toLocaleDateString()
      : '-'
  }))
)

const filteredDocuments = computed(() => {
  return allDocuments.value.filter(item =>
    filterJenis.value ? item.jenis === filterJenis.value : true
  )
})

const itemsPerPage = 5
const currentPage = ref(1)

const totalPages = computed(() =>
  Math.ceil(filteredDocuments.value.length / itemsPerPage)
)

const paginatedData = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage
  return filteredDocuments.value.slice(start, start + itemsPerPage)
})

watch(filterJenis, () => currentPage.value = 1)

/* PREVIEW */
const previewModal = ref(false)
const selectedDoc = ref<any>(null)

const openPreview = (item: any) => {
  // 🔥 buka modal dulu, lalu set selectedDoc
  previewModal.value = true
  
  // 🔥 gunakan nextTick untuk memastikan modal sudah render
  nextTick(() => {
    selectedDoc.value = item
  })
}

const closePreviewModal = () => {
  selectedDoc.value = null
  previewModal.value = false
}

/* ADMIN ACTION */
const deleteId = ref<number | null>(null)
const showDelete = ref(false)

const openDelete = (id: number) => {
  deleteId.value = id
  showDelete.value = true
}

const confirmDelete = () => {
  if (!deleteId.value) return

  router.delete(`/arsip/${deleteId.value}`, {
    onSuccess: () => {
      allDocuments.value = allDocuments.value.filter(
        i => i.id !== deleteId.value
      )
      showDelete.value = false
    }
  })
}

const updateStatus = (id: number, value: string) => {
  router.put(`/arsip/${id}`, {
    status_akses: value
  }, {
    onSuccess: () => {
      // update data di frontend
      const index = allDocuments.value.findIndex(i => i.id === id)
      if (index !== -1) {
        allDocuments.value[index].status = value
      }
    }
  })
}
</script>

<template>
  <Head title="Kelola Arsip User (Admin)" />

  <div class="py-10 px-6 max-w-6xl mx-auto">

    <h1 class="text-4xl font-black mb-6 text-gray-800 uppercase">
      Kelola Arsip User
    </h1>

    <!-- FILTER -->
    <div class="flex gap-4 mb-6">
      <select v-model="filterJenis" class="p-3 rounded-xl border">
        <option value="">Semua Jenis</option>
        <option value="aktif">Aktif</option>
        <option value="inaktif">Inaktif</option>
        <option value="vital">Vital</option>
      </select>
    </div>

    <!-- LIST (SAMA PERSIS USER) -->
    <div class="space-y-4 mb-8">

      <div
        v-for="item in paginatedData"
        :key="item.id"
        @click="openPreview(item)"
        class="flex items-center justify-between bg-[#7fa1b1] p-4 rounded-2xl shadow-md cursor-pointer hover:scale-[1.01] transition-all"
      >

        <!-- LEFT -->
        <div class="flex items-center gap-6 flex-1">

          <div class="w-20 h-20 bg-white rounded-2xl flex items-center justify-center shadow-sm">
            <FileText v-if="item.format === 'PDF'" class="w-10 h-10 text-red-500"/>
            <FileImage v-else-if="item.format === 'IMAGE'" class="w-10 h-10 text-blue-500"/>
            <FileText v-else-if="item.format === 'DOC'" class="w-10 h-10 text-indigo-500"/>
            <FileSpreadsheet v-else-if="item.format === 'EXCEL'" class="w-10 h-10 text-green-500"/>
            <File v-else class="w-10 h-10 text-gray-400"/>
          </div>

          <div class="text-white">
            <h3 class="font-black text-gray-900 text-lg">
              {{ item.title }}
            </h3>

            <div class="flex flex-wrap gap-2 mt-1">
              <span class="bg-black/20 px-3 py-0.5 rounded-full text-[10px] font-bold">
                No: {{ item.nomor }}
              </span>

              <span class="bg-blue-500/30 px-3 py-0.5 rounded-full text-[10px] font-bold">
                {{ item.kategori }}
              </span>

              <span class="bg-green-500/30 px-3 py-0.5 rounded-full text-[10px] font-bold uppercase">
                {{ item.jenis }}
              </span>

              <!-- ADMIN -->
              <span class="bg-yellow-500/30 px-3 py-0.5 rounded-full text-[10px] font-bold">
                {{ item.user }}
              </span>
            </div>

            <div class="flex gap-3 mt-1">
              <span class="text-xs italic">
                {{ item.tanggal }}
              </span>
            </div>
          </div>
        </div>

        <!-- RIGHT -->
        <div class="flex items-center gap-2">

          <select
            @click.stop
            @change="updateStatus(item.id, $event.target.value)"
            :value="item.status"
            class="p-2 rounded-lg text-sm"
          >
            <option value="publik">Publik</option>
            <option value="private">Private</option>
          </select>


          <button @click.stop="openDelete(item.id)" class="bg-red-500 text-white p-2 rounded-lg">
            🗑️
          </button>

        </div>

      </div>
    </div>
  </div>

  <!-- ✅ PREVIEW (DISAMAKAN 100% USER) -->
  <div v-if="selectedDoc"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-6"
    @click.self="closePreviewModal"
  >

    <div class="bg-white w-full max-w-5xl rounded-[30px] shadow-2xl overflow-hidden flex flex-col md:flex-row">

      <!-- LEFT -->
      <div class="w-full md:w-1/2 bg-gray-100 flex items-center justify-center p-6">

        <img v-if="selectedDoc.format === 'IMAGE'"
          :src="`/storage/${selectedDoc.files[0].path_file}`"
          class="max-h-[400px] object-contain rounded-xl shadow" />

        <iframe v-else-if="selectedDoc.format === 'PDF'"
          :src="`/storage/${selectedDoc.files[0].path_file}`"
          class="w-full h-[400px] rounded-xl"></iframe>

        <div v-else class="text-gray-500 text-center">
          📄<br/>Preview tidak tersedia
        </div>

      </div>

      <!-- RIGHT -->
      <div class="w-full md:w-1/2 p-8 flex flex-col justify-between">

        <div>
          <div class="flex justify-between items-start mb-4">
            <h2 class="text-2xl font-black text-gray-800">
              {{ selectedDoc.title }}
            </h2>

            <button @click="closePreviewModal">✕</button>
          </div>

          <div class="flex flex-wrap gap-2 mb-4">
            <span class="bg-gray-200 px-3 py-1 rounded-full text-xs font-bold">
              No: {{ selectedDoc.nomor }}
            </span>

            <span class="bg-blue-100 px-3 py-1 rounded-full text-xs font-bold">
              {{ selectedDoc.kategori }}
            </span>

            <span class="bg-green-100 px-3 py-1 rounded-full text-xs font-bold uppercase">
              {{ selectedDoc.jenis }}
            </span>
          </div>

          <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
              <p class="font-bold">Tahun</p>
              <p>{{ selectedDoc.tahun }}</p>
            </div>

            <div>
              <p class="font-bold">Status</p>
              <p>{{ selectedDoc.status }}</p>
            </div>

            <div class="col-span-2">
              <p class="font-bold">Lokasi</p>
              <p>{{ selectedDoc.lokasi }}</p>
            </div>
          </div>

          <div class="mt-6">
            <p class="font-bold">Deskripsi</p>
            <p>{{ selectedDoc.deskripsi || '-' }}</p>
          </div>
        </div>

        <div class="flex justify-end gap-3 mt-6">
          <a
            v-if="selectedDoc.files.length"
            :href="`/download/${selectedDoc.id}`"
            class="bg-blue-600 text-white px-4 py-2 rounded-xl font-bold"
          >
            Download
          </a>

          <button
            @click="closePreviewModal"
            class="bg-gray-300 px-4 py-2 rounded-xl font-bold"
          >
            Tutup
          </button>
        </div>

      </div>

    </div>
  </div>

  <!-- DELETE MODAL -->
  <div v-if="showDelete"
    class="fixed inset-0 z-[60] flex items-center justify-center bg-black/50 backdrop-blur-sm">

    <div class="bg-white rounded-2xl p-6 w-full max-w-sm shadow-xl">

      <h2 class="text-lg font-bold mb-3">Konfirmasi Hapus</h2>
      <p class="mb-6">Yakin mau hapus?</p>

      <div class="flex justify-end gap-3">
        <button @click="showDelete = false" class="bg-gray-200 px-4 py-2 rounded-xl">
          Batal
        </button>
        <button @click="confirmDelete" class="bg-red-500 text-white px-4 py-2 rounded-xl">
          Hapus
        </button>
      </div>

    </div>
  </div>

</template>