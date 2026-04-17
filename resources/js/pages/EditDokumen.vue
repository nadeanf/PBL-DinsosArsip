<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AuthLayout from '@/layouts/AuthLayout.vue';

defineOptions({ layout: AuthLayout });

// =====================
// AMBIL DATA DARI BACKEND
// =====================
const props = defineProps<{
    arsip: any
}>();

const arsip = props.arsip;

// =====================
// FORM TERISI OTOMATIS
// =====================
const form = useForm({
    judul: arsip?.judul || '',
    nomor: arsip?.nomor || '',
    tahun: arsip?.tahun || '',
    status_akses: arsip?.status_akses || 'Publik',
    kategori_kelompok: '',
    kategori: arsip?.id_kategori || '',
    lokasi: arsip?.lokasi || '',
    deskripsi: arsip?.deskripsi || '',
    files: null as any,
});

// =====================
// FILE LIST (PREVIEW)
// =====================
const fileList = computed(() => {
    return form.files ? Array.from(form.files as FileList) : [];
});

// =====================
// BACK BUTTON
// =====================
const goBack = () => {
    window.history.back();
};

// =====================
// FILE HANDLING
// =====================
const fileInput = ref<HTMLInputElement | null>(null);

const triggerUpload = () => {
    fileInput.value?.click();
};

const handleFileChange = (e: Event) => {
    const target = e.target as HTMLInputElement;
    if (target.files) {
        form.files = target.files;
    }
};

// =====================
// SUBMIT UPDATE
// =====================
const submit = () => {
    form.put(`/arsip/${arsip.id}`, {
        forceFormData: true,
    });
};
</script>

<template>
<Head title="Edit Dokumen" />

<div class="py-10 px-6">
    <div class="max-w-4xl mx-auto">

        <h1 class="text-4xl font-black mb-10">
            Edit Dokumen
        </h1>

        <form @submit.prevent="submit" class="bg-white p-8 rounded-3xl shadow">

            <!-- FILE UPLOAD -->
            <div class="mb-6">

                <input
                    type="file"
                    ref="fileInput"
                    class="hidden"
                    @change="handleFileChange"
                />

                <button
                    type="button"
                    @click="triggerUpload"
                    class="bg-blue-600 text-white px-4 py-2 rounded-xl"
                >
                    Upload File Baru
                </button>

                <!-- PREVIEW FILE -->
                <div v-if="fileList.length > 0" class="mt-4">
                    <p class="font-bold mb-2">
                        File dipilih ({{ fileList.length }})
                    </p>

                    <ul class="text-sm text-gray-700 space-y-1">
                        <li v-for="(file, index) in fileList" :key="index">
                            📄 {{ file.name }}
                        </li>
                    </ul>
                </div>

            </div>

            <!-- JUDUL -->
            <div class="mb-4">
                <label>Judul</label>
                <input v-model="form.judul" class="w-full border p-3 rounded-xl" />
            </div>

            <!-- NOMOR -->
            <div class="mb-4">
                <label>Nomor</label>
                <input v-model="form.nomor" class="w-full border p-3 rounded-xl" />
            </div>

            <!-- TAHUN -->
            <div class="mb-4">
                <label>Tahun</label>
                <input v-model="form.tahun" class="w-full border p-3 rounded-xl" />
            </div>

            <!-- STATUS -->
            <div class="mb-4">
                <label>Status Akses</label>
                <select v-model="form.status_akses" class="w-full border p-3 rounded-xl">
                    <option value="Publik">Publik</option>
                    <option value="Private">Private</option>
                </select>
            </div>

            <!-- LOKASI -->
            <div class="mb-4">
                <label>Lokasi</label>
                <input v-model="form.lokasi" class="w-full border p-3 rounded-xl" />
            </div>

            <!-- DESKRIPSI -->
            <div class="mb-4">
                <label>Deskripsi</label>
                <textarea v-model="form.deskripsi" class="w-full border p-3 rounded-xl"></textarea>
            </div>

            <!-- BUTTON -->
            <div class="flex gap-3 mt-6">

                <button
                    type="button"
                    @click="goBack"
                    class="bg-gray-400 px-6 py-3 rounded-xl text-white"
                >
                    Batal
                </button>

                <button
                    type="submit"
                    class="bg-red-600 px-6 py-3 rounded-xl text-white"
                >
                    Simpan Perubahan
                </button>

            </div>

        </form>
    </div>
</div>
</template>