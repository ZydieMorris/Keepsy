<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ArrowRight, ShieldCheck } from '@lucide/vue';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { login, register } from '@/routes';
</script>

<template>
    <Head title="Welcome" />
    <div
        class="flex min-h-svh flex-col overflow-hidden bg-[#FDFDFC] text-neutral-900 transition-colors duration-300 dark:bg-[#0a0a0a] dark:text-neutral-100"
    >
        <!-- Navigation -->
        <header
            class="relative z-30 mx-auto flex w-full max-w-6xl items-center justify-between px-6 py-6 md:px-8 lg:py-8"
        >
            <Link
                :href="login()"
                class="group flex items-center gap-2.5"
            >
                <span
                    class="flex size-9 items-center justify-center rounded-xl bg-primary text-primary-foreground shadow-sm transition-transform duration-300 group-hover:scale-105"
                >
                    <AppLogoIcon class="size-5 fill-current" />
                </span>
                <span
                    class="font-sans text-xl font-bold tracking-tight transition-opacity duration-300 group-hover:opacity-75"
                >
                    Keepsy
                </span>
            </Link>

            <!-- <nav class="flex items-center gap-1.5" aria-label="Auth">
                <Link
                    :href="login()"
                    class="rounded-full px-4 py-2 text-sm font-medium text-neutral-500 transition-colors duration-300 hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-neutral-100"
                >
                    Log in
                </Link>
                <Link
                    :href="register()"
                    class="rounded-full bg-neutral-900 px-4 py-2 text-sm font-medium text-white shadow-sm transition-colors duration-300 hover:bg-neutral-700 dark:bg-white dark:text-neutral-900 dark:hover:bg-neutral-200"
                >
                    Get started
                </Link>
            </nav> -->
        </header>

        <!-- Hero -->
        <main
            class="mx-auto grid w-full max-w-6xl flex-1 items-center gap-14 px-6 pb-20 md:px-8 lg:grid-cols-[1.05fr_1fr] lg:gap-20 lg:pb-28"
        >
            <div class="max-w-xl motion-safe:animate-fade-up">
                <span
                    class="inline-flex items-center gap-2 rounded-full border border-primary/20 bg-primary/[0.06] px-3.5 py-1.5 text-[11px] font-semibold tracking-[0.18em] text-primary uppercase dark:border-primary/25 dark:bg-primary/10"
                >
                    <span class="size-1.5 rounded-full bg-primary" />
                    Private photo sharing
                </span>

                <h1
                    class="mt-7 text-[2.6rem] leading-[1.05] font-semibold tracking-tight sm:text-6xl"
                >
                    Your memories.<br />
                    Your people.<br />
                    <span class="text-primary">Your space.</span>
                </h1>

                <p
                    class="mt-6 max-w-md text-base leading-relaxed text-neutral-500 sm:text-lg dark:text-neutral-400"
                >
                    A quiet, private place to keep the photos that matter —
                    and share them with only the people you trust.
                </p>

                <div class="mt-9 flex flex-wrap items-center gap-3">
                    <Link
                        :href="register()"
                        class="group inline-flex items-center gap-2 rounded-full bg-primary px-6 py-3 text-sm font-semibold text-primary-foreground shadow-lg shadow-primary/20 transition-all duration-300 hover:-translate-y-0.5 hover:bg-primary/90 hover:shadow-xl hover:shadow-primary/25"
                    >
                        Start sharing
                        <ArrowRight
                            class="size-4 transition-transform duration-300 group-hover:translate-x-0.5"
                        />
                    </Link>
                    <Link
                        :href="login()"
                        class="inline-flex items-center rounded-full border border-neutral-900/15 px-6 py-3 text-sm font-semibold text-neutral-700 transition-colors duration-300 hover:border-neutral-900/30 hover:bg-neutral-900/[0.03] dark:border-white/15 dark:text-neutral-200 dark:hover:border-white/30 dark:hover:bg-white/[0.04]"
                    >
                        Log in
                    </Link>
                </div>

                <p
                    class="mt-10 flex items-center gap-2 text-xs font-medium text-neutral-400 dark:text-neutral-500"
                >
                    <ShieldCheck class="size-4" stroke-width="1.75" />
                    Private by design — nothing you keep here is public.
                </p>
            </div>

            <!-- Photo collage -->
            <div
                class="relative mx-auto w-full max-w-md motion-safe:animate-fade-up [animation-delay:150ms] lg:max-w-none"
            >
                <div
                    class="absolute -inset-10 rounded-full bg-primary/10 blur-3xl dark:bg-primary/15"
                    aria-hidden="true"
                />
                <div class="relative grid grid-cols-2 gap-4 sm:gap-5">
                    <div
                        class="aspect-[3/4] rotate-[-2.5deg] overflow-hidden rounded-[1.75rem] shadow-2xl shadow-neutral-900/20 ring-1 ring-neutral-900/10 transition-transform duration-500 hover:rotate-0 hover:scale-[1.02] dark:ring-white/10"
                    >
                        <img
                            src="/images/login/hero-1.jpg"
                            alt=""
                            class="size-full object-cover"
                        />
                    </div>
                    <div class="grid gap-4 sm:gap-5">
                        <div
                            class="aspect-[4/3] rotate-[2deg] overflow-hidden rounded-2xl shadow-xl shadow-neutral-900/15 ring-1 ring-neutral-900/10 transition-transform duration-500 hover:rotate-0 hover:scale-[1.02] dark:ring-white/10"
                        >
                            <img
                                src="/images/login/tile-1.jpg"
                                alt=""
                                class="size-full object-cover"
                            />
                        </div>
                        <div
                            class="aspect-[4/3] rotate-[0.5deg] overflow-hidden rounded-2xl shadow-xl shadow-neutral-900/15 ring-1 ring-neutral-900/10 transition-transform duration-500 hover:rotate-0 hover:scale-[1.02] dark:ring-white/10"
                        >
                            <img
                                src="/images/login/tile-2.jpg"
                                alt=""
                                class="size-full object-cover"
                            />
                        </div>
                    </div>
                </div>

                <div
                    class="absolute -bottom-7 -left-4 rotate-[-5deg] overflow-hidden rounded-2xl shadow-2xl shadow-neutral-900/25 ring-1 ring-neutral-900/10 transition-transform duration-500 hover:rotate-0 sm:-left-8 dark:ring-white/10"
                >
                    <img
                        src="/images/login/tile-3.jpg"
                        alt=""
                        class="h-24 w-32 object-cover sm:h-28 sm:w-40"
                    />
                </div>

                <div
                    class="absolute -top-5 right-2 flex items-center gap-1.5 rounded-full bg-white/90 px-3.5 py-2 text-[11px] font-semibold tracking-wide text-neutral-600 shadow-lg shadow-neutral-900/10 ring-1 ring-neutral-900/5 backdrop-blur sm:right-6 dark:bg-neutral-900/90 dark:text-neutral-300 dark:ring-white/10"
                >
                    Only the people you trust.
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="mx-auto w-full max-w-6xl px-6 pb-10 md:px-8">
            <div
                class="flex flex-col items-center justify-between gap-2 border-t border-neutral-900/10 pt-6 text-xs text-neutral-400 sm:flex-row dark:border-white/10 dark:text-neutral-500"
            >
                <span>© {{ new Date().getFullYear() }} Keepsy</span>
                <span class="font-medium tracking-wide uppercase">
                    Your memories. Your people. Your space.
                </span>
            </div>
        </footer>
    </div>
</template>
