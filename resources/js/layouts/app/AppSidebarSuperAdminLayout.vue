<script setup lang="ts">
import AppContent from '@/components/AppContent.vue';
import AppShell from '@/components/AppShell.vue';
import AppSidebar from '@/components/AppSidebarSuperAdmin.vue'; 
import AppSidebarHeader from '@/components/AppSidebarHeader.vue';

import Header from '@/components/header.vue';
import Footer from '@/components/footer.vue';

import type { BreadcrumbItem } from '@/types';

type Props = {
    breadcrumbs?: BreadcrumbItem[];
};

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <div class="flex flex-col min-h-screen">
        
        <Header />

        <div class="flex flex-1">
            <AppShell variant="sidebar" class="flex-1">
                <AppSidebar />

                <AppContent variant="sidebar" class="overflow-x-hidden flex flex-col">
                    <AppSidebarHeader :breadcrumbs="breadcrumbs" />

                    <div class="p-6 flex-1">
                        <slot />
                    </div>
                </AppContent>
            </AppShell>
        </div>

        <Footer />
        
    </div>
</template>