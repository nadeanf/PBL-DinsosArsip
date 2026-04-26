<script setup lang="ts">
import { ref, computed } from 'vue'


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
    }
}
</script>

<template>
    <div class="py-10 px-6 max-w-7xl mx-auto">
        <h1 class="text-4xl font-black mb-10 text-gray-800">Riwayat</h1>

        <div class="relative mb-6">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">🔍</span>
            <input 
                v-model="searchQuery"
                placeholder="Cari dalam riwayat..."
                class="w-full p-4 pl-10 border rounded-lg shadow-sm focus:ring-2 focus:ring-[#7fa1b1] outline-none" 
            />
        </div>

        <div v-for="item in paginatedData" :key="item.id"
            class="bg-[#7fa1b1] p-5 rounded-xl mb-4 text-white shadow-md hover:bg-[#6e8d9c] transition-colors cursor-pointer">
            <p class="font-bold text-lg">{{ item.title }}</p>
            <p class="text-sm opacity-90">{{ item.time }} - {{ item.date }}</p>
        </div>

        <div class="flex items-center justify-center gap-2 mt-10">
            <button 
                @click="setPage(currentPage - 1)"
                :disabled="currentPage === 1"
                class="px-4 py-2 bg-white border rounded-lg disabled:opacity-50"
            >
                Prev
            </button>
            
            <div class="flex gap-1">
                <button 
                    v-for="p in totalPages" :key="p" 
                    @click="setPage(p)"
                    :class="['px-4 py-2 rounded-lg border transition', 
                             currentPage === p ? 'bg-[#2f6f7e] text-white' : 'bg-white text-gray-700 hover:bg-gray-100']"
                >
                    {{ p }}
                </button>
            </div>

            <button 
                @click="setPage(currentPage + 1)"
                :disabled="currentPage === totalPages"
                class="px-4 py-2 bg-white border rounded-lg disabled:opacity-50"
            >
                Next
            </button>
        </div>
    </div>
</template>