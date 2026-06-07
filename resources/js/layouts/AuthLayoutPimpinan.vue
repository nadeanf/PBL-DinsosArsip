<script setup>
import Navbar from '@/components/Navbar.vue'
import Sidebar from '@/components/PimpinanSidebar.vue'
import Footer from '@/components/footer.vue'
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

const page = usePage()
const user = computed(() => page.props.auth?.user)

</script>

<template>
  <div class="flex flex-col min-h-screen pimpinan-root">

    <!-- NAVBAR -->
    <Navbar />

    <div class="flex flex-1">

      <!-- SIDEBAR -->
      <Sidebar />

      <!-- CONTENT -->
      <main class="flex-1 bg-gray-100">
        <slot />
      </main>

    </div>

  </div>
</template>

<style>
/* Pimpinan text color palette (Figma: hitam / abu-abu / putih)
   - Default text: near-black for readability
   - Muted text: mid gray
   - Inverse text: pure white for dark backgrounds
   These rules only change color properties (font colors).
*/
.pimpinan-root {
  color: #0f172a; /* slate-900 / near black */
}
.pimpinan-root .muted,
.pimpinan-root small,
.pimpinan-root .text-muted {
  color: #6b7280 !important; /* gray-500 */
}
.pimpinan-root .inverse,
.pimpinan-root .text-inverse,
.pimpinan-root .text-white {
  color: #ffffff !important;
}

/* Headings stronger */
.pimpinan-root h1, .pimpinan-root h2, .pimpinan-root h3, .pimpinan-root h4 {
  color: #0b1220; /* slightly stronger black */
}

/* Ensure table text and buttons follow palette */
.pimpinan-root table,
.pimpinan-root th,
.pimpinan-root td,
.pimpinan-root button,
.pimpinan-root a {
  color: inherit;
}

/* Keep links readable */
.pimpinan-root a { text-decoration-color: rgba(11,18,32,0.08); }
</style>