<script setup lang="ts">
import AppSidebarSuperAdminLayout from '@/layouts/app/AppSidebarSuperAdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { Search, Trash2, Eye, UserPlus, RotateCcw, X } from 'lucide-vue-next';

import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

interface User {
    id: number
    name: string
    email: string
    role: string
    bagian: string
    nip: string
    photo?: string
    is_active: boolean
}

const props = defineProps<{
    users: {
    data: User[],
    links: any[]
},
    totalUser: number,
    totalAktif: number,
    totalAdmin: number,
    filters: {
        search?: string
    }
}>();

const search = ref(props.filters?.search || '');

const selectedUser = ref<User | null>(null);

const openPreview = (user: User) => {
    selectedUser.value = user;
};

const closePreview = () => {
    selectedUser.value = null;
};

const showTambahModal = ref(false);

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    nip: '',
    bagian: '',
    role: 'user',
});

const submitTambahUser = () => {
    form.post('/super-admin/tambah-user', {
        onSuccess: () => {
            showTambahModal.value = false;
            form.reset();
        }
    });
};

const cariUser = () => {
    router.get('/super-admin/kelolauser', {
        search: search.value
    }, {
        preserveState: true,
        replace: true
    });
};


defineOptions({
    layout: undefined
})
</script>

<template>
    <Head title="Kelola User" />

    <AppSidebarSuperAdminLayout>
        <div class="max-w-6xl mx-auto space-y-6 pb-10 px-4">
            
            <div class="flex justify-between items-center mt-2">
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Kelola User</h1>
                <button 
                    @click="showTambahModal = true"
                    class="bg-[#2f4fa2] text-white px-5 py-2.5 rounded-xl text-xs font-bold flex items-center gap-2 hover:bg-blue-800 transition shadow-sm"
                >
                    <UserPlus class="w-4 h-4" />
                    Tambah User
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

    <!-- TOTAL USER -->
    <div class="bg-[#759fb1] p-4 rounded-2xl relative text-white shadow-sm border border-white/10">
        <div class="bg-white text-gray-800 px-3 py-0.5 rounded-full w-fit font-bold mb-2 text-[10px] shadow-inner">
            {{ totalUser }}
        </div>

        <p class="font-bold text-xs">Total Pengguna</p>

        <div class="absolute right-5 top-1/2 -translate-y-1/2 w-11 h-11 bg-white/90 rounded-xl shadow-sm"></div>

        <div class="mt-3 h-1.5 w-24 bg-white rounded-full opacity-80"></div>
    </div>

    <!-- USER AKTIF -->
    <div class="bg-[#759fb1] p-4 rounded-2xl relative text-white shadow-sm border border-white/10">
        <div class="bg-white text-gray-800 px-3 py-0.5 rounded-full w-fit font-bold mb-2 text-[10px] shadow-inner">
            {{ totalAktif }}
        </div>

        <p class="font-bold text-xs">Pengguna Aktif</p>

        <div class="absolute right-5 top-1/2 -translate-y-1/2 w-11 h-11 bg-white/90 rounded-xl shadow-sm"></div>

        <div class="mt-3 h-1.5 w-24 bg-white rounded-full opacity-80"></div>
    </div>

    <!-- ADMIN -->
    <div class="bg-[#759fb1] p-4 rounded-2xl relative text-white shadow-sm border border-white/10">
        <div class="bg-white text-gray-800 px-3 py-0.5 rounded-full w-fit font-bold mb-2 text-[10px] shadow-inner">
            {{ totalAdmin }}
        </div>

        <p class="font-bold text-xs">Administrator</p>

        <div class="absolute right-5 top-1/2 -translate-y-1/2 w-11 h-11 bg-white/90 rounded-xl shadow-sm"></div>

        <div class="mt-3 h-1.5 w-24 bg-white rounded-full opacity-80"></div>
    </div>

