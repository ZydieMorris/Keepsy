<script setup lang="ts">
import { Image as ImageIcon } from '@lucide/vue';

export type PreviewItem = {
    file: File;
    url: string;
    width: number;
    height: number;
    loaded: boolean;
};

defineProps<{
    items: PreviewItem[];
}>();
</script>

<template>
    <div class="grid gap-4">
        <div class="grid grid-cols-4 gap-2">
            <div
                v-for="item in items"
                :key="item.url"
                class="relative aspect-square overflow-hidden rounded-lg border border-border bg-muted"
            >
                <img
                    v-if="item.loaded"
                    :src="item.url"
                    :alt="item.file.name"
                    class="size-full object-cover"
                />
                <div
                    v-else
                    class="flex size-full items-center justify-center text-muted-foreground"
                >
                    <ImageIcon class="size-5" />
                </div>
            </div>
        </div>

        <p class="text-sm text-muted-foreground">
            {{ items.length }} {{ items.length === 1 ? 'photo' : 'photos' }}
            selected
        </p>

        <div class="flex items-center justify-between rounded-lg border border-border bg-muted/50 px-4 py-3">
            <span class="text-sm text-muted-foreground">
                Where should these memories go?
            </span>
            <span class="text-sm font-medium text-foreground">
                Personal memories
            </span>
        </div>
    </div>
</template>
