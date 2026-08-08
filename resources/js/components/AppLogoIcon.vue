<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

type Props = {
    className?: HTMLAttributes['class'];
};

defineProps<Props>();
</script>

<template>
    <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 24 24"
        :class="className"
        v-bind="$attrs"
    >
        <path
            fill="currentColor"
            fill-rule="evenodd"
            clip-rule="evenodd"
            d="M6.5 5h11A2.5 2.5 0 0 1 20 7.5v9a2.5 2.5 0 0 1-2.5 2.5h-11A2.5 2.5 0 0 1 4 16.5v-9A2.5 2.5 0 0 1 6.5 5Zm5.5 12.35-.95-.85C7.9 13.68 6 11.9 6 9.65 6 7.78 7.44 6.4 9.15 6.4c1 0 1.95.47 2.85 1.45.9-.98 1.85-1.45 2.85-1.45C16.56 6.4 18 7.78 18 9.65c0 2.25-1.9 4.03-5.05 6.85L12 17.35Z"
        />
    </svg>
</template>
