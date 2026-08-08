<script setup lang="ts">
import KeepsyHeader from '@/components/KeepsyHeader.vue';
import { Toaster } from '@/components/ui/sonner';
</script>

<template>
    <div
        class="flex min-h-screen flex-col bg-[#FDFDFC] transition-colors duration-300 dark:bg-[#0a0a0a]"
    >
        <KeepsyHeader />
        <main class="flex-1">
            <slot />
        </main>
        <Toaster />
    </div>
</template>
