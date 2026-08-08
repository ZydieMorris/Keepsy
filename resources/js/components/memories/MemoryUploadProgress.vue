<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
    total: number;
    progress: number;
}>();

const done = computed(() => Math.round((props.progress / 100) * props.total));
</script>

<template>
    <div class="grid gap-5 py-4">
        <div>
            <p class="text-sm font-medium text-foreground">
                Uploading memories…
            </p>
            <p class="mt-1 text-xs text-muted-foreground">
                {{ done }} of {{ total }}
                {{ total === 1 ? 'photo' : 'photos' }}
            </p>
        </div>

        <div
            class="h-2 w-full overflow-hidden rounded-full bg-muted"
            role="progressbar"
            :aria-valuenow="progress"
            aria-valuemin="0"
            aria-valuemax="100"
            aria-label="Upload progress"
        >
            <div
                class="h-full rounded-full bg-primary transition-[width] duration-150 ease-out"
                :style="{ width: `${progress}%` }"
            />
        </div>
    </div>
</template>
