<script setup lang="ts">
import { Image as ImageIcon } from '@lucide/vue';
import type { LucideIcon } from '@lucide/vue';

withDefaults(
    defineProps<{
        title: string;
        description: string;
        icon?: LucideIcon;
    }>(),
    {
        icon: ImageIcon,
    },
);
</script>

<template>
    <div
        class="flex flex-col items-center justify-center px-6 py-24 text-center animate-fade-up"
    >
        <div
            class="mb-6 flex size-16 items-center justify-center rounded-full border border-neutral-900/5 bg-white text-neutral-400 dark:border-white/10 dark:bg-neutral-900 dark:text-neutral-500"
        >
            <component :is="icon" class="size-7" :stroke-width="1.5" />
        </div>
        <h1
            class="text-xl font-semibold tracking-tight text-neutral-900 dark:text-neutral-100"
        >
            {{ title }}
        </h1>
        <p
            class="mt-2 max-w-sm text-sm leading-relaxed text-neutral-500 dark:text-neutral-400"
        >
            {{ description }}
        </p>
        <div v-if="$slots.default" class="mt-8">
            <slot />
        </div>
    </div>
</template>
