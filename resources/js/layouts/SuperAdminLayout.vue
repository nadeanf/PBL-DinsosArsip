<script setup>
import Navbar from '@/components/Navbar.vue'
import Sidebar from '@/components/AppSidebarSuperAdmin.vue' 
import Footer from '@/components/footer.vue'
</script>

<template>
  <div class="flex flex-col min-h-screen bg-gray-100">

    <!-- NAVBAR -->
    <Navbar />

    <div class="flex flex-1 overflow-hidden">

      <!-- SIDEBAR -->
      <Sidebar />

      <!-- CONTENT -->
      <main class="flex-1 p-6 overflow-y-auto">
        <slot />
      </main>

    </div>

    <!-- FOOTER -->
    <Footer />

  </div>
</template>

<style scoped>
main {
  transition: all 0.3s ease-in-out;
}
</style>