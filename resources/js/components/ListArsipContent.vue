<script setup>
import { usePage, router } from '@inertiajs/vue3'
import { ref, computed, nextTick } from 'vue'
import { FileText, FileImage, File } from 'lucide-vue-next'
import TreeDropdown from '@/components/TreeDropdown.vue'

const page = usePage()

/* DATA BACKEND */
const dataArsip = computed(() => page.props.arsip ?? [])
const kategoriData = page.props.kategori ?? []
const filters = page.props.filters ?? {}

/* STATE FILTER */
const search = ref(filters.search || '')
const kategori = ref(filters.kategori || '')
const tanggal_awal = ref(filters.tanggal_awal || '')
const tanggal_akhir = ref(filters.tanggal_akhir || '')

const showDropdown = ref(false)

/* PAGINATION */
const currentPage = ref(1)
const perPage = 10

/* RESET PAGE kalau filter berubah */
const resetPage = () => {
  currentPage.value = 1
}

/* CATEGORY NAME */
const selectedKategoriName = computed(() => {
  const findName = (data) => {
    for (let item of data) {
      if (item.id == kategori.value) return item.nama
      if (item.children_recursive) {
        const found = findName(item.children_recursive)
        if (found) return found
      }
    }
  }
  return findName(kategoriData) || ''
})

/* SEARCH */
const handleSearch = () => {
  currentPage.value = 1

  router.get('/daftar-arsip', {
    search: search.value,
    kategori: kategori.value,
    tanggal_awal: tanggal_awal.value,
    tanggal_akhir: tanggal_akhir.value
  }, {
    preserveState: false,
    replace: true
  })
}

/* FILE TYPE */
const getFileType = (path) => {
  if (!path) return 'FILE'

  const ext = path.split('.').pop()?.toLowerCase()

  if(['jpg','jpeg','png','gif','webp'].includes(ext)){
    return 'IMAGE'
  }

  if(ext === 'pdf'){
    return 'PDF'
  }

  return 'FILE'
}

/* MAPPING FULL DATA */
const mappedDocuments = computed(() => {
  return dataArsip.value.map(item => ({
    id: item.id,
    title: item.judul,
    nomor: item.nomor || '',
    deskripsi: item.deskripsi,
    kategori: item.kategori?.nama || '-',
    jenis: item.jenis_arsip || '-',
    bidang: item.user?.bagian || '-',
    tahun: item.tahun,
    lokasi: item.lokasi,
    status: item.status_akses,
    files: item.files || [],
    format: item.files?.length
      ? getFileType(item.files[0].path_file)
      : 'FILE',
    tanggal: item.created_at
      ? new Date(item.created_at).toLocaleDateString('id-ID')
      : '-'
  }))
})

/* TOTAL DATA (PENTING) */
const totalData = computed(() => mappedDocuments.value.length)

/* TOTAL PAGE */
const totalPages = computed(() =>
  Math.ceil(totalData.value / perPage)
)

/* DATA PER PAGE */
const documents = computed(() => {
  const start = (currentPage.value - 1) * perPage
  const end = start + perPage
  return mappedDocuments.value.slice(start, end)
})

/* SET PAGE */
const setPage = (p) => {
  if (p < 1 || p > totalPages.value) return
  currentPage.value = p
}

/* PREVIEW */
const previewModal = ref(false)
const selectedDoc = ref(null)

const openPreview = (item) => {
  // 🔥 buka modal dulu, lalu set selectedDoc
  previewModal.value = true
  
  // 🔥 gunakan nextTick untuk memastikan modal sudah render
  nextTick(() => {
    selectedDoc.value = item
  })

  // 🔥 Track riwayat akses (silent - tidak perlu error dialog)
  const trackView = async () => {
    try {
      const response = await fetch('/riwayat/view', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: JSON.stringify({ dokumen_id: item.id })
      })
      if (!response.ok) throw new Error('Tracking failed')
    } catch (err) {
      // Silent - jangan tampilkan error
      console.debug('View tracking completed', err)
    }
  }
  trackView()
}

const handleDownload = (id) => {
  // 🔥 Gunakan route download agar controller mencatat download ke riwayat.
  window.location.href = `/download/${id}`
}
</script>


<template>
<div class="p-6 bg-[#f3f4f6] min-h-screen space-y-6">

  <!-- FORM -->
  <form
    @submit.prevent="handleSearch"
    class="bg-[#2f6f7e] p-4 rounded-xl shadow-md flex flex-wrap gap-4 items-center"
  >

    <div class="bg-white rounded-lg px-4 py-3 flex items-center shadow-sm flex-1">
      <span class="mr-2">🔍</span>

      <input
        v-model="search"
        placeholder="Cari dokumen..."
        class="w-full outline-none text-sm"
      />
    </div>

    <!-- 🔥 DROPDOWN FIX -->
