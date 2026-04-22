<script setup>
import Navbar from '@/components/Navbar.vue'
import AdminSidebar from '@/components/sidebar/AdminSidebar.vue'
import Footer from '@/components/footer.vue'
</script>

<template>
  <div class="flex flex-col min-h-screen">

    <!-- NAVBAR -->
    <Navbar />

    <div class="flex flex-1 overflow-hidden">

      <!-- SIDEBAR -->
      <AdminSidebar />

      <!-- CONTENT -->
      <main class="flex-1 bg-gray-100 overflow-y-auto">
        <slot />
      </main>

    </div>

    <!-- FOOTER -->
    <Footer />

  </div>
</template>