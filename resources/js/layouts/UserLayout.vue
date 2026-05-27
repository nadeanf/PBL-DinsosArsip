<script setup>
import Navbar from '@/components/Navbar.vue'
import UserSidebar from '@/components/sidebar/UserSidebar.vue'
import { ref } from 'vue'

const sidebarOpen = ref(false)
</script>

<template>
  <div class="h-screen flex flex-col bg-gray-100 overflow-hidden">

    <!-- NAVBAR -->
    <header class="h-16 md:h-16 shrink-0">
      <Navbar @toggle-sidebar="sidebarOpen = !sidebarOpen" />
    </header>

    <!-- BODY -->
    <div class="flex flex-1 overflow-hidden">

      <!-- SIDEBAR - Desktop -->
      <aside
        class="hidden md:block md:w-64 lg:w-64 shrink-0 h-full overflow-y-auto overflow-x-hidden border-r border-gray-200"
      >
        <UserSidebar />
      </aside>

      <!-- SIDEBAR - Mobile (Overlay) -->
      <div
        v-if="sidebarOpen"
        class="md:hidden fixed inset-0 z-40 bg-black/50"
        @click="sidebarOpen = false"
      />
      <aside
        v-show="sidebarOpen"
        class="md:hidden fixed left-0 top-16 bottom-0 w-64 z-50 h-full overflow-y-auto overflow-x-hidden shadow-lg"
      >
        <UserSidebar />
      </aside>

      <!-- CONTENT -->
      <main
        class="flex-1 min-w-0 h-full overflow-y-auto overflow-x-hidden bg-gray-100 p-4 md:p-6"
      >
        <slot />
      </main>

    </div>

  </div>
</template>