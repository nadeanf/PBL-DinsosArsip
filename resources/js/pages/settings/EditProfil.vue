<script setup>
import { usePage, router } from '@inertiajs/vue3'
import { ref } from 'vue'
import UserLayout from '@/layouts/UserLayout.vue'

defineOptions({
  layout: UserLayout
})

const page = usePage()
const user = page.props.auth?.user

const photo = ref(null)
const preview = ref(null)
const fileInput = ref(null)

const loading = ref(false)
const message = ref(null)
const error = ref(null)

// klik foto → buka file
function selectFile() {
  fileInput.value.click()
}

// pilih file + auto upload
function handleFile(e) {
  const file = e.target.files[0]
  if (!file) return

  photo.value = file
  preview.value = URL.createObjectURL(file)

  const formData = new FormData()
  formData.append('photo', file)

  loading.value = true
  message.value = null
  error.value = null

  router.post('/profile/update', formData, {
    forceFormData: true,
    preserveScroll: true,

    onSuccess: () => {
      message.value = 'Foto berhasil diupdate ✅'

      setTimeout(() => {
        message.value = null
      }, 3000)
    },

    onError: () => {
      error.value = 'Gagal upload foto ❌'

      setTimeout(() => {
        error.value = null
      }, 3000)
    },

    onFinish: () => {
      loading.value = false
    }
  })
}
</script>

<template>
  <div class="p-6 bg-[#f3f4f6] min-h-screen flex justify-center">

    <div class="w-full max-w-3xl space-y-4">

      <!-- TITLE -->
      <h1 class="text-2xl font-bold text-gray-800">
        Profile Saya
      </h1>

      <!-- NOTIF -->
      <div v-if="message" class="bg-green-100 text-green-700 px-4 py-2 rounded">
        {{ message }}
      </div>

      <div v-if="error" class="bg-red-100 text-red-700 px-4 py-2 rounded">
        {{ error }}
      </div>

      <!-- CARD -->
      <div class="rounded-xl shadow-md overflow-hidden">

        <!-- HEADER -->
        <div class="bg-gradient-to-b from-[#7fa6b3] to-[#4f7d8c] h-6"></div>

        <div class="bg-white p-6">

          <!-- PROFILE -->
          <div class="flex items-center gap-4 mb-4">

            <!-- FOTO -->
            <div @click="selectFile" class="cursor-pointer relative">

              <!-- LOADING -->
              <div
                v-if="loading"
                class="absolute inset-0 bg-black/50 rounded-full flex items-center justify-center text-white text-xs"
              >
                Upload...
              </div>

              <!-- PREVIEW -->
              <img
                v-if="preview"
                :src="preview"
                class="w-20 h-20 rounded-full object-cover border-2 border-gray-300 hover:opacity-80"
              />

              <!-- FOTO DB -->
              <img
                v-else-if="user?.photo"
                :src="`/storage/${user.photo}`"
                class="w-20 h-20 rounded-full object-cover border-2 border-gray-300 hover:opacity-80"
              />

              <!-- DEFAULT -->
              <div
                v-else
                class="w-20 h-20 rounded-full bg-gray-300 flex items-center justify-center text-xs text-gray-600 hover:opacity-80"
              >
                Klik Foto
              </div>

            </div>

            <!-- INFO -->
            <div>
              <div class="bg-[#7fa6b3] text-white px-4 py-1 rounded-md text-sm font-semibold w-fit">
                {{ user?.name || 'Guest' }}
              </div>

              <div class="bg-[#9bbbc5] text-white px-3 py-1 rounded-md text-xs mt-1 w-fit">
                {{ user?.email || '-' }}
              </div>
            </div>

          </div>

          <!-- INPUT HIDDEN -->
          <input
            type="file"
            ref="fileInput"
            class="hidden"
            @change="handleFile"
          />

          <hr class="my-4">

          <!-- INFORMASI AKUN -->
          <div>
            <div class="bg-[#7fa6b3] text-white px-4 py-1 rounded-md text-sm w-fit mb-3">
              Informasi Akun
            </div>

            <div class="space-y-3">

              <div>
                <label class="text-sm text-gray-600">Nama Lengkap</label>
                <p class="mt-1">{{ user?.name || '-' }}</p>
              </div>

              <div>
                <label class="text-sm text-gray-600">NIP</label>
                <p class="mt-1">{{ user?.nip || '-' }}</p>
              </div>

              <div>
                <label class="text-sm text-gray-600">Bagian</label>
                <p class="mt-1">{{ user?.bagian || '-' }}</p>
              </div>

              <div>
                <label class="text-sm text-gray-600">Email</label>
                <p class="mt-1">{{ user?.email || '-' }}</p>
              </div>

            </div>
          </div>

        </div>

        <!-- FOOTER -->
        <div class="bg-gradient-to-t from-[#7fa6b3] to-[#4f7d8c] h-6"></div>

      </div>

    </div>

  </div>
</template>