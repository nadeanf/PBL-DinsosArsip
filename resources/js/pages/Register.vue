<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3'
import { ref } from 'vue'
import GuestLayout from '@/layouts/GuestLayout.vue'

defineOptions({ layout: GuestLayout })

const form = ref({
    name: '',
    nip: '',
    email: '',
    bagian: '',
    password: '',
    password_confirmation: '',
})

const bagianOptions = [
    'Sekretariat',
    'Bidang Rehabilitasi Sosial',
    'Bidang Perlindungan dan Jaminan Sosial',
    'Bidang Pemberdayaan Sosial dan Penanganan Fakir Miskin',
]

function handleSubmit() {
    router.post('/register', form.value, {
        onSuccess: () => {
            alert('Registrasi berhasil!')
            router.visit('/login')
        },
        onError: (errors) => {
    console.log(errors)
    alert(JSON.stringify(errors))
}
    })
}

function goToLogin() {
    router.visit('/login')
}
</script>

<template>
    <Head title="Register - Dinsos" />

    <div
        class="relative flex items-start justify-center min-h-screen pt-6 bg-center bg-cover"
        style="background-image: url('/image/bgregis.jpg')"
    >
        <!-- Overlay -->
        <div class="absolute inset-0 bg-white/30 backdrop-blur-[2px]"></div>

        <!-- Card -->
        <div
            class="relative z-10 w-full max-w-sm px-5 py-3 bg-[#e8e8e8]/80 rounded-2xl shadow-xl scale-95 origin-top"
        >
            <!-- Header -->
            <div class="flex items-center justify-center gap-2 mb-3">
                <img src="/image/logodinsos.png" class="w-9 h-11 object-contain" />
                <div class="text-center leading-tight">
                    <p class="text-xs font-bold text-gray-800">Sistem Arsip Digital</p>
                    <p class="text-[11px] text-gray-600">Dinas Sosial Boyolali</p>
                </div>
            </div>

            <!-- Title -->
            <div class="text-center mb-3">
                <h1 class="text-lg font-extrabold text-gray-900">BUAT AKUN</h1>
                <p class="text-xs text-gray-500">Silahkan masuk untuk melanjutkan</p>
            </div>

            <!-- FORM -->
            <div class="space-y-2.5 text-sm">
                
                <div>
                    <label class="label">Nama Lengkap</label>
                    <input v-model="form.name" type="text" class="field-input" />
                </div>

                <div>
                    <label class="label">NIP</label>
                    <input v-model="form.nip" type="text" class="field-input" />
                </div>

                <div>
                    <label class="label">Email</label>
                    <input v-model="form.email" type="email" class="field-input" />
                </div>

                <div>
                    <label class="label">Bagian</label>
                    <select v-model="form.bagian" class="field-input">
                        <option value="" disabled></option>
                        <option v-for="opt in bagianOptions" :key="opt">
                            {{ opt }}
                        </option>
                    </select>
                </div>

                <div>
                    <label class="label">Password</label>
                    <input v-model="form.password" type="password" class="field-input" />
                </div>

                <div>
                    <label class="label">Ulangi Password</label>
                    <input v-model="form.password_confirmation" type="password" class="field-input" />
                </div>
            </div>

            <!-- Button -->
            <button
                @click="handleSubmit"
                class="w-full mt-3 py-2 text-sm font-bold text-white bg-[#2d3282] hover:bg-[#232769] rounded-lg transition"
            >
                REGISTER
            </button>

            <!-- Login -->
            <p class="mt-2 text-xs text-center text-gray-700">
                Sudah punya akun?
                <span @click="goToLogin" class="text-blue-600 cursor-pointer hover:underline">
                    Masuk
                </span>
            </p>
        </div>
    </div>
</template>

<style scoped>
.field-input {
    width: 100%;
    padding: 6px 10px;
    border-radius: 6px;
    background: white;
    color: #1f2937;
    font-size: 12.5px;
    outline: none;
    box-shadow: 0 1px 2px rgba(0,0,0,0.08);
}

.field-input::placeholder {
    color: #9ca3af;
}

.field-input:focus {
    box-shadow: 0 0 0 2px rgba(45,50,130,0.3);
}

.label {
    font-size: 11px;
    margin-bottom: 2px;
    display: block;
    color: #374151;
}
</style>