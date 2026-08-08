<script setup lang="ts">
import { Image as ImageIcon } from '@lucide/vue';
import { computed } from 'vue';
import MemoryPhotoActions from '@/components/memories/MemoryPhotoActions.vue';
import type { Photo } from '@/types';

const props = defineProps<{
    photo: Photo;
    /** Animation delay in ms for the staggered entrance. */
    delay?: number;
}>();

/**
 * Reserved tile ratio, guarded so degenerate (0/negative) dimensions fall back
 * to a natural-size tile instead of an invalid `aspect-ratio` value.
 */
const tileRatio = computed<string | undefined>(() =>
    props.photo.width > 0 && props.photo.height > 0
        ? `${props.photo.width} / ${props.photo.height}`
        : undefined,
);

const emit = defineEmits<{
    open: [];
    favorite: [];
    rename: [title: string];
    delete: [];
}>();

const formattedDate = new Intl.DateTimeFormat('en-US', {
    month: 'long',
    day: 'numeric',
    year: 'numeric',
}).format(new Date(props.photo.taken_at));
</script>

<template>
    <!--
        The tile reserves its exact aspect-ratio up front (subtle placeholder
        behind the image) so the gallery layout is stable from the first paint
        and can never collapse to full-width/enlarged tiles while images load.
        The image itself stays `w-full h-auto` so its natural ratio is preserved.
        See docs/memories-refresh-layout-spec.md §7 R2.
    -->
    <figure
        class="group relative block w-full overflow-hidden rounded-[6px] bg-muted/40 motion-safe:animate-memory-enter"
        :style="{
            aspectRatio: tileRatio,
            animationDelay: `${delay}ms`,
        }"
    >
        <button
            type="button"
            class="block w-full cursor-zoom-in focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring/60 focus-visible:ring-offset-2 focus-visible:ring-offset-background"
            :aria-label="`View ${photo.title}`"
            @click="emit('open')"
        >
            <img
                v-if="!photo.unviewable"
                :src="photo.src"
                :alt="photo.title"
                :width="photo.width"
                :height="photo.height"
                loading="lazy"
                class="block h-auto w-full transition-transform duration-300 ease-out group-hover:scale-[1.015]"
            />
            <div
                v-else
                class="flex w-full items-center justify-center bg-muted text-muted-foreground"
                :style="{ aspectRatio: tileRatio }"
            >
                <ImageIcon class="size-8" :stroke-width="1.5" />
            </div>
        </button>

        <!-- Minimal metadata overlay -->
        <figcaption
            class="pointer-events-none absolute inset-x-0 bottom-0 flex items-end justify-between gap-3 bg-gradient-to-t from-black/40 via-black/10 to-transparent p-3 opacity-0 transition-opacity duration-300 group-hover:opacity-100"
        >
            <div class="min-w-0">
                <p class="truncate text-[13px] font-medium text-white">
                    {{ photo.title }}
                </p>
                <p class="truncate text-xs text-white/70">
                    {{ formattedDate }}
                </p>
            </div>
        </figcaption>

        <MemoryPhotoActions
            :photo="photo"
            class="absolute top-2 right-2 opacity-0 transition-opacity duration-300 group-hover:opacity-100 focus-within:opacity-100"
            @favorite="emit('favorite')"
            @rename="(title) => emit('rename', title)"
            @delete="emit('delete')"
        />
    </figure>
</template>
