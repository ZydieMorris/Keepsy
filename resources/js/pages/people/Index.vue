<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Users } from '@lucide/vue';
import KeepsyEmptyState from '@/components/KeepsyEmptyState.vue';
</script>

<template>
    <Head title="People" />

    <div class="mx-auto w-full max-w-[1600px] px-5 md:px-6 lg:px-10">
        <KeepsyEmptyState
            :icon="Users"
            title="Share with the people you trust"
            description="Invite friends and family to your albums and manage who can see your memories here."
        />
    </div>
</template>
