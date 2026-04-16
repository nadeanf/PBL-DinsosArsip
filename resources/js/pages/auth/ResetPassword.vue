<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3'
import GuestLayout from '@/layouts/GuestLayout.vue'

defineOptions({ layout: GuestLayout })

const props = defineProps<{
    token: string
    email: string
}>()

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: ''
})

function submit() {
    form.post('/reset-password', {
        onSuccess: () => {
            alert('Password berhasil diubah!')
        }
    })
}
</script>

<template>
    <Head title="Reset Password" />

    <div
        class="relative flex items-center justify-center min-h-screen bg-center bg-cover"
        style="background-image: url('/image/bgregis.jpg')"
    >
        <!-- Overlay -->
        <div class="absolute inset-0 bg-white/40 backdrop-blur-[2px]"></div>

        <!-- Card -->
        <div class="relative z-10 w-full max-w-sm px-6 py-5 bg-[#e8e8e8]/90 rounded-2xl shadow-xl">

            <!-- Title -->
            <div class="text-center mb-4">
                <h1 class="text-lg font-extrabold text-gray-900">RESET PASSWORD</h1>
                <p class="text-xs text-gray-600 mt-1">
                    Masukkan password baru kamu
                </p>
            </div>

            <!-- Form -->
            <form @submit.prevent="submit" class="space-y-3 text-sm">

                <!-- Email -->
                <div>
                    <label class="text-xs text-gray-700">Email</label>
                    <input
                        v-model="form.email"
                        type="email"
                        readonly
                        class="field-input bg-gray-200"
                    />
                </div>

                <!-- Password -->
                <div>
                    <label class="text-xs text-gray-700">Password Baru</label>
                    <input
                        v-model="form.password"
                        type="password"
                        class="field-input"
                        placeholder="Masukkan password baru"
                    />
                </div>

                <!-- Confirm -->
                <div>
                    <label class="text-xs text-gray-700">Konfirmasi Password</label>
                    <input
                        v-model="form.password_confirmation"
                        type="password"
                        class="field-input"
                        placeholder="Ulangi password"
                    />
                </div>

                <!-- Error -->
                <div v-if="form.errors.password" class="text-xs text-red-600">
                    {{ form.errors.password }}
                </div>

                <!-- Button -->
                <button
                    type="submit"
                    class="w-full py-2 mt-2 text-sm font-bold text-white bg-[#2d3282] hover:bg-[#232769] rounded-lg"
                >
                    Reset Password
                </button>
            </form>
        </div>
    </div>
</template>

<style scoped>
.field-input {
    width: 100%;
    padding: 8px 12px;
    border-radius: 6px;
    background-color: #ffffff;
    color: #111827;
    font-size: 13px;
    outline: none;
    border: none;
    box-shadow: 0 1px 2px rgba(0,0,0,0.1);
}

.field-input:focus {
    box-shadow: 0 0 0 2px rgba(45, 50, 130, 0.35);
}
</style>