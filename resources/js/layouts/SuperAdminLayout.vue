<script setup>
import Navbar from '@/components/Navbar.vue'
import Sidebar from '@/components/AppSidebarSuperAdmin.vue' // Pastikan path file sesuai
import Footer from '@/components/footer.vue'
</script>

<template>
  <div class="flex min-h-screen bg-gray-100">

    <Sidebar />

    <div class="flex flex-col flex-1">
      
      <Navbar />

      <main class="flex-1 p-6">
        <slot />
      </main>

      <Footer />
    </div>

  </div>
</template>

<style scoped>
/* Tambahkan transisi halus jika diperlukan */
main {
  transition: all 0.3s ease-in-out;
}
</style>