<div class="relative w-[300px]">

  <!-- BUTTON -->
  <div 
    @click="showDropdown = !showDropdown"
    class="bg-white px-4 py-3 rounded-lg text-sm cursor-pointer flex justify-between items-center"
  >
    <span>
      {{ selectedKategoriName || 'Pilih Kategori' }}
    </span>
    <span>▼</span>
  </div>

  <!-- DROPDOWN -->
  <div 
    v-show="showDropdown"
    class="absolute left-0 top-full mt-2 w-full z-[9999] 
           bg-white border rounded-xl shadow-lg 
           max-h-[300px] overflow-y-auto"
  >

    <TreeDropdown
      :data="kategoriData"
      v-model="kategori"
    />

  </div>

</div>

    <input
      type="date"
      v-model="tanggal_awal"
      class="bg-white px-3 py-2 rounded"
    />

    <input
      type="date"
      v-model="tanggal_akhir"
      class="bg-white px-3 py-2 rounded"
    />

    <!-- BUTTON CARI -->
    <button
      type="submit"
      class="bg-white rounded-lg px-5 py-2 shadow-sm text-sm font-semibold hover:bg-gray-100 transition"
    >
      Cari
    </button>

  </form>

  <!-- TOTAL -->
  <div class="text-sm">
    Ditemukan <b>{{ totalData }}</b> arsip
  </div>


  <!-- LIST -->
  <div class="space-y-4">

    <div
      v-for="item in documents"
      :key="item.id"
      @click="openPreview(item)"
      class="cursor-pointer bg-[#7fa6b3] rounded-xl p-4 shadow-md flex items-center gap-4 hover:border-blue-500 transition"
    >

      <!-- ICON -->
      <div class="w-12 h-12 flex items-center justify-center rounded-xl bg-gray-100">

        <FileText
          v-if="item.format==='PDF'"
          class="w-6 h-6 text-red-500"
        />

        <FileImage
          v-else-if="item.format==='IMAGE'"
          class="w-6 h-6 text-blue-500"
        />

        <File
          v-else
          class="w-6 h-6 text-gray-500"
        />

      </div>


      <!-- DATA -->
      <div class="flex-1 text-xs text-gray-900">

        <p class="font-semibold text-sm mb-1">
          {{ item.title }}
        </p>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
          <span>No : {{ item.nomor }}</span>
          <span>Kategori : {{ item.kategori }}</span>
          <span>Bidang : {{ item.bidang }}</span>
          <span>Tahun : {{ item.tahun }}</span>
        </div>

      </div>

      <div class="bg-white text-xs px-3 py-1 rounded-full">
        {{ item.status }}
      </div>

    </div>


    <div
      v-if="!documents.length"
      class="text-center text-gray-500"
    >
      Tidak ada dokumen
    </div>

    <div
      v-if="totalPages > 1"
      class="mt-4 flex flex-wrap items-center justify-center gap-2"
    >
      <button
        @click="setPage(currentPage - 1)"
        :disabled="currentPage === 1"
        class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-50"
      >
        Sebelumnya
      </button>

      <button
        v-for="pageNumber in totalPages"
        :key="pageNumber"
        @click="setPage(pageNumber)"
        :class="currentPage === pageNumber
          ? 'bg-slate-900 text-white border-slate-900'
          : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50'"
        class="inline-flex min-w-[2.4rem] items-center justify-center rounded-lg border px-3 py-2 text-sm font-medium transition"
      >
        {{ pageNumber }}
      </button>

      <button
        @click="setPage(currentPage + 1)"
        :disabled="currentPage === totalPages"
        class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-50"
      >
        Berikutnya
      </button>
    </div>

  </div>

</div>



<!-- PREVIEW MODAL -->
<div
v-if="previewModal"
@click.self="previewModal = false; selectedDoc = null"
class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-6"
>

<div class="bg-white w-full max-w-5xl rounded-[30px] shadow-2xl overflow-hidden flex flex-col md:flex-row">

<!-- LEFT -->
<div class="w-full md:w-1/2 bg-gray-100 flex items-center justify-center p-6">

<img
v-if="selectedDoc.format==='IMAGE'"
:src="`/storage/${selectedDoc.files[0].path_file}`"
class="max-h-[400px] object-contain rounded-xl shadow"
/>

<iframe
v-else-if="selectedDoc.format==='PDF'"
:src="`/storage/${selectedDoc.files[0].path_file}`"
class="w-full h-[400px] rounded-xl"
/>

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

<button @click="previewModal=false">
✕
</button>

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

<button
v-if="selectedDoc.files.length"
@click="() => handleDownload(selectedDoc.id)"
class="bg-blue-600 text-white px-4 py-2 rounded-xl font-bold"
>
Download
</button>

<button
            @click="previewModal=false; selectedDoc = null"
>
Tutup
</button>

</div>

</div>
</div>
</div>

</template>