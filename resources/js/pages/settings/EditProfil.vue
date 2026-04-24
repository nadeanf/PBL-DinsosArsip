<script setup>
import { usePage } from '@inertiajs/vue3'
import UserLayout from '@/layouts/UserLayout.vue'

defineOptions({
  layout: UserLayout
})

const page = usePage()
const user = page.props.auth?.user
</script>

<template>
  <div class="p-6 bg-[#f3f4f6] min-h-screen flex justify-center">

    <div class="w-full max-w-3xl space-y-6">

      <h1 class="text-2xl font-bold text-gray-800 -ml-4">
        Profile Saya
      </h1>

      <!-- ================= CARD PROFILE ================= -->
      <div class="rounded-xl shadow-md overflow-hidden">

        <div class="bg-gradient-to-b from-[#7fa6b3] to-[#4f7d8c] h-6"></div>

        <div class="bg-white p-6 text-black">

          <!-- PROFILE -->
          <div class="flex items-center gap-4 mb-4">
            <div class="w-16 h-16 rounded-full bg-gray-300"></div>

            <div>
              <div class="bg-[#7fa6b3] text-white px-4 py-1 rounded-md text-sm font-semibold w-fit">
                {{ user?.name || 'Guest' }}
              </div>

              <div class="bg-[#9bbbc5] text-white px-3 py-1 rounded-md text-xs mt-1 w-fit">
                {{ user?.email || '-' }}
              </div>
            </div>
          </div>

          <hr class="my-4">

          <!-- INFORMASI AKUN -->
          <div class="mb-4">
            <div class="bg-[#7fa6b3] text-white px-4 py-1 rounded-md text-sm w-fit mb-3">
              Informasi Akun
            </div>

            <div class="space-y-3">

              <div>
                <label class="text-sm text-gray-600">Nama Lengkap</label>
                <p class="mt-1 text-gray-900">{{ user?.name || '-' }}</p>
              </div>

              <div>
                <label class="text-sm text-gray-600">NIP</label>
                <p class="mt-1 text-gray-900">{{ user?.nip || '-' }}</p>
              </div>

              <div>
                <label class="text-sm text-gray-600">Bagian</label>
                <p class="mt-1 text-gray-900">{{ user?.bagian || '-' }}</p>
              </div>

              <div>
                <label class="text-sm text-gray-600">Email</label>
                <p class="mt-1 text-gray-900">{{ user?.email || '-' }}</p>
              </div>

            </div>
          </div>

        </div>

        <div class="bg-gradient-to-t from-[#7fa6b3] to-[#4f7d8c] h-6"></div>

      </div>

    </div>

  </div>
</template>