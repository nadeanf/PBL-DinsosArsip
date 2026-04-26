<script setup lang="ts">
import AppSidebarSuperAdminLayout from '@/layouts/app/AppSidebarSuperAdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Search, Trash2, Eye, UserPlus } from 'lucide-vue-next';

const breadcrumbs = [
    { title: 'Kelola User', href: '/super-admin/kelolauser' },
];

const users = [
    { id: 1, nama: 'Admin Arsip', email: 'admin@arsip.go.id', role: 'Admin', status: 'Aktif', bergabung: '04 Sep 2025', bagian: 'Sekretariat' },
    { id: 2, nama: 'Ahmad Yani', email: 'ahmad.yani@pemerintah.go.id', role: 'User', status: 'Aktif', bergabung: '15 Aug 2025', bagian: 'Kepala Dinas' },
    { id: 3, nama: 'Agus Purnomo', email: 'agus.purnomo@pemerintah.go.id', role: 'User', status: 'Aktif', bergabung: '15 Aug 2025', bagian: 'Lorem ipsum dolor sit amet, consectetur adipisci elit' },
    { id: 4, nama: 'Permana Aji', email: 'permana.aji@pemerintah.go.id', role: 'User', status: 'Aktif', bergabung: '15 Aug 2025', bagian: 'Lorem ipsum dolor sit amet, consectetur adipisci elit' },
    { id: 5, nama: 'Benny Kurniawan', email: 'benny.k@pemerintah.go.id', role: 'User', status: 'Aktif', bergabung: '15 Aug 2025', bagian: 'Lorem ipsum dolor sit amet, consectetur adipisci elit' },
    { id: 6, nama: 'Mukhlis Irawan', email: 'mukhlis.i@pemerintah.go.id', role: 'User', status: 'Aktif', bergabung: '15 Aug 2025', bagian: 'Lorem ipsum dolor sit amet, consectetur adipisci elit' },
];
</script>

<template>
    <Head title="Kelola User" />

    <AppSidebarSuperAdminLayout :breadcrumbs="breadcrumbs">
        <div class="max-w-6xl mx-auto space-y-6 pb-10 px-4">
            
            <div class="flex justify-between items-center mt-2">
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Kelola User</h1>
                <button class="bg-[#2f4fa2] text-white px-5 py-2.5 rounded-xl text-xs font-bold flex items-center gap-2 hover:bg-blue-800 transition shadow-sm">
                    <UserPlus class="w-4 h-4" />
                    Tambah User
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div v-for="stat in ['Total Pengguna', 'Pengguna Aktif', 'Administrator']" :key="stat" class="bg-[#759fb1] p-4 rounded-2xl relative text-white shadow-sm border border-white/10">
                    <div class="bg-white text-gray-800 px-3 py-0.5 rounded-full w-fit font-bold mb-2 text-[10px] shadow-inner">XX</div>
                    <p class="font-bold text-xs">{{ stat }}</p>
                    <div class="absolute right-5 top-1/2 -translate-y-1/2 w-11 h-11 bg-white/90 rounded-xl shadow-sm"></div>
                    <div class="mt-3 h-1.5 w-24 bg-white rounded-full opacity-80"></div>
                </div>
            </div>

            <div class="bg-[#759fb1] p-3 rounded-2xl flex gap-3 shadow-md w-full">
                <div class="relative flex-1">
                    <input 
                        type="text" 
                        placeholder="Cari pengguna, atau kata kunci..." 
                        class="w-full px-4 py-2.5 rounded-xl border-none focus:ring-1 focus:ring-[#2f4fa2] text-sm text-gray-600 bg-white shadow-inner" 
                    />
                </div>
                <button class="bg-white text-gray-800 px-6 py-2.5 rounded-xl font-bold flex items-center gap-2 hover:bg-gray-50 transition shadow-sm border border-gray-100 text-sm">
                    <Search class="w-4 h-4 text-gray-800" />
                    Cari
                </button>
            </div>

            <div class="overflow-hidden rounded-[2rem] shadow-xl border border-gray-100">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#2f4fa2] text-white uppercase text-[10px] tracking-[0.15em]">
                            <th class="px-6 py-5 font-bold">Pengguna</th>
                            <th class="px-6 py-5 font-bold text-center">Role</th>
                            <th class="px-6 py-5 font-bold text-center">Status</th>
                            <th class="px-6 py-5 font-bold text-center">Action</th>
                            <th class="px-6 py-5 font-bold">Bagian</th>
                        </tr>
                    </thead>
                    <tbody class="bg-[#759fb1] text-white">
                        <tr v-for="user in users" :key="user.id" class="border-t border-white/20 hover:bg-white/10 transition cursor-default">
                            <td class="px-6 py-4">
                                <div class="font-bold text-sm leading-tight">{{ user.nama }}</div>
                                <div class="text-[10px] text-white/70 italic mt-0.5">{{ user.email }}</div>
                            </td>
                            <td class="px-6 py-4 text-center text-xs font-medium">{{ user.role }}</td>
                            <td class="px-6 py-4 text-center text-xs font-medium">{{ user.status }}</td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-6">
                                    <button class="text-[#b91c1c] hover:scale-125 transition drop-shadow-sm"><Trash2 class="w-5 h-5" /></button>
                                    <button class="text-[#1e40af] hover:scale-125 transition drop-shadow-sm"><Eye class="w-5 h-5" /></button>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-[10px] italic leading-relaxed max-w-[200px] truncate">
                                {{ user.bagian }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex justify-center items-center gap-2 pt-2">
                <button v-for="p in ['<<', '<', '1', '2', '...', '4', '>', '>>']" :key="p" 
                    class="w-9 h-9 flex items-center justify-center bg-white border border-gray-200 rounded-xl text-[10px] font-extrabold text-gray-700 hover:bg-[#2f4fa2] hover:text-white transition-all shadow-sm">
                    {{ p }}
                </button>
            </div>

        </div>
    </AppSidebarSuperAdminLayout>
</template>