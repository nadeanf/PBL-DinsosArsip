<script setup>
import UserLayout from '@/layouts/UserLayout.vue'
import { computed, ref } from 'vue'

defineOptions({
  layout: UserLayout
})

const documents = [
    {
        title: "Proposal Program Pemberdayaan Masyarakat",
        nomor: "DOK/012/11/2026",
        kategori: "Proposal",
        tanggal: "25 Januari 2026",
        divisi: "Sekretariat",
        ukuran: "3,5 MB",
        status: "Public"
    },
    {
        title: "Proposal Program Pemberdayaan Masyarakat",
        nomor: "DOK/012/11/2026",
        kategori: "Proposal",
        tanggal: "25 Januari 2026",
        divisi: "Sekretariat",
        ukuran: "3,5 MB",
        status: "Public"
    }
]

// 🔥 state search baru
const search = ref('')
const kategori = ref('')
const tanggal_awal = ref('')
const tanggal_akhir = ref('')

// jumlah data
const totalArsip = computed(() => documents.length)

// dummy handle (nanti connect backend)
const handleSearch = () => {
  console.log({
    search: search.value,
    kategori: kategori.value,
    tanggal_awal: tanggal_awal.value,
    tanggal_akhir: tanggal_akhir.value
  })
}
</script>

<template>

<div class="p-6 pt-10 bg-[#f3f4f6] min-h-screen space-y-6">

    <!-- FORM -->
    <form @submit.prevent="handleSearch" class="bg-[#2f6f7e] p-4 rounded-xl shadow-md flex flex-wrap gap-4 items-center">

      <!-- SEARCH -->
      <div class="bg-white rounded-lg px-4 py-3 flex items-center shadow-sm flex-1 min-w-[200px]">
        <span class="text-gray-400 mr-2">🔍</span>
        <input
          type="text"
          v-model="search"
          placeholder="Cari dokumen..."
          class="w-full outline-none text-sm"
        />
      </div>

      <!-- KATEGORI -->
      <div class="bg-white rounded-lg px-4 py-3 flex items-center shadow-sm w-[180px]">
        <select v-model="kategori" class="text-sm outline-none w-full">
          <option value="">Semua Kategori</option>
          <option value="Proposal">Vital</option>
          <option value="Laporan">Aktif</option>
          <option value="Surat">Inaktif</option>
        </select>
      </div>

      <!-- TANGGAL AWAL -->
      <div class="bg-white rounded-lg px-4 py-3 flex items-center shadow-sm w-[160px]">
        <input
          type="date"
          v-model="tanggal_awal"
          class="text-sm outline-none w-full"
        />
      </div>

      <!-- "-" -->
      <div class="text-white font-bold px-1">
        -
      </div>

      <!-- TANGGAL AKHIR -->
      <div class="bg-white rounded-lg px-4 py-3 flex items-center shadow-sm w-[160px]">
        <input
          type="date"
          v-model="tanggal_akhir"
          class="text-sm outline-none w-full"
        />
      </div>

      <!-- BUTTON -->
      <div>
        <button 
          type="submit"
          class="bg-white rounded-lg px-4 py-2 shadow-sm text-sm font-semibold text-gray-700 hover:bg-gray-100 transition"
        >
          Cari
        </button>
      </div>

    </form>

    <!-- INFO HASIL -->
    <div class="text-sm text-gray-700">
        Ditemukan <span class="font-semibold">{{ totalArsip }}</span> arsip
    </div>

    <!-- LIST -->
    <div>

        <!-- JIKA ADA DATA ARSIP -->
        <div v-if="documents.length > 0" class="space-y-4">

            <div 
                v-for="(doc, i) in documents" 
                :key="i"
                @click="$inertia.visit(`/arsip/${doc.id ?? i}`)"
                class="bg-[#7fa6b5] rounded-xl p-4 flex items-center gap-4 shadow-md cursor-pointer hover:shadow-lg transition"
            >

                <!-- ICON -->
                <div class="w-14 h-14 bg-white rounded-lg flex items-center justify-center text-xl">
                📄
                </div>

                <!-- CONTENT -->
                <div class="flex-1 text-sm text-gray-900 space-y-2">

                <p class="font-semibold text-base">
                    {{ doc.title }}
                </p>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-x-6 gap-y-1 text-xs">
                    <span><b>No:</b> {{ doc.nomor }}</span>
                    <span><b>Kategori:</b> {{ doc.kategori }}</span>
                    <span><b>Tanggal:</b> {{ doc.tanggal }}</span>
                    <span><b>Ukuran:</b> {{ doc.ukuran }}</span>
                    <span><b>Divisi:</b> {{ doc.divisi }}</span>
                </div>

                </div>

                <!-- RIGHT SIDE -->
                <div class="flex flex-col items-end gap-2">

                <!-- STATUS -->
                <div class="bg-white text-xs px-3 py-1 rounded-full shadow">
                    {{ doc.status }}
                </div>

                <!-- ACTION -->
                <div class="flex gap-2">
                    <button class="bg-white px-3 py-1 text-xs rounded shadow hover:bg-gray-100">
                    Lihat
                    </button>
                    <button class="bg-white px-3 py-1 text-xs rounded shadow hover:bg-gray-100">
                    Unduh
                    </button>
                </div>

                </div>

            </div>

        </div>

        <!-- JIKA TIDAK ADA DATA -->
        <div v-else class="bg-white rounded-xl p-6 text-center shadow-md text-gray-500">
            Tidak ditemukan arsip
        </div>

    </div>

</div>

</template>