<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Images, Plus } from '@lucide/vue';
import { toast } from 'vue-sonner';
import KeepsyEmptyState from '@/components/KeepsyEmptyState.vue';
import { Button } from '@/components/ui/button';

const handleUploadClick = () => {
    toast('Uploads are coming soon');
};
</script>

<template>
    <Head title="Memories" />

    <div class="mx-auto w-full max-w-[1600px] px-5 md:px-6 lg:px-10">
        <KeepsyEmptyState
            :icon="Images"
            title="Your memories will live here"
            description="Upload photos and organize them into private albums to start building your gallery."
        >
            <Button
                variant="outline"
                class="rounded-full"
                data-test="upload-button"
                @click="handleUploadClick"
            >
                <Plus class="size-4" />
                Upload photos
            </Button>
        </KeepsyEmptyState>
    </div>
</template>
