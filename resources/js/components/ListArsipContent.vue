<script setup>
import { computed, ref } from 'vue'
import { router, Link } from '@inertiajs/vue3'

const documents = [
    {
        id: 1,
        title: "Proposal Program Pemberdayaan Masyarakat",
        nomor: "DOK/012/11/2026",
        kategori: "Proposal",
        tanggal: "25 Januari 2026",
        divisi: "Sekretariat",
        ukuran: "3,5 MB",
        status: "Public"
    },
    {
        id: 2,
        title: "Proposal Program Pemberdayaan Masyarakat",
        nomor: "DOK/012/11/2026",
        kategori: "Proposal",
        tanggal: "25 Januari 2026",
        divisi: "Sekretariat",
        ukuran: "3,5 MB",
        status: "Public"
    }
]

// state search
const search = ref('')
const kategori = ref('')
const tanggal_awal = ref('')
const tanggal_akhir = ref('')

// jumlah data
const totalArsip = computed(() => documents.length)

// search
const handleSearch = () => {
  router.get('/daftar-arsip', {
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
    <form
        @submit.prevent="handleSearch"
        class="bg-[#2f6f7e] p-4 rounded-xl shadow-md flex flex-wrap gap-4 items-center"
    >

      <div class="bg-white rounded-lg px-4 py-3 flex items-center shadow-sm flex-1 min-w-[200px]">
        <span class="text-gray-400 mr-2">🔍</span>
        <input
          type="text"
          v-model="search"
          placeholder="Cari dokumen..."
          class="w-full outline-none text-sm"
        />
      </div>

      <div class="bg-white rounded-lg px-4 py-3 flex items-center shadow-sm w-[180px]">
        <select v-model="kategori" class="text-sm outline-none w-full">
          <option value="">Semua Kategori</option>
          <option value="Proposal">Vital</option>
          <option value="Laporan">Aktif</option>
          <option value="Surat">Inaktif</option>
        </select>
      </div>

      <div class="bg-white rounded-lg px-4 py-3 shadow-sm w-[160px]">
        <input type="date" v-model="tanggal_awal" class="w-full text-sm outline-none" />
      </div>

      <div class="text-white font-bold px-1">-</div>

      <div class="bg-white rounded-lg px-4 py-3 shadow-sm w-[160px]">
        <input type="date" v-model="tanggal_akhir" class="w-full text-sm outline-none" />
      </div>

      <button
        type="submit"
        class="bg-white rounded-lg px-4 py-2 text-sm font-semibold"
      >
        Cari
      </button>

    </form>


    <div class="text-sm">
        Ditemukan <b>{{ totalArsip }}</b> arsip
    </div>


    <div v-if="documents.length > 0" class="space-y-4">

        <Link
            v-for="doc in documents"
            :key="doc.id"
            :href="`/arsip/${doc.id}`"
            class="bg-[#7fa6b5] p-5 rounded-xl flex gap-4 cursor-pointer hover:shadow-xl transition relative block"
        >


            <div class="w-16 h-16 bg-gray-200 rounded"></div>

            
            <div class="flex-1">

                <p class="font-semibold text-gray-900">
                    {{ doc.title }}
                </p>

                <div class="text-xs text-gray-800 mt-1 space-y-1">
                    <p>No : {{ doc.nomor }}</p>
                    <p>Ukuran : {{ doc.ukuran }}</p>
                </div>

                <div class="flex gap-6 text-xs text-gray-800 mt-2">
                    <p>Kategori : {{ doc.kategori }}</p>
                    <p>Tanggal : {{ doc.tanggal }}</p>
                    <p>Divisi : {{ doc.divisi }}</p>
                </div>

            </div>

            <div class="absolute top-4 right-4 text-[10px] bg-white px-3 py-1 rounded-full shadow">
                {{ doc.status }}
            </div>

        </Link>

    </div>

    <!-- EMPTY -->
    <div v-else class="text-center text-gray-500">
        Tidak ditemukan arsip
    </div>

</div>
</template>