<script setup>
import { ref, computed } from 'vue'
import TreeNode from './TreeNode.vue'

const props = defineProps({
  data: Array,
  modelValue: [String, Number]
})

const emit = defineEmits(['update:modelValue'])

const openNodes = ref([])

// hanya root kategori
const rootData = computed(() => {
  return props.data.filter(item => item.parent_id === null)
})

// aktif/inaktif
const aktifData = computed(() => {
  return rootData.value.filter(
    item => item.nama?.trim().toLowerCase() !== 'vital'
  )
})

// vital
const vitalData = computed(() => {
  return rootData.value.filter(
    item => item.nama?.trim().toLowerCase() === 'vital'
  )
})

// otomatis buka vital
vitalData.value.forEach(item => {
  openNodes.value.push(item.id)
})

// ambil semua child recursive
const getAllChildIds = (node) => {
  let ids = []

  if (node.children_recursive && node.children_recursive.length) {
    node.children_recursive.forEach(child => {
      ids.push(child.id)
      ids = ids.concat(getAllChildIds(child))
    })
  }

  return ids
}

// toggle
const toggleNode = (node) => {
  const id = node.id

  if (openNodes.value.includes(id)) {
    const childIds = getAllChildIds(node)

    openNodes.value = openNodes.value.filter(
      i => i !== id && !childIds.includes(i)
    )
  } else {
    openNodes.value.push(id)
  }
}

// select
const selectNode = (item) => {
  emit('update:modelValue', item.id)
}
</script>

<template>
  <div class="bg-white rounded-lg p-2 max-h-[300px] overflow-y-auto">

    <!-- AKTIF -->
    <div class="text-xs text-gray-400 px-2 py-1">
      Aktif/Inaktif
    </div>

    <TreeNode
      v-for="item in aktifData"
      :key="item.id"
      :item="item"
      :level="0"
      :openNodes="openNodes"
      @toggle="toggleNode"
      @select="selectNode"
    />

    <!-- VITAL -->
    <div class="text-xs text-gray-400 px-2 py-1 mt-3">
      Vital
    </div>

    <TreeNode
      v-for="item in vitalData"
      :key="item.id"
      :item="item"
      :level="0"
      :openNodes="openNodes"
      @toggle="toggleNode"
      @select="selectNode"
    />

  </div>
</template>