</div>

            <div class="bg-[#759fb1] p-3 rounded-2xl flex gap-3 shadow-md w-full">
                <div class="relative flex-1">
                    <input 
                    v-model="search"
                    @keyup.enter="cariUser"
                    type="text"
                        placeholder="Cari pengguna, atau kata kunci..." 
                        class="w-full px-4 py-2.5 rounded-xl border-none focus:ring-1 focus:ring-[#2f4fa2] text-sm text-gray-600 bg-white shadow-inner" 
                    />
                </div>
                <button 
                @click="cariUser"
                class="bg-white text-gray-800 px-6 py-2.5 rounded-xl font-bold flex items-center gap-2 hover:bg-gray-50 transition shadow-sm border border-gray-100 text-sm">
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
                       <tr
                        v-for="user in users.data"
                        :key="user.id"
                        :class="[
                        'border-t border-white/20 hover:bg-white/10 transition cursor-default',
                       !user.is_active ? 'opacity-60' : ''
                        ]"
                        >
                            <td class="px-6 py-4">
                                <div class="font-bold text-sm leading-tight">{{ user.name }}</div>
                                <div class="text-[10px] text-white/70 italic mt-0.5">{{ user.email }}</div>
                            </td>
                            <td class="px-6 py-4 text-center text-xs font-medium">{{ user.role }}</td>
                            <td class="px-6 py-4 text-center">
                            <span
                                :class="user.is_active
                             ? 'bg-green-100 text-green-700'
                              : 'bg-red-100 text-red-700'"
                                class="px-3 py-1 rounded-full text-[10px] font-bold"
                                >
                        {{ user.is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-5">

                            <!-- NONAKTIFKAN -->
                             <button
                             v-if="user.is_active"
                              @click="router.patch(`/super-admin/user/${user.id}/toggle`)"
                              class="text-red-600 hover:scale-125 transition"
                            >
                              <Trash2 class="w-5 h-5" />
                            </button>

                         <!-- AKTIFKAN -->
                            <button
                             v-else
                               @click="router.patch(`/super-admin/user/${user.id}/toggle`)"
                                 class="text-green-600 hover:scale-125 transition"
                            >
                                <RotateCcw class="w-5 h-5" />
                            </button>

                        <!-- DETAIL -->
                        <button
                        @click="openPreview(user)"
                        class="text-blue-700 hover:scale-125 transition"
                            >
                            <Eye class="w-5 h-5" />
                            </button>

                        </div>
                            </td>
                            <td class="px-6 py-4 text-[10px] italic leading-relaxed max-w-[200px] truncate">
                                {{ user.bagian }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex justify-center items-center gap-2 pt-4 flex-wrap">

    <template v-for="link in users.links" :key="link.label">

        <button
            v-if="link.url"
            v-html="link.label"
            @click="router.visit(link.url)"
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

        </div>
        <!-- MODAL PREVIEW USER -->
<div
    v-if="selectedUser"
    class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 px-4"
>
    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden">

        <!-- HEADER -->
        <div class="bg-[#2f4fa2] text-white px-6 py-4 flex justify-between items-center">
            <h2 class="font-bold text-lg">Detail Pengguna</h2>

            <button @click="closePreview">
                <X class="w-5 h-5" />
            </button>
        </div>

        <!-- CONTENT -->
        <div class="p-6 space-y-5">

            <!-- FOTO -->
            <div class="flex justify-center">
                <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-[#759fb1] shadow-md">
                    <img
                        v-if="selectedUser.photo"
                        :src="`/storage/${selectedUser.photo}`"
                        class="w-full h-full object-cover"
                    />

                    <div
                        v-else
                        class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-500 text-xs"
                    >
                        No Photo
                    </div>
                </div>
            </div>

            <!-- DATA -->
            <div class="space-y-3 text-sm">

                <div>
                    <p class="text-gray-400 text-[11px] uppercase font-bold">Nama</p>
                    <p class="font-semibold text-gray-800">{{ selectedUser.name }}</p>
                </div>

                <div>
                    <p class="text-gray-400 text-[11px] uppercase font-bold">Email</p>
                    <p class="font-semibold text-gray-800">{{ selectedUser.email }}</p>
                </div>

                <div>
                    <p class="text-gray-400 text-[11px] uppercase font-bold">NIP</p>
                    <p class="font-semibold text-gray-800">{{ selectedUser.nip }}</p>
                </div>

                <div>
                    <p class="text-gray-400 text-[11px] uppercase font-bold">Role</p>
                    <p class="font-semibold text-gray-800">{{ selectedUser.role }}</p>
                </div>

                <div>
                    <p class="text-gray-400 text-[11px] uppercase font-bold">Bagian</p>
                    <p class="font-semibold text-gray-800">{{ selectedUser.bagian }}</p>
                </div>

                <div>
                    <p class="text-gray-400 text-[11px] uppercase font-bold">Status</p>

                    <span
                        :class="selectedUser.is_active
                            ? 'bg-green-100 text-green-700'
                            : 'bg-red-100 text-red-700'"
                        class="px-3 py-1 rounded-full text-[10px] font-bold"
                    >
                        {{ selectedUser.is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>

            </div>

        </div>
    </div>
</div>
<!-- MODAL TAMBAH USER -->
<div
    v-if="showTambahModal"
    class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 px-4"
>
    <div class="bg-white w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden">

        <!-- HEADER -->
        <div class="bg-[#2f4fa2] text-white px-6 py-4 flex justify-between items-center">
            <h2 class="font-bold text-lg">Tambah User</h2>

            <button @click="showTambahModal = false">
                <X class="w-5 h-5" />
            </button>
        </div>

        <!-- FORM -->
        <form @submit.prevent="submitTambahUser" class="p-6 space-y-4">

            <input
                v-model="form.name"
                type="text"
                placeholder="Nama"
                class="w-full border rounded-xl px-4 py-3"
            />

            <input
                v-model="form.email"
                type="email"
                placeholder="Email"
                class="w-full border rounded-xl px-4 py-3"
            />

            <input
                v-model="form.nip"
                type="text"
                placeholder="NIP"
                class="w-full border rounded-xl px-4 py-3"
            />

            <select
            v-model="form.bagian"
            class="w-full border rounded-xl px-4 py-3"
                >
            <option value="">Pilih Bagian</option>
            <option value="Sekretariat">Sekretariat</option>
            <option value="Bidang Rehabilitasi Sosial">Bidang Rehabilitasi Sosial</option>
            <option value="Bidang Perlindungan dan Jaminan Sosial">
            Bidang Perlindungan dan Jaminan Sosial
            </option>
            <option value="Bidang Pemberdayaan Sosial dan Penanganan Fakir Miskin">
            Bidang Pemberdayaan Sosial dan Penanganan Fakir Miskin
            </option>
        </select>

            <select
                v-model="form.role"
                class="w-full border rounded-xl px-4 py-3"
            >
                <option value="user">User</option>
                <option value="admin">Admin</option>
                <option value="pimpinan">Pimpinan</option>
            </select>

            <input
                v-model="form.password"
                type="password"
                placeholder="Password"
                class="w-full border rounded-xl px-4 py-3"
            />

            <input
                v-model="form.password_confirmation"
                type="password"
                placeholder="Konfirmasi Password"
                class="w-full border rounded-xl px-4 py-3"
            />

            <button
                type="submit"
                class="w-full bg-[#2f4fa2] text-white py-3 rounded-xl font-bold hover:bg-blue-900 transition"
            >
                Tambah User
            </button>

        </form>
    </div>
</div>
    </AppSidebarSuperAdminLayout>
</template>