<script setup lang="ts">
import Header from '@/components/header.vue';
import Sidebar from '@/components/AppSidebarSuperAdmin.vue';
import { ref } from 'vue';

import type { BreadcrumbItem } from '@/types';

type Props = {
    breadcrumbs?: BreadcrumbItem[];
};

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

const sidebarOpen = ref(false);
</script>

<template>
    <div class="h-screen flex flex-col bg-gray-100 overflow-hidden">

        <!-- NAVBAR -->
        <header class="h-16 md:h-16 shrink-0">
            <Header @toggle-sidebar="sidebarOpen = !sidebarOpen" />
        </header>

        <!-- BODY -->
        <div class="flex flex-1 overflow-hidden">

            <!-- SIDEBAR - Desktop -->
            <aside class="hidden md:block md:w-64 lg:w-64 h-full overflow-y-auto overflow-x-hidden border-r border-gray-200 shrink-0">
                <Sidebar />
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
                <Sidebar />
            </aside>

            <!-- CONTENT -->
            <main class="flex-1 min-w-0 h-full overflow-y-auto overflow-x-hidden p-4 md:p-6 bg-gray-100 transition-all duration-200">
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