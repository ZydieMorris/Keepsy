<script setup lang="ts">
import { ImagePlus } from '@lucide/vue';
import { ref } from 'vue';

const emit = defineEmits<{
    files: [files: File[]];
}>();

const dragDepth = ref(0);
const isDragging = ref(false);

const hasFiles = (event: DragEvent): boolean =>
    Array.from(event.dataTransfer?.types ?? []).includes('Files');

const onDragEnter = (event: DragEvent): void => {
    if (!hasFiles(event)) {
        return;
    }

    event.preventDefault();
    dragDepth.value += 1;
    isDragging.value = true;
};

const onDragOver = (event: DragEvent): void => {
    if (!hasFiles(event)) {
        return;
    }

    event.preventDefault();
};

const onDragLeave = (): void => {
    dragDepth.value = Math.max(0, dragDepth.value - 1);

    if (dragDepth.value === 0) {
        isDragging.value = false;
    }
};

const onDrop = (event: DragEvent): void => {
    event.preventDefault();
    dragDepth.value = 0;
    isDragging.value = false;

    const files = Array.from(event.dataTransfer?.files ?? []);

    if (files.length > 0) {
        emit('files', files);
    }
};
</script>

<template>
    <div
        class="relative"
        @dragenter="onDragEnter"
        @dragover="onDragOver"
        @dragleave="onDragLeave"
        @drop="onDrop"
    >
        <div :class="isDragging ? 'opacity-40' : 'opacity-100'">
            <slot />
        </div>

        <Transition
            enter-active-class="transition-opacity duration-200 ease-out"
            enter-from-class="opacity-0"
            leave-active-class="transition-opacity duration-200 ease-in"
            leave-to-class="opacity-0"
        >
            <div
                v-if="isDragging"
                class="pointer-events-none absolute inset-0 z-40 flex items-center justify-center bg-background/40 backdrop-blur-[2px]"
            >
                <div
                    class="flex items-center gap-2.5 rounded-full border border-border bg-card/95 px-6 py-3.5 text-sm font-medium text-foreground shadow-xl"
                >
                    <ImagePlus class="size-5 text-primary" />
                    Drop to add memories
                </div>
            </div>
        </Transition>
    </div>
</template>
