<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import AuthLayout from '@/layouts/AuthLayout.vue';

defineOptions({ layout: AuthLayout });

/* =====================
   PROPS
===================== */
const props = defineProps<{
    arsip: any,
    kategori: any[]
}>();

const arsip = props.arsip;
const kategori = props.kategori;

/* =====================
   FORM
===================== */
const form = useForm({
    judul: arsip?.judul || '',
    nomor: arsip?.nomor || '',
    tahun: arsip?.tahun || '',
    status_akses: arsip?.status_akses || 'Publik',

    id_kategori: arsip?.id_kategori || '',

    lokasi: arsip?.lokasi || '',
    deskripsi: arsip?.deskripsi || '',
    files: null as any,
});

/* =====================
   FILE PREVIEW
===================== */
const fileList = computed(() => {
    return form.files ? Array.from(form.files as FileList) : [];
});

/* =====================
   FILE INPUT
===================== */
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

/* =====================
   BACK
===================== */
const goBack = () => {
    window.history.back();
};

/* =====================
   SUBMIT (FIXED REDIRECT)
===================== */
const submit = () => {
    form.put(`/arsip/${arsip.id}`, {
        forceFormData: true,

        onSuccess: () => {
            router.visit('/kelola-arsip');
        }
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

        <form @submit.prevent="submit" class="bg-white p-8 rounded-3xl shadow space-y-5">

            <!-- FILE -->
            <div>
                <input
                    type="file"
                    ref="fileInput"
                    class="hidden"
                    multiple
                    @change="handleFileChange"
                />

                <button
                    type="button"
                    @click="triggerUpload"
                    class="bg-blue-600 text-white px-4 py-2 rounded-xl"
                >
                    Upload File Baru
                </button>

                <div v-if="fileList.length" class="mt-3">
                    <p class="font-bold mb-2">
                        File dipilih ({{ fileList.length }})
                    </p>

                    <ul class="text-sm text-gray-600">
                        <li v-for="(file, i) in fileList" :key="i">
                            📄 {{ file.name }}
                        </li>
                    </ul>
                </div>
            </div>

            <!-- JUDUL -->
            <div>
                <label class="font-bold">Judul</label>
                <input v-model="form.judul" class="w-full border p-3 rounded-xl" />
            </div>

            <!-- NOMOR -->
            <div>
                <label class="font-bold">Nomor</label>
                <input v-model="form.nomor" class="w-full border p-3 rounded-xl" />
            </div>

            <!-- TAHUN -->
            <div>
                <label class="font-bold">Tahun</label>
                <input v-model="form.tahun" class="w-full border p-3 rounded-xl" />
            </div>

            <!-- STATUS -->
            <div>
                <label class="font-bold">Status Akses</label>
                <select v-model="form.status_akses" class="w-full border p-3 rounded-xl">
                    <option value="Publik">Publik</option>
                    <option value="Private">Private</option>
                </select>
            </div>

            <!-- KATEGORI -->
            <div>
                <label class="font-bold">Kategori</label>

                <select v-model="form.id_kategori" class="w-full border p-3 rounded-xl">
                    <option value="">Pilih Kategori</option>

                    <option
                        v-for="kat in kategori"
                        :key="kat.id"
                        :value="kat.id"
                    >
                        {{ kat.nama }}
                    </option>
                </select>
            </div>

            <!-- LOKASI -->
            <div>
                <label class="font-bold">Lokasi</label>
                <input v-model="form.lokasi" class="w-full border p-3 rounded-xl" />
            </div>

            <!-- DESKRIPSI -->
            <div>
                <label class="font-bold">Deskripsi</label>
                <textarea v-model="form.deskripsi" class="w-full border p-3 rounded-xl"></textarea>
            </div>

            <!-- BUTTON -->
            <div class="flex gap-3 pt-4">

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