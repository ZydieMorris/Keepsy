<script setup lang="ts">
import { useIntersectionObserver } from '@vueuse/core';
import { computed, onBeforeUnmount, onMounted, ref, useTemplateRef } from 'vue';
import MemoriesColumn from '@/components/memories/MemoriesColumn.vue';
import { Spinner } from '@/components/ui/spinner';
import type { Photo } from '@/types';

const props = defineProps<{
    photos: Photo[];
    loadingMore: boolean;
}>();

const emit = defineEmits<{
    open: [index: number];
    favorite: [index: number];
    rename: [index: number, title: string];
    delete: [index: number];
    nearBottom: [];
}>();

type GalleryItem = {
    photo: Photo;
    index: number;
};

/**
 * Column count resolved deterministically from the viewport width.
 *
 * Mirrors the Tailwind breakpoints (lg = 1024px, md = 768px) used by the
 * design spec. Resolved synchronously at setup and kept in sync with a resize
 * listener, so the initial render never depends on an async media-query
 * initial value — see docs/memories-refresh-layout-spec.md §6/§8.
 */
const columnCount = ref(resolveColumnCount());

function resolveColumnCount(): number {
    if (typeof window === 'undefined') {
        return 1;
    }

    return window.innerWidth >= 1024 ? 3 : window.innerWidth >= 768 ? 2 : 1;
}

function onViewportChange(): void {
    columnCount.value = resolveColumnCount();
}

onMounted(() => {
    window.addEventListener('resize', onViewportChange);
});

onBeforeUnmount(() => {
    window.removeEventListener('resize', onViewportChange);
});

/**
 * Greedy shortest-column distribution: each photo joins the column with the
 * smallest cumulative aspect-ratio height, so columns stay visually balanced
 * while every image keeps its natural ratio.
 */
const columns = computed<GalleryItem[][]>(() => {
    const buckets = Array.from({ length: columnCount.value }, () => ({
        items: [] as GalleryItem[],
        height: 0,
    }));

    props.photos.forEach((photo, index) => {
        const ratio = photo.height / photo.width;
        let target = 0;

        for (let i = 1; i < buckets.length; i++) {
            if (buckets[i].height < buckets[target].height) {
                target = i;
            }
        }

        buckets[target].items.push({ photo, index });
        buckets[target].height += ratio;
    });

    return buckets.map((bucket) => bucket.items);
});

const sentinel = useTemplateRef('sentinel');

useIntersectionObserver(sentinel, ([entry]) => {
    if (entry.isIntersecting) {
        emit('nearBottom');
    }
});
</script>

<template>
    <section
        class="px-3 pt-3 pb-10 sm:px-4 md:px-6 md:pt-4 lg:px-8"
        aria-label="Your memories"
    >
        <div
            class="grid gap-3 md:gap-4 lg:gap-5"
            :style="{ gridTemplateColumns: `repeat(${columnCount}, minmax(0, 1fr))` }"
        >
            <MemoriesColumn
                v-for="(column, columnIndex) in columns"
                :key="columnIndex"
                :items="column"
                @open="(index) => emit('open', index)"
                @favorite="(index) => emit('favorite', index)"
                @rename="(index, title) => emit('rename', index, title)"
                @delete="(index) => emit('delete', index)"
            />
        </div>

        <div
            ref="sentinel"
            class="flex h-16 items-center justify-center"
            aria-hidden="true"
        >
            <Spinner v-if="loadingMore" class="size-5 text-muted-foreground" />
        </div>
    </section>
</template>
