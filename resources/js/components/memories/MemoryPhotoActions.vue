<script setup lang="ts">
import { Heart, MoreHorizontal, Pencil, Share2, Trash2 } from '@lucide/vue';
import { ref } from 'vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import type { Photo } from '@/types';

const props = defineProps<{
    photo: Photo;
}>();

const emit = defineEmits<{
    favorite: [];
    rename: [title: string];
    delete: [];
}>();

const renameOpen = ref(false);
const draftTitle = ref(props.photo.title);

const openRename = (): void => {
    draftTitle.value = props.photo.title;
    renameOpen.value = true;
};

const confirmRename = (): void => {
    const title = draftTitle.value.trim();

    if (title.length === 0) {
        return;
    }

    emit('rename', title);
    renameOpen.value = false;
};
</script>

<template>
    <div class="flex items-center gap-1.5">
        <button
            type="button"
            class="flex size-8 items-center justify-center rounded-full text-white transition-colors duration-200 hover:bg-white/15 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white/60"
            :aria-pressed="photo.favorite"
            aria-label="Toggle favorite"
            @click.stop="emit('favorite')"
        >
            <Heart
                class="size-4"
                :class="photo.favorite ? 'fill-white text-white' : 'text-white/90'"
            />
        </button>

        <DropdownMenu>
            <DropdownMenuTrigger as-child>
                <button
                    type="button"
                    class="flex size-8 items-center justify-center rounded-full text-white transition-colors duration-200 hover:bg-white/15 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white/60"
                    aria-label="Photo actions"
                    @click.stop
                >
                    <MoreHorizontal class="size-4" />
                </button>
            </DropdownMenuTrigger>
            <DropdownMenuContent align="end" class="w-44">
                <DropdownMenuItem disabled class="cursor-not-allowed">
                    <Share2 class="mr-2 size-4" />
                    Share
                </DropdownMenuItem>
                <DropdownMenuItem @click.stop="openRename">
                    <Pencil class="mr-2 size-4" />
                    Rename
                </DropdownMenuItem>
                <DropdownMenuSeparator />
                <DropdownMenuItem
                    class="text-destructive focus:text-destructive"
                    @click.stop="emit('delete')"
                >
                    <Trash2 class="mr-2 size-4" />
                    Delete
                </DropdownMenuItem>
            </DropdownMenuContent>
        </DropdownMenu>

        <Dialog :open="renameOpen" @update:open="renameOpen = $event">
            <DialogContent class="sm:max-w-sm">
                <DialogHeader>
                    <DialogTitle>Rename memory</DialogTitle>
                </DialogHeader>
                <div class="grid gap-2">
                    <Label for="memory-title">Title</Label>
                    <Input
                        id="memory-title"
                        v-model="draftTitle"
                        name="title"
                        class="mt-1"
                        @keydown.enter.prevent="confirmRename"
                    />
                </div>
                <DialogFooter>
                    <Button
                        type="button"
                        variant="outline"
                        @click="renameOpen = false"
                    >
                        Cancel
                    </Button>
                    <Button
                        type="button"
                        :disabled="draftTitle.trim().length === 0"
                        @click="confirmRename"
                    >
                        Save
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </div>
</template>
