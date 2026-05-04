<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3'
import { ref } from 'vue'
import AdminLayout from '@/layouts/AdminLayout.vue'
import { Plus, Trash2 } from 'lucide-vue-next'

defineOptions({ layout: AdminLayout })

/* =========================
   PROPS
========================= */
const props = defineProps({
  kategori: Array
})

/* =========================
   STATE
========================= */
const showModal = ref(false)
const newItem = ref('')
const selectedParent = ref<number | null>(null)

/* =========================
   CREATE
========================= */
const tambahKategori = () => {
  if (!newItem.value.trim()) return

  router.post('/kategori', {
    nama: newItem.value,
    parent_id: selectedParent.value
  }, {
    onSuccess: () => {
      newItem.value = ''
      selectedParent.value = null
      showModal.value = false
    }
  })
}

/* =========================
   DELETE
========================= */
const hapusItem = (id: number) => {
  router.delete(`/kategori/${id}`)
}

/* =========================
   RECURSIVE RENDER FUNCTION
========================= */
const renderChildren = (children: any[], level = 1) => {
  return children?.map((child: any) => ({
    ...child,
    level
  })) || []
}
</script>

<template>
  <Head title="Kelola Kategori" />

  <div class="p-6">

    <!-- HEADER -->
    <div class="flex items-center gap-4 mb-6">
      <h1 class="text-3xl font-bold text-gray-800">
        Kelola Kategori
      </h1>

      <button
        @click="showModal = true"
        class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center"
      >
        <Plus class="w-5 h-5" />
      </button>
    </div>

    <!-- ================= LIST ================= -->
    <div class="space-y-4">

      <div v-for="parent in props.kategori" :key="parent.id">

        <!-- PARENT -->
        <div class="bg-gray-200 font-bold px-4 py-3 rounded">
          {{ parent.nama }}
        </div>

        <!-- CHILDREN LEVEL 1 -->
        <div
          v-for="child in parent.children_recursive"
          :key="child.id"
          class="flex items-center justify-between mt-2 ml-6"
        >
          <div class="bg-[#6f97a8] text-white px-4 py-2 rounded w-full">
            {{ child.nama }}
          </div>

          <button
            @click="hapusItem(child.id)"
            class="ml-2 p-2 bg-gray-200 hover:bg-red-500 hover:text-white rounded"
          >
            <Trash2 class="w-4 h-4" />
          </button>

          <!-- CHILD LEVEL 2+ -->
          <div v-if="child.children_recursive?.length" class="w-full ml-6 mt-2">

            <div
              v-for="grand in child.children_recursive"
              :key="grand.id"
              class="flex items-center justify-between mb-2"
            >
              <div class="bg-[#4f7d8a] text-white px-4 py-2 rounded w-full">
                {{ grand.nama }}
              </div>

              <button
                @click="hapusItem(grand.id)"
                class="ml-2 p-2 bg-gray-200 hover:bg-red-500 hover:text-white rounded"
              >
                <Trash2 class="w-4 h-4" />
              </button>
            </div>

          </div>

        </div>

      </div>

    </div>

  </div>

  <!-- ================= MODAL ================= -->
  <div
    v-if="showModal"
    class="fixed inset-0 bg-black/40 flex items-center justify-center"
  >
    <div class="bg-white p-6 rounded-xl w-[400px]">

      <h2 class="text-lg font-bold mb-4">Tambah Kategori</h2>

      <!-- PARENT SELECT -->
      <select
        v-model="selectedParent"
        class="w-full border p-2 mb-3 rounded"
      >
        <option :value="null">-- Root Kategori --</option>

        <option
          v-for="k in props.kategori"
          :key="k.id"
          :value="k.id"
        >
          {{ k.nama }}
        </option>
      </select>

      <!-- INPUT -->
      <input
        v-model="newItem"
        class="w-full border p-2 mb-4 rounded"
        placeholder="Nama kategori"
      />

      <!-- BUTTON -->
      <div class="flex justify-end gap-2">
        <button
          @click="showModal = false"
          class="px-4 py-2 bg-gray-300 rounded"
        >
          Batal
        </button>

        <button
          @click="tambahKategori"
          class="px-4 py-2 bg-blue-600 text-white rounded"
        >
          Simpan
        </button>
      </div>

    </div>
  </div>

</template>