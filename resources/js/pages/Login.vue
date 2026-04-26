<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3'
import GuestLayout from '@/layouts/GuestLayout.vue'

defineOptions({ layout: GuestLayout })

defineProps<{
    status?: string
}>()

const form = useForm({
    email: '',
    password: '',
    remember: false,
})

function handleSubmit() {
    form.post('/login', {
        onError: () => {
            alert('Login gagal, cek email dan password!')
        }
    })
}

function goToRegister() {
    router.visit('/register')
}

function goToForgotPassword() {
    router.visit('/forgot-password')
}
</script>

<template>
    <Head title="Login - Dinsos" />

    <div
        class="relative flex items-center justify-center min-h-screen bg-center bg-cover"
        style="background-image: url('/image/bgregis.jpg')"
    >

        <div class="absolute inset-0 bg-white/30 backdrop-blur-[2px]"></div>


        <div class="relative z-10 w-full max-w-sm px-6 py-4 bg-[#e8e8e8]/80 rounded-2xl shadow-xl">


            <div class="flex items-center justify-center gap-2 mb-3.5">
                <img
                    src="/image/logodinsos.png"
                    alt="logo"
                    class="w-9.5 h-11.5 object-contain"
                />
                <div class="text-center leading-tight">
                    <p class="text-xs font-bold text-gray-800">Sistem Arsip Digital</p>
                    <p class="text-[10px] text-gray-600">Dinas Sosial Boyolali</p>
                </div>
            </div>


            <div class="text-center mb-3.5">
                <h1 class="text-lg font-extrabold text-gray-900">MASUK</h1>
                <p class="text-xs text-gray-500">Silahkan masuk untuk melanjutkan</p>
            </div>

            
            <div
                v-if="status"
                class="mb-2 p-2 bg-green-100 text-green-700 rounded-lg text-xs"
            >
                {{ status }}
            </div>

  
            <form @submit.prevent="handleSubmit" class="space-y-3 text-sm">


                <div>
                    <label class="block text-xs text-gray-700 mb-1">Email</label>
                    <input
                        v-model="form.email"
                        type="email"
                        required
                        class="field-input"
                        placeholder="email@example.com"
                    />
                    <span v-if="form.errors.email" class="text-xs text-red-600">
                        {{ form.errors.email }}
                    </span>
                </div>


                <div>
                    <label class="block text-xs text-gray-700 mb-1">Password</label>
                    <input
                        v-model="form.password"
                        type="password"
                        required
                        class="field-input"
                        placeholder="Password"
                    />
                    <span v-if="form.errors.password" class="text-xs text-red-600">
                        {{ form.errors.password }}
                    </span>
                </div>


                <div class="flex items-center justify-between">
                    <label class="flex items-center text-xs text-gray-700">
                        <input
                            v-model="form.remember"
                            type="checkbox"
                            class="w-4 h-4 mr-1"
                        />
                        Ingat saya
                    </label>

                    <span
                        @click="goToForgotPassword"
                        class="text-xs text-blue-600 cursor-pointer hover:underline"
                    >
                        Lupa Password?
                    </span>
                </div>

  
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full py-2 mt-1 font-bold text-white bg-[#2d3282] hover:bg-[#232769] rounded-lg disabled:opacity-50"
                >
                    {{ form.processing ? 'Loading...' : 'LOGIN' }}
                </button>
            </form>

            
            <p class="mt-3 text-xs text-center text-gray-700">
                Belum punya akun?
                <span
                    @click="goToRegister"
                    class="text-blue-600 font-medium cursor-pointer hover:underline"
                >
                    Daftar
                </span>
            </p>
        </div>
    </div>
</template>

<style scoped>
.field-input {
    width: 100%;
    padding: 7px 11px;
    border-radius: 6px;
    background-color: #ffffff;
    color: #1f2937;
    font-size: 12.5px;
    outline: none;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.08);
    transition: 0.2s;
}

.field-input:focus {
    box-shadow: 0 0 0 2px rgba(45, 50, 130, 0.35);
}
</style>