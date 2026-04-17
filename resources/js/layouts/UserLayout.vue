<script setup>
import Navbar from '@/components/Navbar.vue'
import UserSidebar from '@/components/sidebar/UserSidebar.vue'
import Footer from '@/components/footer.vue'
</script>

<template>
  <div class="flex flex-col min-h-screen">

    <!-- NAVBAR -->
    <Navbar />

    <div class="flex flex-1">

      <!-- SIDEBAR USER -->
      <UserSidebar />

      <!-- CONTENT -->
      <main class="flex-1 bg-gray-100">
        <slot />
      </main>

    </div>

    <!-- FOOTER -->
    <Footer />

  </div>
</template>