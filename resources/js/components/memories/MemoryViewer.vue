<script setup lang="ts">
import { ChevronLeft, ChevronRight, Image as ImageIcon, X } from '@lucide/vue';
import { computed, nextTick, onBeforeUnmount, onMounted, useTemplateRef, watch } from 'vue';
import type { Photo } from '@/types';

const props = defineProps<{
    photos: Photo[];
}>();

const index = defineModel<number>('index', { required: true });

const emit = defineEmits<{
    close: [];
}>();

const total = computed(() => props.photos.length);

const current = computed<Photo | null>(
    () => props.photos[Math.min(index.value, total.value - 1)] ?? null,
);

const formattedDate = computed(() => {
    if (!current.value) {
        return '';
    }

    return new Intl.DateTimeFormat('en-US', {
        month: 'long',
        year: 'numeric',
    }).format(new Date(current.value.taken_at));
});

const closeButton = useTemplateRef('closeButton');

const goPrevious = (): void => {
    index.value = (index.value - 1 + total.value) % total.value;
};

const goNext = (): void => {
    index.value = (index.value + 1) % total.value;
};

const onKeydown = (event: KeyboardEvent): void => {
    if (event.key === 'Escape') {
        emit('close');

        return;
    }

    if (event.key === 'ArrowLeft') {
        goPrevious();
    }

    if (event.key === 'ArrowRight') {
        goNext();
    }
};

watch(index, async () => {
    await nextTick();
    closeButton.value?.focus();
});

onMounted(() => {
    window.addEventListener('keydown', onKeydown);
    document.body.style.overflow = 'hidden';
    closeButton.value?.focus();
});

onBeforeUnmount(() => {
    window.removeEventListener('keydown', onKeydown);
    document.body.style.overflow = '';
});
</script>

<template>
    <Teleport to="body">
        <div
            role="dialog"
            aria-modal="true"
            aria-label="Photo viewer"
            class="fixed inset-0 z-50 flex flex-col bg-[#111111]"
            @click.self="emit('close')"
        >
            <!-- Top bar -->
            <div
                class="flex items-center justify-between px-4 py-3 sm:px-6"
            >
                <p
                    class="text-sm text-white/70"
                >
                    {{ current?.title }}
                    <span v-if="formattedDate" class="text-white/40">
                        · {{ formattedDate }}
                    </span>
                </p>
                <button
                    ref="closeButton"
                    type="button"
                    class="flex size-10 items-center justify-center rounded-full text-white/80 transition-colors duration-200 hover:bg-white/10 hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white/60"
                    aria-label="Close viewer"
                    @click="emit('close')"
                >
                    <X class="size-5" />
                </button>
            </div>

            <!-- Image stage -->
            <div
                class="relative flex flex-1 items-center justify-center overflow-hidden px-4 pb-4 sm:px-16"
            >
                <Transition
                    name="viewer-fade"
                    mode="out-in"
                >
                    <img
                        v-if="current && !current.unviewable"
                        :key="current.id"
                        :src="current.full"
                        :alt="current.title"
                        class="max-h-full max-w-full rounded-[4px] object-contain shadow-2xl"
                    />
                    <div
                        v-else-if="current"
                        :key="current.id"
                        class="flex size-full flex-col items-center justify-center gap-4 text-white/50"
                    >
                        <ImageIcon class="size-16" :stroke-width="1" />
                        <p class="text-sm">Preview unavailable for this format</p>
                    </div>
                </Transition>

                <button
                    v-if="total > 1"
                    type="button"
                    class="absolute left-3 flex size-11 items-center justify-center rounded-full bg-white/5 text-white/80 backdrop-blur transition-colors duration-200 hover:bg-white/15 hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white/60 sm:left-6"
                    aria-label="Previous photo"
                    @click="goPrevious"
                >
                    <ChevronLeft class="size-6" />
                </button>
                <button
                    v-if="total > 1"
                    type="button"
                    class="absolute right-3 flex size-11 items-center justify-center rounded-full bg-white/5 text-white/80 backdrop-blur transition-colors duration-200 hover:bg-white/15 hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white/60 sm:right-6"
                    aria-label="Next photo"
                    @click="goNext"
                >
                    <ChevronRight class="size-6" />
                </button>
            </div>

            <!-- Footer -->
            <div class="flex items-center justify-between px-4 pb-4 sm:px-6">
                <span class="text-xs text-white/40" />
                <span class="text-xs text-white/50 tabular-nums">
                    {{ index + 1 }} / {{ total }}
                </span>
            </div>
        </div>
    </Teleport>
</template>

<style scoped>
.viewer-fade-enter-active,
.viewer-fade-leave-active {
    transition: opacity 250ms ease-out;
}

.viewer-fade-enter-from,
.viewer-fade-leave-to {
    opacity: 0;
}
</style>
