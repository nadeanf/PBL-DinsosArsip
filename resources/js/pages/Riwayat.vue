<script setup lang="ts">
import { ref, computed } from 'vue'
import { Head } from '@inertiajs/vue3'
import AuthLayout from '@/layouts/AuthLayout.vue'

defineOptions({ layout: AuthLayout })

// Data Dummy
const allHistory = ref([
    { id: 1, title: 'Laporan Keuangan Kesekretariatan - Oktober 2025', time: '10.35', date: '25 Februari 2026', type: 'doc' },
    { id: 2, title: 'Dokumentasi Rapat Anggaran Pendapatan dan Belanja Daerah 2014', time: '10.35', date: '25 Februari 2026', type: 'image' },
    { id: 3, title: 'Video Rapat Penyusunan Peraturan Baru Dinas Sosial', time: '10.35', date: '25 Februari 2026', type: 'video' },
    ...Array.from({ length: 17 }, (_, i) => ({
        id: i + 4,
        title: `Arsip Tambahan Ke-${i + 1}`,
        time: '09.00',
        date: '10 Maret 2026',
        type: 'doc'
    }))
])

const searchQuery = ref('')
const currentPage = ref(1)
const itemsPerPage = 5

const filteredHistory = computed(() => {
    return allHistory.value.filter(item =>
        item.title.toLowerCase().includes(searchQuery.value.toLowerCase())
    )
})

const totalPages = computed(() =>
    Math.ceil(filteredHistory.value.length / itemsPerPage)
)

const paginatedData = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage
    return filteredHistory.value.slice(start, start + itemsPerPage)
})

const setPage = (page: number) => {
    if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page
        window.scrollTo({ top: 0, behavior: 'smooth' })
    }
}
</script>

<template>
    <Head title="Riwayat Arsip" />

    <div class="py-10 px-6 max-w-7xl mx-auto">
        <h1 class="text-4xl font-black mb-10 text-gray-800">Riwayat</h1>

        <input v-model="searchQuery"
            placeholder="Cari dalam riwayat..."
            class="w-full p-4 mb-6 border rounded-lg" />

        <div v-for="item in paginatedData" :key="item.id"
            class="bg-[#7fa1b1] p-4 rounded-xl mb-4 text-white">
            <p class="font-bold">{{ item.title }}</p>
            <p class="text-sm">{{ item.time }} - {{ item.date }}</p>
        </div>

        <div class="flex gap-2 mt-6">
            <button @click="setPage(currentPage - 1)">Prev</button>
            <button v-for="p in totalPages" :key="p" @click="setPage(p)">
                {{ p }}
            </button>
            <button @click="setPage(currentPage + 1)">Next</button>
        </div>
    </div>
</template>
