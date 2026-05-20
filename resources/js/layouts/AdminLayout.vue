<script setup>
import Navbar from '@/components/Navbar.vue'
import AdminSidebar from '@/components/sidebar/AdminSidebar.vue'
import Footer from '@/components/footer.vue'
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

const page = usePage()
const user = computed(() => page.props.auth?.user)
</script>

<template>
  <!-- FULL SCREEN -->
  <div class="h-screen flex flex-col bg-gray-100 overflow-hidden">

    <!-- NAVBAR -->
    <header class="h-16 shrink-0">
      <Navbar />
    </header>

    <!-- BODY -->
    <div class="flex flex-1 overflow-hidden w-full">

      <!-- SIDEBAR -->
      <aside
        class="w-67 h-full overflow-y-auto overflow-x-hidden bg-white border-r shrink-0"
      >
        <AdminSidebar :user="user" />
      </aside>

      <!-- CONTENT -->
      <main
        class="flex-1 h-full min-w-0 overflow-y-auto overflow-x-hidden p-6 bg-gray-100"
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