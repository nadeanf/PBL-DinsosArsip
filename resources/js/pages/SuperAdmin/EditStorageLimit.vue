<script setup lang="ts">
import AppSidebarSuperAdminLayout from '@/layouts/app/AppSidebarSuperAdminLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { FileText } from 'lucide-vue-next';
import { ref } from 'vue';

const breadcrumbs = [
    { title: 'Pengaturan', href: '/super-admin/pengaturan' },
    { title: 'Edit Storage Limit', href: '#' },
];

const userData = ref({
    terpakai: '2.3 GB',
    limitSaatIni: '10 GB',
    penggunaan: '23%'
});

const newLimit = ref('');
</script>

<template>
    <Head title="Edit Storage Limit" />

    <AppSidebarSuperAdminLayout :breadcrumbs="breadcrumbs">
        <div class="max-w-4xl mx-auto pt-10 px-6">
            
            <div class="mb-12">
                <h1 class="text-3xl font-extrabold text-black inline-block relative">
                    Edit Storage Limit
                    <div class="absolute -bottom-2 left-0 w-full h-1.5 bg-[#2f4fa2] rounded-full"></div>
                </h1>
            </div>

            <div class="bg-[#f0f9ff] border border-gray-300 rounded-2xl p-8 mb-10 shadow-sm">
                <div class="space-y-4 text-gray-800 font-bold text-sm">
                    <p>Storage Terpakai : {{ userData.terpakai }}</p>
                    <p>Limit Saat Ini : {{ userData.limitSaatIni }}</p>
                    <p>Penggunaan : {{ userData.penggunaan }}</p>
                </div>
            </div>

            <div class="bg-[#759fb1] p-10 rounded-2xl flex items-center gap-8 mb-16 shadow-lg">
                <div class="w-16 h-16 bg-white rounded-xl flex items-center justify-center shadow-md shrink-0">
                    <FileText class="w-9 h-9 text-gray-700" />
                </div>
                
                <div class="flex-1 flex flex-col">
                    <div class="mb-3">
                        <span class="bg-white text-gray-800 px-5 py-1.5 rounded-full text-[11px] font-black shadow-sm uppercase tracking-tight border border-gray-100">
                            Storage Limit Baru (GB)
                        </span>
                    </div>
                    
                    <input 
                        v-model="newLimit"
                        type="text" 
                        placeholder="XX GB"
                        class="w-full bg-white border-none rounded-xl py-4 px-8 text-gray-700 font-bold text-lg focus:ring-0 shadow-inner outline-none"
                    >
                </div>
            </div>

            <div class="flex justify-between items-center gap-10">
                <button class="flex-1 bg-[#2f4fa2] text-white py-4 rounded-xl font-bold hover:bg-blue-800 transition-all shadow-lg active:scale-95">
                    Simpan Pengaturan
                </button>
                
                <Link 
                    href="/super-admin/pengaturan"
                    class="flex-1 bg-[#8b0000] text-white py-4 rounded-xl font-bold hover:bg-red-900 transition-all shadow-lg text-center active:scale-95"
                >
                    Batal
                </Link>
            </div>

        </div>
    </AppSidebarSuperAdminLayout>
</template>

<style scoped>
input:focus {
    outline: none;
}
</style>