<script setup>
import AuthLayout from '@/layouts/AuthLayout.vue'
import { computed } from 'vue'

defineOptions({
  layout: AuthLayout
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

// jumlah data
const totalArsip = computed(() => documents.length)
</script>

<template>

<div class="p-6 pt-10 bg-[#f3f4f6] min-h-screen space-y-6">

    <!-- SEARCH BAR -->
    <div class="bg-[#2f6f84] p-4 rounded-xl flex items-center gap-4 shadow-md">

        <div class="bg-white flex items-center flex-1 h-12 px-4 rounded-lg shadow-sm">
            <span class="mr-2">🔍</span>
            <input 
                type="text" 
                placeholder="Cari dokumen, nomor surat, atau kata kunci..."
                class="w-full outline-none text-sm"
            >
        </div>

        <button class="bg-white h-12 px-6 rounded-lg shadow-sm text-sm font-medium hover:bg-gray-100">
            Cari
        </button>

    </div>

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