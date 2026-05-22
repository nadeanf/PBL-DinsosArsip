<script setup lang="ts">
import AppContent from '@/components/AppContent.vue';
import AppShell from '@/components/AppShell.vue';
import AppSidebar from '@/components/AppSidebarSuperAdmin.vue'; 
import AppSidebarHeader from '@/components/AppSidebarHeader.vue';

import Header from '@/components/header.vue';

import type { BreadcrumbItem } from '@/types';

type Props = {
    breadcrumbs?: BreadcrumbItem[];
};

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <div class="flex flex-col h-screen overflow-hidden">
        
        <Header />

        <div class="flex flex-1 overflow-hidden">
            <AppShell variant="sidebar" class="flex-1 overflow-hidden">
                <AppSidebar />

                <AppContent variant="sidebar" class="overflow-hidden flex flex-col">
                    <AppSidebarHeader :breadcrumbs="breadcrumbs" />

                    <div class="p-6 flex-1 overflow-hidden">
                        <slot />
                    </div>
                </AppContent>
            </AppShell>
        </div>
        
    </div>
</template>
