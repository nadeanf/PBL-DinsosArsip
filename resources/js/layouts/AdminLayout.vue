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
  <div class="flex flex-col min-h-screen">

    <!-- NAVBAR -->
    <Navbar />

    <div class="flex flex-1">

      <!-- SIDEBAR -->
      <AdminSidebar :user="user" />

      <!-- CONTENT -->
      <main class="flex-1 bg-gray-100 overflow-y-auto">
        <slot />
      </main>

    </div>

    <!-- FOOTER -->
    <Footer />

  </div>
</template>