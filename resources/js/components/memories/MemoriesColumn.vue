<script setup lang="ts">
import MemoryPhoto from '@/components/memories/MemoryPhoto.vue';
import type { Photo } from '@/types';

type GalleryItem = {
    photo: Photo;
    index: number;
};

defineProps<{
    items: GalleryItem[];
}>();

const emit = defineEmits<{
    open: [index: number];
    favorite: [index: number];
    rename: [index: number, title: string];
    delete: [index: number];
}>();
</script>

<template>
    <div class="flex flex-col gap-3 md:gap-4 lg:gap-5">
        <MemoryPhoto
            v-for="item in items"
            :key="item.photo.id"
            :photo="item.photo"
            :delay="Math.min(item.index * 40, 400)"
            @open="emit('open', item.index)"
            @favorite="emit('favorite', item.index)"
            @rename="(title) => emit('rename', item.index, title)"
            @delete="emit('delete', item.index)"
        />
    </div>
</template>
