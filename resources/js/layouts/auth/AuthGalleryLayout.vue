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
        class="flex min-h-svh flex-col gap-4 bg-background p-4 lg:grid lg:h-svh lg:grid-cols-[1fr_1.15fr] lg:gap-6 lg:p-6"
    >
        <div
            class="relative h-[34vh] min-h-56 shrink-0 overflow-hidden rounded-[2rem] bg-[#16324F] lg:hidden"
        >
            <img
                src="/images/login/hero-1.jpg"
                alt=""
                class="absolute inset-0 h-full w-full object-cover"
            />
            <div
                class="absolute inset-0 bg-gradient-to-tr from-[#16324F]/80 via-[#2A5681]/40 to-[#8FB6D9]/20"
            />
            <div class="absolute top-5 left-6 flex items-center gap-2.5">
                <div
                    class="flex h-9 w-9 items-center justify-center rounded-lg bg-white/10 ring-1 ring-white/25 backdrop-blur"
                >
                    <AppLogoIcon class="size-5 fill-current text-white" />
                </div>
                <span
                    class="text-xs font-semibold tracking-[0.25em] text-white uppercase"
                >
                    {{ name }}
                </span>
            </div>
        </div>

        <main
            class="flex w-full flex-1 flex-col justify-center gap-8 sm:mx-auto sm:max-w-md lg:order-1 lg:mx-auto lg:max-w-md lg:px-0"
        >
            <Link
                :href="home()"
                class="hidden items-center gap-3 font-medium lg:flex"
            >
                <div
                    class="flex h-10 w-10 items-center justify-center rounded-xl bg-primary text-primary-foreground"
                >
                    <AppLogoIcon class="size-5 fill-current" />
                </div>
                <span class="text-base font-semibold tracking-tight">{{
                    name
                }}</span>
            </Link>

            <div class="space-y-2 text-center lg:text-left">
                <h1 class="text-3xl font-semibold tracking-tight lg:text-4xl" v-if="title">
                    {{ title }}
                </h1>
                <p class="text-sm text-muted-foreground lg:text-base" v-if="description">
                    {{ description }}
                </p>
            </div>

            <slot />
        </main>

        <aside
            class="relative hidden min-h-0 overflow-hidden rounded-[2.5rem] bg-[#12263B] lg:order-2 lg:block"
        >
            <div class="grid h-full grid-cols-2 grid-rows-2 gap-3 p-6 lg:p-8">
                <div
                    class="group overflow-hidden rounded-2xl motion-safe:animate-fade-up"
                >
                    <img
                        src="/images/login/hero-1.jpg"
                        alt=""
                        class="h-full w-full object-cover transition-transform duration-700 ease-out group-hover:scale-[1.04]"
                    />
                </div>
                <div
                    class="group overflow-hidden rounded-2xl motion-safe:animate-fade-up [animation-delay:120ms]"
                >
                    <img
                        src="/images/login/tile-1.jpg"
                        alt=""
                        class="h-full w-full object-cover transition-transform duration-700 ease-out group-hover:scale-[1.04]"
                    />
                </div>
                <div
                    class="group overflow-hidden rounded-2xl motion-safe:animate-fade-up [animation-delay:240ms]"
                >
                    <img
                        src="/images/login/tile-2.jpg"
                        alt=""
                        class="h-full w-full object-cover transition-transform duration-700 ease-out group-hover:scale-[1.04]"
                    />
                </div>
                <div
                    class="group overflow-hidden rounded-2xl motion-safe:animate-fade-up [animation-delay:360ms]"
                >
                    <img
                        src="/images/login/tile-3.jpg"
                        alt=""
                        class="h-full w-full object-cover transition-transform duration-700 ease-out group-hover:scale-[1.04]"
                    />
                </div>
            </div>

            <div
                class="pointer-events-none absolute inset-0 bg-gradient-to-tr from-[#16324F]/60 via-[#2A5681]/25 to-[#8FB6D9]/10"
            />
            <div
                class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_at_center,transparent_40%,rgba(11,25,40,0.45)_100%)]"
            />

            <div
                class="pointer-events-none absolute inset-y-0 left-0 z-10 w-[30%] bg-gradient-to-r from-black/20 to-transparent"
            />

            <div
                class="pointer-events-none absolute inset-y-0 left-0 z-[15] w-[44%] opacity-60 blur-[16px]"
            >
                <svg
                    class="h-full w-full"
                    viewBox="0 0 100 342"
                    preserveAspectRatio="none"
                    aria-hidden="true"
                >
                    <path
                        d="M2 0 C 37 20, 60 48, 50 110 C 44 150, 32 178, 26 205 C 20 240, 44 264, 48 290 C 50 310, 28 330, 2 342 Z"
                        fill="var(--background)"
                    />
                </svg>
            </div>

            <svg
                class="absolute inset-y-0 left-0 z-20 h-full w-[30%] drop-shadow-[12px_0_28px_rgba(11,25,40,0.28)]"
                viewBox="0 0 100 342"
                preserveAspectRatio="none"
                aria-hidden="true"
            >
                <path
                    d="M0 0 C 35 20, 58 48, 48 110 C 42 150, 30 178, 24 205 C 18 240, 42 264, 46 290 C 48 310, 26 330, 0 342 Z"
                    fill="var(--background)"
                />
            </svg>

            <div
                class="absolute bottom-8 left-8 z-30 max-w-sm motion-safe:animate-fade-up [animation-delay:200ms]"
            >
                <p
                    class="rounded-2xl bg-[#0E2033]/45 px-5 py-4 text-2xl leading-snug font-light text-white/95 ring-1 ring-white/10 backdrop-blur-sm"
                >
                    A quiet entrance into your memories.
                </p>
            </div>
        </aside>
    </div>
</template>
