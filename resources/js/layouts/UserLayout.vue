<script setup>
import Navbar from '@/components/Navbar.vue'
import UserSidebar from '@/components/sidebar/UserSidebar.vue'
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
      <aside
        class="w-64 shrink-0 h-full overflow-y-auto overflow-x-hidden"
      >
        <UserSidebar />
      </aside>

      <!-- CONTENT -->
      <main
        class="flex-1 min-w-0 h-full overflow-y-auto overflow-x-hidden bg-gray-100"
      >
        <slot />
      </main>

    </div>

  </div>
</template>