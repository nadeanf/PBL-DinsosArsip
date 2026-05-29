<script setup>
import Navbar from '@/components/Navbar.vue'
import AdminSidebar from '@/components/sidebar/AdminSidebar.vue'
import Footer from '@/components/footer.vue'
import { computed, ref } from 'vue'
import { usePage } from '@inertiajs/vue3'

const page = usePage()
const user = computed(() => page.props.auth?.user)
const sidebarOpen = ref(false)
</script>

<template>
  <!-- FULL SCREEN -->
  <div class="h-screen flex flex-col bg-gray-100 overflow-hidden">

    <!-- NAVBAR -->
    <header class="h-16 md:h-16 shrink-0">
      <Navbar @toggle-sidebar="sidebarOpen = !sidebarOpen" />
    </header>

    <!-- BODY -->
    <div class="flex flex-1 overflow-hidden w-full">

      <!-- SIDEBAR - Desktop -->
      <aside
        class="hidden md:block md:w-64 lg:w-64 h-full overflow-y-auto overflow-x-hidden bg-white border-r border-gray-200 shrink-0"
      >
        <AdminSidebar :user="user" />
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
        <AdminSidebar :user="user" />
      </aside>

      <!-- CONTENT -->
      <main
        class="flex-1 h-full min-w-0 overflow-y-auto overflow-x-hidden p-4 md:p-6 bg-gray-100 transition-all duration-200"
      >
        <slot />
      </main>

    </div>
  </div>
</template>

<style scoped>
main {
  transition: all 0.2s ease-in-out;
}

/* GLOBAL FIX */
:global(*) {
  box-sizing: border-box;
}

:global(html),
:global(body),
:global(#app) {
  width: 100%;
  height: 100%;
  margin: 0;
  overflow: hidden;
}

/* semua element ikut ukuran */
:global(*) {
  box-sizing: border-box;
}
</style>