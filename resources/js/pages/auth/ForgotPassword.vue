<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3'
import GuestLayout from '@/layouts/GuestLayout.vue'

defineOptions({ layout: GuestLayout })

defineProps<{
    status?: string
}>()

const form = useForm({
    email: ''
})

function submit() {
    form.post('/forgot-password')
}
</script>

<template>
    <Head title="Lupa Password" />

    <div
        class="relative flex items-center justify-center min-h-screen bg-center bg-cover"
        style="background-image: url('/image/bgregis.jpg')"
    >
        <!-- Overlay biar ga terlalu terang -->
        <div class="absolute inset-0 bg-white/40 backdrop-blur-[2px]"></div>

        <!-- Card -->
        <div class="relative z-10 w-full max-w-sm p-6 bg-[#e8e8e8]/90 rounded-2xl shadow-xl">
            
            <!-- Title -->
            <h1 class="text-lg font-extrabold text-gray-900 text-center mb-2">
                Lupa Password
            </h1>

            <p class="text-xs text-gray-600 text-center mb-4">
                Masukkan email untuk menerima link reset password
            </p>

            <!-- Status -->
            <div v-if="status" class="mb-3 text-green-700 text-xs text-center">
                {{ status }}
            </div>

            <!-- Form -->
            <form @submit.prevent="submit" class="space-y-3">

                <!-- Input Email -->
                <input
                    v-model="form.email"
                    type="email"
                    placeholder="email@example.com"
                    class="field-input"
                    required
                />

                <!-- Error -->
                <span v-if="form.errors.email" class="text-xs text-red-600">
                    {{ form.errors.email }}
                </span>

                <!-- Button -->
                <button
                    type="submit"
                    class="w-full py-2 text-sm font-bold text-white bg-[#2d3282] hover:bg-[#232769] rounded-lg"
                >
                    Kirim Link Reset
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
    color: #111827; /* 🔥 INI YANG BIKIN TEXT HITAM */
    font-size: 13px;
    outline: none;
    border: none;
    box-shadow: 0 1px 2px rgba(0,0,0,0.1);
}

.field-input::placeholder {
    color: #6b7280;
}

.field-input:focus {
    box-shadow: 0 0 0 2px rgba(45, 50, 130, 0.35);
}
</style>