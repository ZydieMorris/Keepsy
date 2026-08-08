<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { Bell, Menu, Plus } from '@lucide/vue';
import { computed } from 'vue';
import { toast } from 'vue-sonner';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import KeepsyUserMenu from '@/components/KeepsyUserMenu.vue';
import { Button } from '@/components/ui/button';
import {
    Sheet,
    SheetContent,
    SheetHeader,
    SheetTitle,
    SheetTrigger,
} from '@/components/ui/sheet';
import {
    Tooltip,
    TooltipContent,
    TooltipProvider,
    TooltipTrigger,
} from '@/components/ui/tooltip';
import { useCurrentUrl } from '@/composables/useCurrentUrl';
import { memories, people, shared } from '@/routes';
import type { NavItem } from '@/types';

const navItems: NavItem[] = [
    { title: 'Memories', href: memories() },
    { title: 'Shared', href: shared() },
    { title: 'People', href: people() },
];

const { isCurrentOrParentUrl } = useCurrentUrl();

const page = usePage();
const user = computed(() => page.props.auth.user);

const handleUploadClick = () => {
    toast('Uploads are coming soon');
};

const handleInvitationsClick = () => {
    toast('No new invitations');
};
</script>

<template>
    <header
        class="sticky top-0 z-40 border-b border-neutral-900/[0.06] bg-[#FDFDFC]/95 backdrop-blur-sm transition-colors duration-300 dark:border-white/[0.06] dark:bg-[#0a0a0a]/95"
    >
        <div
            class="mx-auto grid h-[60px] w-full max-w-[1600px] grid-cols-[1fr_auto_1fr] items-center gap-4 px-5 md:h-16 md:px-6 lg:px-10"
        >
            <!-- Wordmark -->
            <Link :href="memories()" class="group justify-self-start">
                <span
                    class="flex items-center gap-2.5 transition-opacity duration-300 group-hover:opacity-75"
                >
                    <span
                        class="flex size-8 items-center justify-center rounded-lg bg-primary text-primary-foreground shadow-sm transition-transform duration-300 group-hover:scale-105"
                    >
                        <AppLogoIcon class="size-4 fill-current" />
                    </span>
                    <span
                        class="font-sans text-[19px] font-bold tracking-tight text-neutral-900 dark:text-neutral-50"
                    >
                        Keepsy
                    </span>
                </span>
            </Link>

            <!-- Desktop navigation -->
            <nav
                class="hidden items-center justify-center gap-10 lg:flex"
                aria-label="Primary"
            >
                <Link
                    v-for="item in navItems"
                    :key="item.title"
                    :href="item.href"
                    prefetch
                    class="group relative py-1 text-[13px] font-medium tracking-[0.08em] text-neutral-500 uppercase transition-colors duration-300 hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-neutral-100"
                    :class="{
                        'text-neutral-900 dark:text-neutral-100':
                            isCurrentOrParentUrl(item.href),
                    }"
                >
                    {{ item.title }}
                    <span
                        aria-hidden="true"
                        class="absolute inset-x-0 -bottom-[3px] h-px origin-center scale-x-0 bg-neutral-900 transition-transform duration-300 ease-out group-hover:scale-x-100 dark:bg-white"
                        :class="{
                            'scale-x-100': isCurrentOrParentUrl(item.href),
                        }"
                    ></span>
                </Link>
            </nav>

            <!-- Actions -->
            <div class="flex items-center justify-self-end gap-1 md:gap-1.5">
                <!-- Mobile navigation -->
                <Sheet>
                    <SheetTrigger as-child>
                        <Button
                            variant="ghost"
                            size="icon"
                            class="size-9 rounded-full text-neutral-600 hover:bg-transparent hover:text-neutral-900 dark:text-neutral-300 dark:hover:text-neutral-100 lg:hidden"
                            aria-label="Open navigation menu"
                        >
                            <Menu class="size-5" stroke-width="1.75" />
                        </Button>
                    </SheetTrigger>
                    <SheetContent side="left" class="w-72">
                        <SheetHeader>
                            <SheetTitle
                                class="flex items-center gap-2.5 text-left font-sans text-xl font-bold tracking-tight"
                            >
                                <span
                                    class="flex size-7 items-center justify-center rounded-lg bg-primary text-primary-foreground"
                                >
                                    <AppLogoIcon class="size-4 fill-current" />
                                </span>
                                Keepsy
                            </SheetTitle>
                        </SheetHeader>
                        <nav class="mt-8 flex flex-col gap-1" aria-label="Mobile">
                            <Link
                                v-for="item in navItems"
                                :key="item.title"
                                :href="item.href"
                                class="rounded-md px-3 py-2.5 text-sm font-medium text-neutral-600 transition-colors duration-200 hover:bg-neutral-100 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-900 dark:hover:text-neutral-100"
                                :class="{
                                    'bg-neutral-100 text-neutral-900 dark:bg-neutral-900 dark:text-neutral-100':
                                        isCurrentOrParentUrl(item.href),
                                }"
                            >
                                {{ item.title }}
                            </Link>
                        </nav>
                    </SheetContent>
                </Sheet>

                <!-- Upload -->
                <TooltipProvider :delay-duration="200">
                    <Tooltip>
                        <TooltipTrigger as-child>
                            <Button
                                variant="ghost"
                                size="icon"
                                class="size-9 rounded-full text-neutral-600 transition-transform duration-200 hover:scale-[1.03] hover:bg-transparent hover:text-neutral-900 dark:text-neutral-300 dark:hover:text-neutral-100"
                                aria-label="Upload photos"
                                data-test="upload-button"
                                @click="handleUploadClick"
                            >
                                <Plus class="size-5" stroke-width="1.75" />
                            </Button>
                        </TooltipTrigger>
                        <TooltipContent side="bottom">Upload</TooltipContent>
                    </Tooltip>
                </TooltipProvider>

                <!-- Invitations -->
                <TooltipProvider :delay-duration="200">
                    <Tooltip>
                        <TooltipTrigger as-child>
                            <Button
                                variant="ghost"
                                size="icon"
                                class="hidden size-9 rounded-full text-neutral-600 transition-colors duration-200 hover:bg-transparent hover:text-neutral-900 md:inline-flex dark:text-neutral-300 dark:hover:text-neutral-100"
                                aria-label="Invitations"
                                @click="handleInvitationsClick"
                            >
                                <Bell class="size-[18px]" stroke-width="1.75" />
                            </Button>
                        </TooltipTrigger>
                        <TooltipContent side="bottom">Invitations</TooltipContent>
                    </Tooltip>
                </TooltipProvider>

                <!-- User -->
                <KeepsyUserMenu :user="user" />
            </div>
        </div>
    </header>
</template>
