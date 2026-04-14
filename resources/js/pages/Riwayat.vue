<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthLayout from '@/Layouts/AuthLayout.vue';

defineOptions({ layout: AuthLayout });

// 1. Data Dummy Riwayat (20 Data)
const allHistory = ref([
    { id: 1, title: 'Laporan Keuangan Kesekretariatan - Oktober 2025', time: '10.35', date: '25 Februari 2026', type: 'doc' },
    { id: 2, title: 'Dokumentasi Rapat Anggaran Pendapatan dan Belanja Daerah 2014', time: '10.35', date: '25 Februari 2026', type: 'image' },
    { id: 3, title: 'Video Rapat Penyusunan Peraturan Baru Dinas Sosial', time: '10.35', date: '25 Februari 2026', type: 'video' },
    // Mengisi sisa data sampai 20 untuk simulasi pagination
    ...Array.from({ length: 17 }, (_, i) => ({
        id: i + 4,
        title: `Arsip Tambahan Ke-${i + 1} Kabupaten Boyolali`,
        time: '09.00',
        date: '10 Maret 2026',
        type: 'doc'
    }))
]);

// 2. State Pencarian & Pagination
const searchQuery = ref('');
const currentPage = ref(1);
const itemsPerPage = 5;

// Logika Filter Berdasarkan Pencarian
const filteredHistory = computed(() => {
    return allHistory.value.filter(item => 
        item.title.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

// Logika Pagination dari hasil filter
const totalPages = computed(() => Math.ceil(filteredHistory.value.length / itemsPerPage));

const paginatedData = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return filteredHistory.value.slice(start, end);
});

const setPage = (page: number) => {
    if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
};
</script>

<template>
    <Head title="Riwayat Arsip" />

    <div class="py-10 px-6 max-w-7xl mx-auto">
        <h1 class="text-4xl font-black mb-10 text-gray-800 uppercase tracking-tight">Riwayat</h1>

        <div class="relative mb-10 max-w-3xl">
    <span class="absolute inset-y-0 left-0 flex items-center pl-5 pointer-events-none">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
    </span>
    
    <input 
        v-model="searchQuery"
        type="text" 
        placeholder="Cari dalam riwayat" 
        class="w-full p-5 pl-16 bg-white text-gray-600 font-semibold rounded-[25px] border-none shadow-[0px_4px_10px_rgba(0,0,0,0.1)] outline-none focus:ring-2 focus:ring-[#7fa1b1] placeholder:text-gray-400 placeholder:font-medium"
    />
</div>

        <div class="space-y-6">
            <div v-for="item in paginatedData" :key="item.id" 
                class="flex items-center gap-6 bg-[#7fa1b1] p-5 rounded-3xl shadow-lg transition-transform hover:scale-[1.01]">
                
                <div class="w-20 h-20 bg-white rounded-2xl flex-shrink-0 flex items-center justify-center shadow-inner">
                    <svg v-if="item.type === 'doc'" class="w-10 h-10 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <svg v-else-if="item.type === 'image'" class="w-10 h-10 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <svg v-else-if="item.type === 'video'" class="w-10 h-10 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                </div>

                <div class="text-white flex-1">
                    <h3 class="font-black text-gray-900 text-lg mb-2 tracking-tight">{{ item.title }}</h3>
                    <div class="flex gap-4">
                        <div class="flex items-center gap-1.5 bg-white/20 px-3 py-1 rounded-full border border-white/30 shadow-sm">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span class="text-xs font-black">{{ item.time }}</span>
                        </div>
                        <div class="flex items-center gap-1.5 bg-white/20 px-3 py-1 rounded-full border border-white/30 shadow-sm">
                            <span class="text-xs font-black tracking-tight">{{ item.date }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="totalPages > 1" class="flex justify-center items-center gap-3 mt-16">
            <button @click="setPage(currentPage - 1)" :disabled="currentPage === 1" class="p-3 rounded-xl bg-white border-2 border-gray-100 disabled:opacity-30">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7" stroke-width="3"/></svg>
            </button>

            <div class="flex gap-2">
                <button v-for="page in totalPages" :key="page" @click="setPage(page)"
                    class="w-12 h-12 rounded-xl text-sm font-black transition-all border-2"
                    :class="currentPage === page ? 'bg-[#2f55a4] text-white border-[#2f55a4] shadow-lg scale-110' : 'bg-white text-gray-600 border-gray-100'">
                    {{ page }}
                </button>
            </div>

            <button @click="setPage(currentPage + 1)" :disabled="currentPage === totalPages" class="p-3 rounded-xl bg-white border-2 border-gray-100">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="3"/></svg>
            </button>
        </div>

        <div v-if="filteredHistory.length === 0" class="text-center py-20 italic text-gray-500 font-bold">
            Data riwayat "{{ searchQuery }}" tidak ditemukan.
        </div>
    </div>
</template>