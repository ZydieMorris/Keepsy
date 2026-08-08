<script setup lang="ts">
import KeepsyHeader from '@/components/KeepsyHeader.vue';
import MemoryDropZone from '@/components/memories/MemoryDropZone.vue';
import MemoryUploadModal from '@/components/memories/MemoryUploadModal.vue';
import { Toaster } from '@/components/ui/sonner';
import { useMemoryUpload } from '@/composables/useMemoryUpload';

const { open } = useMemoryUpload();

const handleFiles = (files: File[]): void => {
    open(files);
};
</script>

<template>
    <div
        class="flex min-h-screen flex-col bg-background transition-colors duration-300 dark:bg-background"
    >
        <KeepsyHeader />
        <main class="flex-1">
            <MemoryDropZone @files="handleFiles">
                <slot />
            </MemoryDropZone>
        </main>
        <MemoryUploadModal />
        <Toaster />
    </div>
</template>
