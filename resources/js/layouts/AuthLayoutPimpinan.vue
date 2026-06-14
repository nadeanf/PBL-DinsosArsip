<script setup>
import Navbar from '@/components/Navbar.vue'
import Sidebar from '@/components/PimpinanSidebar.vue'
import Footer from '@/components/footer.vue'
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

const page = usePage()
const user = computed(() => page.props.auth?.user)

</script>

<template>
  <div class="flex flex-col h-screen overflow-hidden">

    <!-- NAVBAR -->
    <Navbar />

    <div class="flex flex-1 overflow-hidden">

      <!-- SIDEBAR -->
      <Sidebar />

      <!-- CONTENT -->
      <main class="flex-1 bg-gray-100 overflow-y-auto overflow-x-hidden">
        <slot />
      </main>

    </div>

  </div>
</template>