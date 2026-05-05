<script setup>
import { computed } from 'vue'
import TreeNode from './TreeNode.vue'

defineOptions({
  name: 'TreeNode' 
})

const props = defineProps({
  item: Object,
  level: Number,
  openNodes: Array
})

const emit = defineEmits(['toggle', 'select'])

const isOpen = computed(() =>
  props.openNodes.includes(props.item.id)
)

const hasChildren = computed(() =>
  Array.isArray(props.item.children_recursive) &&
  props.item.children_recursive.length > 0
)
</script>

<template>
  <div>

    <!-- ITEM -->
    <div
      class="flex items-center gap-2 cursor-pointer py-1 hover:bg-gray-100 rounded"
      :style="{ paddingLeft: (level * 16) + 'px' }"
    >
      <span
        class="w-4 text-black font-black" 
        @click.stop="emit('toggle', item)"
      >
        <span v-if="hasChildren">
          {{ isOpen ? '▾' : '▸' }}
        </span>
        <span v-else>•</span>
      </span>

      <span
        class="text-sm text-black font-bold"
        @click.stop="emit('select', item)"
      >
        {{ item.nama }}
      </span>

    </div>

    <div v-if="hasChildren && isOpen">
      <TreeNode
        v-for="child in item.children_recursive"
        :key="child.id + '-' + level"
        :item="child"
        :level="level + 1"
        :openNodes="openNodes"
        @toggle="emit('toggle', $event)"
        @select="emit('select', $event)"
      />
    </div>
  </div>
</template>