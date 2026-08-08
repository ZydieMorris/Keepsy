<script setup lang="ts">
import { Images, Plus } from '@lucide/vue';
import { Button } from '@/components/ui/button';

const emit = defineEmits<{
    add: [];
}>();
</script>

<template>
    <div
        class="flex min-h-[70vh] flex-col items-center justify-center px-6 text-center motion-safe:animate-fade-up"
    >
        <div
            class="mb-6 flex size-14 items-center justify-center rounded-full border border-border bg-card text-muted-foreground"
        >
            <Images class="size-6" :stroke-width="1.5" />
        </div>
        <h1
            class="text-xl font-semibold tracking-tight text-foreground"
        >
            Your memories are waiting.
        </h1>
        <p
            class="mt-2 max-w-sm text-sm leading-relaxed text-muted-foreground"
        >
            Add your first photos and start building your private gallery.
        </p>
        <Button class="mt-8" @click="emit('add')">
            <Plus class="size-4" />
            Add memories
        </Button>
    </div>
</template>
