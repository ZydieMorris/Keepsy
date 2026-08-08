<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import { LogOut, Settings, Sun } from '@lucide/vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { getInitials } from '@/composables/useInitials';
import { logout } from '@/routes';
import { edit as editAppearance } from '@/routes/appearance';
import { edit as editProfile } from '@/routes/profile';
import type { User } from '@/types';

defineProps<{
    user: User | null;
}>();

const handleLogout = () => {
    router.flushAll();
};
</script>

<template>
    <DropdownMenu v-if="user">
        <DropdownMenuTrigger as-child>
            <button
                type="button"
                class="size-9 rounded-full transition-opacity duration-200 hover:opacity-75 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/40 focus-visible:ring-offset-2 focus-visible:ring-offset-[#FDFDFC] dark:focus-visible:ring-offset-[#0a0a0a]"
                data-test="user-menu-button"
                aria-label="Open user menu"
            >
                <Avatar class="size-9">
                    <AvatarImage
                        v-if="user.avatar"
                        :src="user.avatar"
                        :alt="user.name"
                    />
                    <AvatarFallback
                        class="bg-neutral-200 text-xs font-semibold text-neutral-700 dark:bg-neutral-800 dark:text-neutral-200"
                    >
                        {{ getInitials(user.name) }}
                    </AvatarFallback>
                </Avatar>
            </button>
        </DropdownMenuTrigger>
        <DropdownMenuContent align="end" class="w-56">
            <DropdownMenuLabel class="p-0 font-normal">
                <div
                    class="flex flex-col px-1 py-1.5 text-left text-sm"
                    data-test="user-menu-label"
                >
                    <span class="font-medium text-foreground">{{ user.name }}</span>
                    <span class="text-xs text-muted-foreground">{{
                        user.email
                    }}</span>
                </div>
            </DropdownMenuLabel>
            <DropdownMenuSeparator />
            <DropdownMenuGroup>
                <DropdownMenuItem as-child>
                    <Link
                        :href="editProfile()"
                        prefetch
                        class="flex w-full cursor-pointer items-center"
                    >
                        <Settings class="mr-2 size-4" />
                        Profile settings
                    </Link>
                </DropdownMenuItem>
                <DropdownMenuItem as-child>
                    <Link
                        :href="editAppearance()"
                        prefetch
                        class="flex w-full cursor-pointer items-center"
                    >
                        <Sun class="mr-2 size-4" />
                        Appearance
                    </Link>
                </DropdownMenuItem>
            </DropdownMenuGroup>
            <DropdownMenuSeparator />
            <DropdownMenuItem as-child>
                <Link
                    :href="logout()"
                    as="button"
                    class="flex w-full cursor-pointer items-center"
                    data-test="logout-button"
                    @click="handleLogout"
                >
                    <LogOut class="mr-2 size-4" />
                    Log out
                </Link>
            </DropdownMenuItem>
        </DropdownMenuContent>
    </DropdownMenu>
</template>
