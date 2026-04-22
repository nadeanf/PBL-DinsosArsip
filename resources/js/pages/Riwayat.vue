<script setup lang="ts">
import { ref, computed } from 'vue'
import { Head } from '@inertiajs/vue3'
import AuthLayout from '@/layouts/AuthLayout.vue'

defineOptions({ layout: AuthLayout })

// 1. Tangkap data dari Controller (ArsipController.php)
const props = defineProps({
    riwayat: Array as any
})

// 2. Gunakan props.riwayat sebagai sumber data, bukan dummy lagi
const searchQuery = ref('')
const currentPage = ref(1)
const itemsPerPage = 5

const filteredHistory = computed(() => {
    // Pastikan data ada sebelum difilter
    return (props.riwayat || []).filter(item =>
        item.judul.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        item.aktivitas.toLowerCase().includes(searchQuery.value.toLowerCase())
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

// Fungsi bantu format tanggal agar lebih cantik
const formatTanggal = (dateString: string) => {
    if (!dateString) return ''
    const date = new Date(dateString)
    return date.toLocaleString('id-ID', { 
        day: '2-digit', month: 'long', year: 'numeric', 
        hour: '2-digit', minute: '2-digit' 
    })
}
</script>

<template>
    <Head title="Riwayat Arsip" />

    <div class="py-10 px-6 max-w-7xl mx-auto">
        <h1 class="text-4xl font-black mb-10 text-gray-800">Riwayat Aktivitas</h1>

        <input 
    v-model="searchQuery"
    type="text"
    placeholder="Cari dalam riwayat (judul atau aktivitas)..."
    class="w-full p-4 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-[#7fa1b1] focus:border-[#7fa1b1] outline-none text-black font-medium placeholder-gray-500 bg-white" 
/>
        <div v-for="item in paginatedData" :key="item.id"
            class="bg-[#7fa1b1] p-5 rounded-xl mb-4 text-white shadow-md transition hover:scale-[1.01]">
            <div class="flex justify-between items-start">
                <div>
                    <p class="font-bold text-lg">{{ item.judul }}</p>
                    <p class="text-sm opacity-90">{{ item.aktivitas }}</p>
                </div>
                <div class="text-right">
                    <p class="text-xs font-medium bg-white/20 px-2 py-1 rounded">
                        {{ formatTanggal(item.waktu_aktivitas) }}
                    </p>
                </div>
            </div>
        </div>

        <div v-if="totalPages > 1" class="flex justify-center items-center gap-2 mt-10">
            <button @click="setPage(currentPage - 1)" 
                :disabled="currentPage === 1"
                class="px-4 py-2 bg-gray-200 rounded-lg disabled:opacity-50">Prev</button>
            
            <button v-for="p in totalPages" :key="p" 
                @click="setPage(p)"
                :class="['px-4 py-2 rounded-lg transition', currentPage === p ? 'bg-[#7fa1b1] text-white' : 'bg-gray-200']">
                {{ p }}
            </button>
            
            <button @click="setPage(currentPage + 1)" 
                :disabled="currentPage === totalPages"
                class="px-4 py-2 bg-gray-200 rounded-lg disabled:opacity-50">Next</button>
        </div>

        <div v-if="filteredHistory.length === 0" class="text-center py-20 text-gray-400">
            Data riwayat tidak ditemukan.
        </div>
    </div>
</template>