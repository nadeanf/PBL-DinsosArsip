<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import AuthLayout from '@/layouts/AuthLayout.vue';

defineOptions({ layout: AuthLayout });

const page = usePage();

/* =========================
   FIX 1: pakai "arsip"
========================= */
const allDocuments = ref(
    (page.props.arsip || []).map((item: any) => ({
        id: item.id,
        title: item.judul,
        format: 'FILE',
        tanggal: item.created_at
            ? new Date(item.created_at).toLocaleDateString()
            : '-'
    }))
);

/* =========================
   FIX 2: EDIT (ambil 1 data saja)
========================= */
const handleEdit = (id: number) => {
    router.get(`/edit-dokumen/${id}`);
};

/* =========================
   DELETE MODAL
========================= */
const showModal = ref(false);
const modalConfig = ref({
    id: null as number | null,
    title: '',
    message: ''
});

const openConfirmDelete = (id: number) => {
    modalConfig.value = {
        id,
        title: 'Hapus',
        message: 'Anda yakin akan menghapus file ini?'
    };
    showModal.value = true;
};

const handleExecute = () => {
    allDocuments.value = allDocuments.value.filter(
        doc => doc.id !== modalConfig.value.id
    );

    showModal.value = false;
};

/* =========================
   PAGINATION (TETAP)
========================= */
const itemsPerPage = 5;
const currentPage = ref(1);

const totalPages = computed(() =>
    Math.ceil(allDocuments.value.length / itemsPerPage)
);

const paginatedData = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    return allDocuments.value.slice(start, start + itemsPerPage);
});

const setPage = (pageNum: number) => {
    if (pageNum >= 1 && pageNum <= totalPages.value) {
        currentPage.value = pageNum;
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
                        <h3 class="font-black text-gray-900 text-lg leading-tight tracking-wide mb-1">
                            {{ item.title }}
                        </h3>
                        <div class="flex gap-3 items-center">
                            <span class="bg-black/20 px-3 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest">
                                {{ item.format }}
                            </span>
                            <span class="text-xs font-bold text-white/80 italic">
                                {{ item.tanggal }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 ml-4">
                    <!-- EDIT -->
                    <button 
                        @click="handleEdit(item.id)" 
                        class="p-4 bg-white/40 hover:bg-white text-white hover:text-gray-800 rounded-2xl transition-all duration-300"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                    </button>

                    <!-- DELETE -->
                    <button 
                        @click="openConfirmDelete(item.id)" 
                        class="p-4 bg-red-500 hover:bg-red-700 text-white rounded-2xl transition-all duration-300 shadow-sm"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path d="M3 6h18"/>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/>
                            <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                            <line x1="10" y1="11" x2="10" y2="17"/>
                            <line x1="14" y1="11" x2="14" y2="17"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- PAGINATION -->
        <div v-if="totalPages > 1" class="flex justify-center items-center gap-3 mt-12">
            <button @click="setPage(currentPage - 1)" :disabled="currentPage === 1"
                class="p-3 rounded-xl bg-white border-2 border-gray-200 disabled:opacity-30">
                ◀
            </button>

            <div class="flex gap-2">
                <button v-for="pageNum in totalPages" :key="pageNum"
                    @click="setPage(pageNum)"
                    class="w-12 h-12 rounded-xl text-sm font-black border-2"
                    :class="currentPage === pageNum
                        ? 'bg-[#2f55a4] text-white border-[#2f55a4]'
                        : 'bg-white text-gray-600 border-gray-100'">
                    {{ pageNum }}
                </button>
            </div>

            <button @click="setPage(currentPage + 1)" :disabled="currentPage === totalPages"
                class="p-3 rounded-xl bg-white border-2 border-gray-200">
                ▶
            </button>
        </div>

        <!-- EMPTY -->
        <div v-if="allDocuments.length === 0"
            class="text-center py-20 bg-gray-100 rounded-[40px] border-2 border-dashed border-gray-300">
            <p class="text-gray-500 font-bold uppercase tracking-widest text-lg">
                Tidak ada arsip ditemukan
            </p>
        </div>
    </div>

    <!-- MODAL -->
    <div v-if="showModal"
        class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">

        <div class="bg-[#94B3C1] w-full max-w-sm rounded-[40px] p-10 text-center shadow-2xl">

            <h3 class="text-2xl font-black mb-3 uppercase">
                {{ modalConfig.title }}
            </h3>

            <p class="mb-8 italic">
                {{ modalConfig.message }}
            </p>

            <div class="flex gap-4">
                <button @click="showModal = false"
                    class="flex-1 bg-gray-300 py-3 rounded-xl font-bold">
                    Batalkan
                </button>

                <button @click="handleExecute"
                    class="flex-1 bg-red-600 text-white py-3 rounded-xl font-bold">
                    Iya
                </button>
            </div>
        </div>
    </div>
</template>