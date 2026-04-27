<script setup>
import UserLayout from '@/layouts/UserLayout.vue'
import { Head } from '@inertiajs/vue3'
import { ref, computed } from 'vue'

defineOptions({ layout: UserLayout })

// ambil data dari controller
const props = defineProps({
    riwayat: Array
})

// state
const searchQuery = ref('')
const currentPage = ref(1)
const itemsPerPage = 5

// filter
const filteredHistory = computed(() => {
    return (props.riwayat || []).filter(item =>
        item.judul.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        item.aktivitas.toLowerCase().includes(searchQuery.value.toLowerCase())
    )
})

// pagination
const totalPages = computed(() =>
    Math.ceil(filteredHistory.value.length / itemsPerPage)
)

const paginatedData = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage
    return filteredHistory.value.slice(start, start + itemsPerPage)
})

const setPage = (page) => {
    if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page
        window.scrollTo({ top: 0, behavior: 'smooth' })
    }
}

// format tanggal
const formatTanggal = (dateString) => {
    if (!dateString) return ''
    const date = new Date(dateString)
    return date.toLocaleString('id-ID', {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    })
}
</script>

<template>
    <Head title="Riwayat Arsip" />

    <div class="py-10 px-6 max-w-7xl mx-auto">
        <h1 class="text-4xl font-black mb-10 text-gray-800">
            Riwayat Aktivitas
        </h1>

        <!-- SEARCH -->
        <input 
            v-model="searchQuery"
            type="text"
            placeholder="Cari dalam riwayat..."
            class="w-full p-4 border rounded-xl mb-6"
        />

        <!-- LIST -->
        <div v-for="item in paginatedData" :key="item.id"
            class="bg-[#7fa1b1] p-5 rounded-xl mb-4 text-white shadow-md">

            <div class="flex justify-between">
                <div>
                    <p class="font-bold text-lg">{{ item.judul }}</p>
                    <p class="text-sm">{{ item.aktivitas }}</p>
                </div>

                <p class="text-xs bg-white/20 px-2 py-1 rounded">
                    {{ formatTanggal(item.waktu_aktivitas) }}
                </p>
            </div>

        </div>

        <!-- PAGINATION -->
        <div v-if="totalPages > 1" class="flex gap-2 justify-center mt-6">

            <button @click="setPage(currentPage - 1)">Prev</button>

            <button
                v-for="p in totalPages"
                :key="p"
                @click="setPage(p)">
                {{ p }}
            </button>

            <button @click="setPage(currentPage + 1)">Next</button>

        </div>

        <!-- EMPTY -->
        <div v-if="filteredHistory.length === 0"
            class="text-center py-10 text-gray-400">
            Data tidak ada
        </div>

    </div>
</template>