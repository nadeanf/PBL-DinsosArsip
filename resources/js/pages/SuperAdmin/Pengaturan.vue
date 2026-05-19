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

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 text-white">
                <div v-for="stat in [
                   { label: 'Total Pengguna', val: stats.totalUsers },
                   { label: 'Total Dokumen', val: stats.totalDokumen },
                   { label: 'Storage Terpakai', val: stats.storageTerpakai }
                ]" :key="stat.label" class="bg-[#759fb1] p-4 rounded-2xl relative shadow-sm border border-white/10">
                    <div class="bg-white text-gray-800 px-3 py-0.5 rounded-full w-fit font-bold mb-2 text-[10px] shadow-inner">{{ stat.val }}</div>
                    <p class="font-bold text-xs">{{ stat.label }}</p>
                    <div class="absolute right-5 top-1/2 -translate-y-1/2 w-11 h-11 bg-white/90 rounded-xl shadow-sm"></div>
                    <div class="mt-3 h-1.5 w-28 bg-white rounded-full opacity-80"></div>
                </div>
            </div>

            <div class="bg-[#759fb1] p-6 rounded-[2rem] shadow-lg text-white space-y-5">
                <div class="bg-white text-gray-800 px-4 py-1.5 rounded-full w-fit text-xs font-bold shadow-sm flex items-center gap-2">
                    <Settings class="w-3.5 h-3.5" /> Pengaturan Sistem
                </div>

                <div class="grid grid-cols-1 gap-4">
                    <div v-for="label in ['Ukuran File Maksimal (MB)', 'Batas Penyimpanan Default (GB)', 'Lama Penyimpanan Sampah (Hari)']" :key="label" class="space-y-1.5">
                        <label class="text-[11px] font-bold ml-1 uppercase tracking-wider text-white opacity-90">{{ label }}</label>
                        <input 
                            type="text" 
                            placeholder="Contoh: 50 Mb"
                            class="w-full px-4 py-2.5 rounded-xl border-none bg-white text-gray-700 text-sm font-medium shadow-inner focus:ring-2 focus:ring-[#2f4fa2] placeholder:text-gray-400"
                        >
                    </div>

                    <div class="space-y-2 pt-2">
                        <label class="text-[11px] font-bold ml-1 uppercase tracking-wider text-white opacity-90">Jenis File Diizinkan</label>
                        <div class="flex flex-wrap gap-2">
                            <button 
                                v-for="ext in fileExtensions" 
                                :key="ext"
                                @click="toggleFile(ext)"
                                :class="[
                                    'group flex items-center gap-2 px-4 py-1.5 rounded-lg text-[10px] font-bold transition-all border shadow-sm',
                                    selectedFiles.includes(ext) 
                                        ? 'bg-white text-gray-800 border-white' 
                                        : 'bg-white/10 text-white/50 border-white/10 hover:bg-white/20' 
                                ]"
                            >
                                {{ ext }}
                                <X 
                                    v-if="!selectedFiles.includes(ext)" 
                                    class="w-3 h-3 text-white/40 group-hover:text-white transition-colors" 
                                />
                                <div v-else class="w-1.5 h-1.5 rounded-full bg-[#2f4fa2]"></div>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button class="bg-white text-[#2f4fa2] px-6 py-2.5 rounded-xl font-bold text-sm flex items-center gap-2 hover:bg-gray-50 transition shadow-md border border-gray-100">
                        <Save class="w-4 h-4" /> Simpan Pengaturan
                    </button>
                </div>
            </div>

            <div class="space-y-4">
                <div class="bg-[#2f4fa2] text-white px-5 py-1.5 rounded-full w-fit text-xs font-bold shadow-sm">
                    Manajemen Penyimpanan Pengguna
                </div>

                <div class="overflow-hidden rounded-[2rem] shadow-xl border border-gray-100">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-[#2f4fa2] text-white uppercase text-[10px] tracking-widest">
                                <th class="px-6 py-4 font-bold">Pengguna</th>
                                <th class="px-6 py-4 font-bold text-center">Role</th>
                                <th class="px-6 py-4 text-center font-bold">Terpakai</th>
                                <th class="px-6 py-4 text-center font-bold">Limit</th>
                                <th class="px-6 py-4 font-bold text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-[#759fb1] text-white">
                            <tr v-for="user in storageUsers.data" :key="user.id" class="border-t border-white/20 hover:bg-white/10 transition cursor-default">
                                <td class="px-6 py-3.5">
                                    <div class="font-bold text-sm leading-tight">{{ user.nama }}</div>
                                    <div class="text-[10px] text-white/70 italic mt-0.5">{{ user.email }}</div>
                                </td>
                                <td class="px-6 py-3.5 text-center text-xs font-medium">{{ user.role }}</td>
                                <td class="px-6 py-3.5 text-center text-xs font-mono">{{ user.terpakai }}</td>
                                <td class="px-6 py-3.5 text-center text-xs font-mono">{{ user.limit }}</td>
                                <td class="px-6 py-3.5">
                                    <div class="flex justify-center">
                                        <Link 
                                            href="/super-admin/editstoragelimit"
                                            class="bg-[#b49898] text-gray-900 px-5 py-1.5 rounded-xl text-[10px] font-extrabold hover:brightness-110 shadow-sm transition-all active:scale-95 text-center"
                                        >
                                            EDIT LIMIT
                                        </Link>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-center items-center gap-2 pt-4 flex-wrap">

    <template
        v-for="link in storageUsers.links"
        :key="link.label"
    >

        <button
            v-if="link.url"
            v-html="link.label"
            @click="$inertia.visit(link.url)"
            class="px-3 py-2 rounded-xl text-xs font-bold border transition"
            :class="[
                link.active
                    ? 'bg-[#2f4fa2] text-white border-[#2f4fa2]'
                    : 'bg-white text-gray-700 border-gray-200 hover:bg-[#2f4fa2] hover:text-white'
            ]"
        />

        <span
            v-else
            v-html="link.label"
            class="px-3 py-2 rounded-xl text-xs font-bold text-gray-400 border border-gray-200 bg-gray-100 cursor-not-allowed"
        />

    </template>

</div>

                <div class="flex justify-between items-center px-1">
                    <div class="bg-[#2f4fa2] text-white px-5 py-2 rounded-xl text-[10px] font-bold shadow-sm uppercase tracking-wide">
                        Total Storage Terpakai
                    </div>
                    <div class="bg-[#2f4fa2] text-white px-5 py-2 rounded-xl text-[10px] font-bold shadow-sm font-mono">
                        {{ stats.storageTerpakai }}/ {{ stats.storageTotal }}
                    </div>
                </div>
            </div>

            <div class="bg-[#759fb1] p-5 rounded-[2rem] shadow-lg text-white flex items-center justify-between border border-white/10">

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

        </div>
    </AppSidebarSuperAdminLayout>
</template>