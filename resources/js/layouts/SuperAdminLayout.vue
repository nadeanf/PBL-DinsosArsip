<script setup>
import Navbar from '@/components/Navbar.vue'
import Sidebar from '@/components/AppSidebarSuperAdmin.vue'
</script>

<template>
  <div class="h-screen flex flex-col bg-gray-100 overflow-hidden">

    <!-- NAVBAR -->
    <header class="h-16 shrink-0">
      <Navbar />
    </header>

    <!-- BODY -->
    <div class="flex flex-1 overflow-hidden">

      <!-- SIDEBAR -->
      <aside class="w-72 h-full overflow-y-auto overflow-x-hidden shrink-0">
        <Sidebar />
      </aside>

      <!-- CONTENT -->
      <main class="flex-1 min-w-0 h-full overflow-y-auto overflow-x-hidden p-6">
        <slot />
      </main>

    </div>

  </div>
</template>

<style scoped>
main {
  transition: all 0.3s ease-in-out;
}
</style>