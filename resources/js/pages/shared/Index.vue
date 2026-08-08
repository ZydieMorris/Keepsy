<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Share2 } from '@lucide/vue';
import KeepsyEmptyState from '@/components/KeepsyEmptyState.vue';
</script>

<template>
    <Head title="Shared" />

    <div class="mx-auto w-full max-w-[1600px] px-5 md:px-6 lg:px-10">
        <KeepsyEmptyState
            :icon="Share2"
            title="Albums shared with you"
            description="When family or friends invite you to an album, it will appear here once you accept the invitation."
        />
    </div>
</template>
