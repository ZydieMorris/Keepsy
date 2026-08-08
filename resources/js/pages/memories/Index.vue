<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { toast } from 'vue-sonner';
import MemoriesController from '@/actions/App/Http/Controllers/MemoriesController';
import MemoriesGallery from '@/components/memories/MemoriesGallery.vue';
import MemoryEmptyState from '@/components/memories/MemoryEmptyState.vue';
import MemoryViewer from '@/components/memories/MemoryViewer.vue';
import { useMemoryUpload } from '@/composables/useMemoryUpload';
import { memories } from '@/routes';
import type { Photo } from '@/types';

const props = defineProps<{
    photos: Photo[];
    next_cursor: string | null;
}>();

const { open, onUploaded } = useMemoryUpload();

const photos = computed(() => props.photos);
const isLoadingMore = ref(false);
const viewerIndex = ref<number | null>(null);

// Refresh the gallery from page one after a successful upload, so the new
// memories (taken_at = now) appear at the top.
onUploaded(() => {
    router.visit(memories().url, {
        only: ['photos', 'next_cursor'],
    });
});

const handleNearBottom = (): void => {
    if (isLoadingMore.value || !props.next_cursor) {
        return;
    }

    isLoadingMore.value = true;

    router.get(memories().url, { cursor: props.next_cursor }, {
        only: ['photos', 'next_cursor'],
        preserveState: true,
        preserveScroll: true,
        onFinish: () => {
            isLoadingMore.value = false;
        },
    });
};

const handleOpen = (index: number): void => {
    viewerIndex.value = index;
};

const handleToggleFavorite = (index: number): void => {
    const photo = photos.value[index];

    if (!photo) {
        return;
    }

    const previous = photo.favorite;
    photo.favorite = !previous;

    toast(
        photo.favorite ? 'Added to favorites' : 'Removed from favorites',
        { duration: 1800 },
    );

    // Persist in the background; the gallery already reflects the change.
    router.patch(MemoriesController.update(Number(photo.id)).url, { favorite: photo.favorite }, {
        only: ['flash'],
        preserveScroll: true,
        preserveState: true,
        onError: () => {
            photo.favorite = previous;
            toast.error('Could not update favorite');
        },
    });
};

const handleRename = (index: number, title: string): void => {
    const photo = photos.value[index];

    if (!photo) {
        return;
    }

    const previousTitle = photo.title;
    photo.title = title;

    toast('Memory renamed', { duration: 1800 });

    router.patch(MemoriesController.update(Number(photo.id)).url, { title }, {
        only: ['flash'],
        preserveScroll: true,
        preserveState: true,
        onError: () => {
            photo.title = previousTitle;
            toast.error('Could not rename memory');
        },
    });
};

const handleDelete = (index: number): void => {
    const photo = photos.value[index];

    if (!photo) {
        return;
    }

    photos.value.splice(index, 1);

    if (viewerIndex.value !== null && viewerIndex.value >= photos.value.length) {
        viewerIndex.value = Math.max(0, photos.value.length - 1);
    }

    toast('Memory removed', { duration: 1800 });

    router.delete(MemoriesController.destroy(Number(photo.id)).url, {
        only: ['flash'],
        preserveScroll: true,
        preserveState: true,
        onError: () => {
            // Restore the gallery from the server so the photo reappears.
            router.reload({ only: ['photos', 'next_cursor'] });
            toast.error('Could not delete memory');
        },
    });
};
</script>

<template>
    <Head title="Memories" />

    <MemoryEmptyState v-if="photos.length === 0" @add="open()" />

    <MemoriesGallery
        v-else
        :photos="photos"
        :loading-more="isLoadingMore"
        @open="handleOpen"
        @favorite="handleToggleFavorite"
        @rename="handleRename"
        @delete="handleDelete"
        @near-bottom="handleNearBottom"
    />

    <MemoryViewer
        v-if="viewerIndex !== null && photos.length > 0"
        :photos="photos"
        v-model:index="viewerIndex"
        @close="viewerIndex = null"
    />
</template>
