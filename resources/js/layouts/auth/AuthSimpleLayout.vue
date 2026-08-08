<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { home } from '@/routes';

const page = usePage();
const name = page.props.name;

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div
        class="flex min-h-svh flex-col items-center justify-center bg-background p-6 md:p-10"
    >
        <div class="flex w-full max-w-sm flex-col gap-8">
            <div class="flex flex-col items-center gap-6">
                <Link
                    :href="home()"
                    class="flex flex-col items-center gap-3 font-medium"
                >
                    <div
                        class="flex h-11 w-11 items-center justify-center rounded-xl bg-primary text-primary-foreground"
                    >
                        <AppLogoIcon class="size-6 fill-current" />
                    </div>
                    <span class="text-lg font-semibold tracking-tight">{{
                        name
                    }}</span>
                    <span class="sr-only">{{ title }}</span>
                </Link>
                <div class="space-y-2 text-center">
                    <h1 class="text-2xl font-semibold tracking-tight" v-if="title">
                        {{ title }}
                    </h1>
                    <p class="text-sm text-muted-foreground" v-if="description">
                        {{ description }}
                    </p>
                </div>
            </div>
            <slot />
        </div>
    </div>
</template>
