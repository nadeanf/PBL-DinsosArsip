<script setup lang="ts">
import AppSidebarSuperAdminLayout from '@/layouts/app/AppSidebarSuperAdminLayout.vue';
import { Head, Link } from '@inertiajs/vue3'; // Tambahkan Link
import { Settings, Database, Save, FileText, X } from 'lucide-vue-next';
import { ref } from 'vue';

const breadcrumbs = [
    { title: '', href: '/super-admin/pengaturan' },
];

defineProps<{
    stats: {
        totalUsers: number
        totalDokumen: number
        storageTerpakai: string
        storageTotal: string
    }
    storageUsers: {
    data: any[],
    links: any[]
}
}>()



const fileExtensions = ['PDF', 'Images (PNG, JPG, JPEG)', 'DOC', 'Excel', 'ZIP'];
const selectedFiles = ref(['PDF', 'DOC']); 

const toggleFile = (ext: string) => {
    if (selectedFiles.value.includes(ext)) {
        selectedFiles.value = selectedFiles.value.filter(f => f !== ext);
    } else {
        selectedFiles.value.push(ext);
    }
};
</script>

<template>

    <AppSidebarSuperAdminLayout :breadcrumbs="breadcrumbs">
        <div class="max-w-6xl mx-auto space-y-6 pb-10 px-4">
            
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Pengaturan</h1>


    <!-- LEFT -->
    <div class="flex items-center gap-5">

        <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center shadow-inner shrink-0">
            <Database class="w-8 h-8 text-[#2f4fa2]" />
        </div>

        <div>
            <h2 class="text-lg font-extrabold">
                Cadangkan Database
            </h2>

            <p class="text-xs text-white/80 mt-1">
                Backup seluruh data arsip dan pengguna
            </p>
        </div>

    </div>

    <!-- BUTTON -->
    <a
        href="/super-admin/backup-database"
        class="bg-[#1e40af] text-white px-7 py-3 rounded-2xl font-bold text-sm hover:bg-blue-900 transition-all shadow-lg flex items-center gap-2 active:scale-95"
    >
        <Database class="w-4 h-4" />
        Backup Sekarang
    </a>

    </div>

    </AppSidebarSuperAdminLayout>
</template>