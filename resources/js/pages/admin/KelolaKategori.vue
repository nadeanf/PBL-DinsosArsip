<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3'
import { computed, ref } from 'vue'
import AdminLayout from '@/layouts/AdminLayout.vue'
import { Plus, Pencil, Trash2 } from 'lucide-vue-next'

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
const showRenameModal = ref(false)
const renameItemId = ref<number | null>(null)
const renameItemName = ref('')

const kategoriList = computed(() => {
  const list: any[] = []

  const walk = (items: any[] = [], level = 0) => {
    items.forEach((item) => {
      list.push({ ...item, level })
      if (item.children_recursive?.length) {
        walk(item.children_recursive, level + 1)
      }
    })
  }

  walk(props.kategori || [])
  return list
})

const optionLabel = (name: string, level: number) => {
  return level > 0 ? '— '.repeat(level) + name : name
}

const categoryBgClass = (level: number) => {
  switch (level) {
    case 0:
      return 'bg-white text-slate-900 border border-slate-200 font-semibold'
    case 1:
      return 'bg-sky-100 text-slate-800'
    case 2:
      return 'bg-amber-100 text-slate-800'
    case 3:
      return 'bg-emerald-100 text-slate-800'
    case 4:
      return 'bg-sky-100 text-slate-800'
    default:
      return 'bg-purple-100 text-slate-800'
  }
}

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

const openRenameModal = (item: any) => {
  renameItemId.value = item.id
  renameItemName.value = item.nama
  showRenameModal.value = true
}

const renameKategori = () => {
  if (!renameItemId.value || !renameItemName.value.trim()) return

  router.patch(`/kategori/${renameItemId.value}`, {
    nama: renameItemName.value
  }, {
    onSuccess: () => {
      showRenameModal.value = false
      renameItemId.value = null
      renameItemName.value = ''
    }
  })
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
    <div class="space-y-2">
      <div
        v-for="item in kategoriList"
        :key="item.id"
        class="flex items-center justify-between rounded"
        :class="categoryBgClass(item.level)"
        :style="{ marginLeft: `${item.level * 1.5}rem` }"
      >
        <div class="px-4 py-3 w-full">
          {{ item.nama }}
        </div>

        <div class="flex items-center gap-2">
          <button
            @click="openRenameModal(item)"
            class="p-2 bg-gray-200 hover:bg-blue-500 hover:text-white rounded"
            title="Rename kategori"
          >
            <Pencil class="w-4 h-4" />
          </button>

          <button
            @click="hapusItem(item.id)"
            class="p-2 bg-gray-200 hover:bg-red-500 hover:text-white rounded"
            title="Hapus kategori"
          >
            <Trash2 class="w-4 h-4" />
          </button>
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
          v-for="k in kategoriList"
          :key="k.id"
          :value="k.id"
        >
          {{ optionLabel(k.nama, k.level) }}
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

  <div
    v-if="showRenameModal"
    class="fixed inset-0 bg-black/40 flex items-center justify-center"
  >
    <div class="bg-white p-6 rounded-xl w-[400px]">
      <h2 class="text-lg font-bold mb-4">Rename Kategori</h2>

      <input
        v-model="renameItemName"
        class="w-full border p-2 mb-4 rounded"
        placeholder="Nama baru kategori"
      />

      <div class="flex justify-end gap-2">
        <button
          @click="showRenameModal = false"
          class="px-4 py-2 bg-gray-300 rounded"
        >
          Batal
        </button>

        <button
          @click="renameKategori"
          class="px-4 py-2 bg-blue-600 text-white rounded"
        >
          Simpan
        </button>
      </div>
    </div>
  </div>

</template>