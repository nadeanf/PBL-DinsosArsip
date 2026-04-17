<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AuthLayout from '@/layouts/AuthLayout.vue';
// Import icon jika menggunakan lucide-vue-next, jika tidak, pakai SVG biasa di bawah
import { AlertTriangle } from 'lucide-vue-next';

defineOptions({ layout: AuthLayout });

const handleEdit = (id: number) => {
    // Navigasi ke route /edit-dokumen
    // Kamu bisa melempar ID lewat query parameter agar halaman edit tahu file mana yang dibuka
    router.get('/edit-dokumen', { id: id });
};
// 1. Data Dummy Utama
const allDocuments = ref(Array.from({ length: 20 }, (_, i) => ({
    id: i + 1,
    title: `Surat Tugas Dinas Kabupaten Boyolali - Desember 2026 (${i + 1})`,
    format: "PDF",
    tanggal: "8/3/25"
})));

// 2. State untuk Modal Konfirmasi
const showModal = ref(false);
const modalConfig = ref({
    id: null,
    title: '',
    message: ''
});

// Fungsi untuk Membuka Modal
const openConfirmDelete = (id) => {
    modalConfig.value = {
        id: id,
        title: 'Hapus',
        message: 'Anda yakin akan menghapus file ini?'
    }
    showModal.value = true;
}

// Fungsi Eksekusi Hapus (Filter Data)
const handleExecute = () => {
    // Menghapus data dari array ref berdasarkan ID
    allDocuments.value = allDocuments.value.filter(doc => doc.id !== modalConfig.value.id);
    
    // Tutup modal
    showModal.value = false;
    
    // Reset ke halaman 1 jika data di halaman saat ini habis
    if (paginatedData.value.length === 0 && currentPage.value > 1) {
        currentPage.value--;
    }
};

// 3. Logika Pagination
const itemsPerPage = 5;
const currentPage = ref(1);

const totalPages = computed(() => Math.ceil(allDocuments.value.length / itemsPerPage));

const paginatedData = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return allDocuments.value.slice(start, end);
});

const setPage = (page: number) => {
    if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
};
</script>

<template>
    <Head title="Kelola Arsip Saya" />
    
    <div class="py-10 px-6 max-w-6xl mx-auto">
        <h1 class="text-4xl font-black mb-10 text-gray-800 uppercase tracking-tight italic">
            Kelola Arsip Saya
        </h1>

        <div class="space-y-4 mb-8">
            <div v-for="item in paginatedData" :key="item.id" 
                class="flex items-center justify-between bg-[#7fa1b1] p-4 rounded-2xl shadow-md border-b-4 border-black/10">
                
                <div class="flex items-center gap-6 flex-1">
                    <div class="w-20 h-20 bg-white rounded-2xl flex-shrink-0 flex items-center justify-center shadow-inner text-[#7fa1b1]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    
                    <div class="text-white">
                        <h3 class="font-black text-gray-900 text-lg leading-tight tracking-wide mb-1">{{ item.title }}</h3>
                        <div class="flex gap-3 items-center">
                            <span class="bg-black/20 px-3 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest">
                                {{ item.format }}
                            </span>
                            <span class="text-xs font-bold text-white/80 italic">{{ item.tanggal }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 ml-4">
                    <button 
        @click="handleEdit(item.id)" 
        class="p-4 bg-white/40 hover:bg-white text-white hover:text-gray-800 rounded-2xl transition-all duration-300"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
        </svg>
    </button>
                    <button @click="openConfirmDelete(item.id)" class="p-4 bg-red-500 hover:bg-red-700 text-white rounded-2xl transition-all duration-300 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                    </button>
                </div>
            </div>
        </div>

        <div v-if="totalPages > 1" class="flex justify-center items-center gap-3 mt-12">
            <button @click="setPage(currentPage - 1)" :disabled="currentPage === 1" class="p-3 rounded-xl bg-white border-2 border-gray-200 disabled:opacity-30">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7" /></svg>
            </button>

            <div class="flex gap-2">
                <button v-for="page in totalPages" :key="page" @click="setPage(page)"
                    class="w-12 h-12 rounded-xl text-sm font-black transition-all border-2"
                    :class="currentPage === page ? 'bg-[#2f55a4] text-white border-[#2f55a4] shadow-lg' : 'bg-white text-gray-600 border-gray-100'">
                    {{ page }}
                </button>
            </div>

            <button @click="setPage(currentPage + 1)" :disabled="currentPage === totalPages" class="p-3 rounded-xl bg-white border-2 border-gray-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" /></svg>
            </button>
        </div>

        <div v-if="allDocuments.length === 0" class="text-center py-20 bg-gray-100 rounded-[40px] border-2 border-dashed border-gray-300">
            <p class="text-gray-500 font-bold uppercase tracking-widest text-lg">Tidak ada arsip ditemukan</p>
        </div>
    </div>

    <div v-if="showModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <div class="relative bg-[#94B3C1] border-2 border-gray-400 w-full max-w-sm rounded-[40px] overflow-hidden p-10 text-center shadow-2xl"
             style="background-image: url('https://www.transparenttextures.com/patterns/black-linen.png'); background-blend-mode: soft-light;">
            
            <div class="bg-white w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6 border-2 border-red-500 shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>

            <h3 class="text-2xl font-black text-gray-900 mb-3 uppercase tracking-tight">{{ modalConfig.title }}</h3>
            <p class="text-sm font-semibold text-gray-800 mb-10 leading-relaxed px-2 italic">{{ modalConfig.message }}</p>

            <div class="flex gap-4">
                <button @click="showModal = false" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-900 font-black py-4 rounded-2xl transition shadow-md uppercase text-xs tracking-tighter">
                    Batalkan
                </button>
                <button @click="handleExecute" class="flex-1 bg-[#E11D48] hover:bg-red-700 text-white font-black py-4 rounded-2xl transition shadow-md uppercase text-xs tracking-tighter">
                    Iya
                </button>
            </div>
        </div>
    </div>
</template>