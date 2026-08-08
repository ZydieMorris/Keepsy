<script setup lang="ts">
import { ImagePlus, Upload } from '@lucide/vue';
import { computed, ref, useTemplateRef, watch } from 'vue';
import { toast } from 'vue-sonner';
import MemoryUploadPreview from '@/components/memories/MemoryUploadPreview.vue';
import type { PreviewItem } from '@/components/memories/MemoryUploadPreview.vue';
import MemoryUploadProgress from '@/components/memories/MemoryUploadProgress.vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { useMemoryUpload } from '@/composables/useMemoryUpload';

const ALLOWED_TYPES = [
    'image/jpeg',
    'image/png',
    'image/webp',
    'image/heic',
    'image/heif',
];

const ALLOWED_LABEL = 'JPG · PNG · WEBP · HEIC';
const MAX_FILES = 20;
const MAX_BYTES = 25 * 1024 * 1024;

const { state, close, setPhase, upload, emitUploaded } = useMemoryUpload();

const fileInput = useTemplateRef('fileInput');
const previewItems = ref<PreviewItem[]>([]);
const uploading = ref(false);

const total = computed(() => previewItems.value.length);

const loadDimensions = (url: string): Promise<{ width: number; height: number }> =>
    new Promise((resolve) => {
        const image = new Image();

        image.onload = () =>
            resolve({ width: image.naturalWidth, height: image.naturalHeight });

        image.onerror = () => resolve({ width: 800, height: 600 });

        image.src = url;
    });

const buildPreviewItems = async (files: File[]): Promise<void> => {
    const items = await Promise.all(
        files.map(async (file) => {
            const url = URL.createObjectURL(file);
            const { width, height } = await loadDimensions(url);

            // Browsers can't decode HEIC — show an icon tile instead.
            const loaded = !file.type.includes('heic') && !file.type.includes('heif');

            return { file, url, width, height, loaded };
        }),
    );

    previewItems.value = items;
};

const revokePreviewUrls = (): void => {
    previewItems.value.forEach((item) => URL.revokeObjectURL(item.url));
    previewItems.value = [];
};

const validateFiles = (files: File[]): File[] => {
    const valid = files
        .filter((file) => {
            const typeOk = ALLOWED_TYPES.includes(file.type);

            return typeOk && file.size <= MAX_BYTES;
        })
        .slice(0, MAX_FILES);

    const rejected = files.length - valid.length;

    if (rejected > 0) {
        toast.error(
            `${rejected} ${rejected === 1 ? 'file was' : 'files were'} skipped (${ALLOWED_LABEL}, max 25MB each)`,
        );
    }

    return valid;
};

const openWithFiles = (files: File[]): void => {
    const valid = validateFiles(files);

    if (valid.length === 0) {
        return;
    }

    uploading.value = false;
    void buildPreviewItems(valid);
    setPhase('preview');
};

const onFilesChosen = (event: Event): void => {
    const files = Array.from((event.target as HTMLInputElement).files ?? []);

    openWithFiles(files);

    // Allow re-selecting the same files.
    (event.target as HTMLInputElement).value = '';
};

const handleUpload = async (): Promise<void> => {
    if (total.value === 0 || uploading.value) {
        return;
    }

    uploading.value = true;

    try {
        await upload(previewItems.value.map((item) => item.file));

        toast.success(
            total.value === 1 ? '1 memory added' : `${total.value} memories added`,
        );

        close();
        emitUploaded();
    } catch {
        setPhase('preview');
        toast.error('Upload failed. Please try again.');
    } finally {
        uploading.value = false;
    }
};

watch(
    () => state.isOpen,
    (isOpen) => {
        if (isOpen) {
            uploading.value = false;

            if (state.files.length > 0) {
                openWithFiles(state.files);
            }
        } else {
            revokePreviewUrls();
        }
    },
);
</script>

<template>
    <Dialog :open="state.isOpen" @update:open="(value) => !value && close()">
        <DialogContent class="sm:max-w-lg">
            <template v-if="state.phase === 'pick'">
                <DialogHeader>
                    <DialogTitle></DialogTitle>
                    <DialogDescription>

                    </DialogDescription>
                </DialogHeader>

                <div
                    class="grid cursor-pointer place-items-center gap-3 rounded-2xl border-2 border-dashed border-border bg-muted/40 px-6 py-12 text-center transition-colors duration-200 hover:border-primary/40 hover:bg-muted/60"
                    role="button"
                    tabindex="0"
                    aria-label="Choose photos to upload"
                    @click="fileInput?.click()"
                    @keydown.enter="fileInput?.click()"
                    @dragover.prevent
                    @drop.prevent="(event) => openWithFiles(Array.from((event as DragEvent).dataTransfer?.files ?? []))"
                >
                    <div
                        class="flex size-12 items-center justify-center rounded-full bg-primary/10 text-primary"
                    >
                        <ImagePlus class="size-6" :stroke-width="1.5" />
                    </div>
                    <p class="text-sm font-medium text-foreground">
                        Drop photos here or choose from your device
                    </p>
                    <p class="text-xs text-muted-foreground">
                        {{ ALLOWED_LABEL }} · up to {{ MAX_FILES }} photos · 25MB each
                    </p>
                </div>

                <DialogFooter class="sm:justify-center">
                    <Button class="w-full sm:w-auto" @click="fileInput?.click()">
                        <Upload class="size-4" />
                        Choose photos
                    </Button>
                </DialogFooter> 
            </template>

            <template v-else-if="state.phase === 'preview'">
                <DialogHeader>
                    <DialogTitle>Add memories</DialogTitle>
                </DialogHeader>

                <MemoryUploadPreview :items="previewItems" />

                <DialogFooter class="sm:justify-between">
                    <Button
                        type="button"
                        variant="outline"
                        :disabled="uploading"
                        @click="close"
                    >
                        Cancel
                    </Button>
                    <Button type="button" :disabled="uploading" @click="handleUpload">
                        <Upload class="size-4" />
                        Upload
                    </Button>
                </DialogFooter>
            </template>

            <template v-else>
                <DialogHeader>
                    <DialogTitle>Add memories</DialogTitle>
                </DialogHeader>

                <MemoryUploadProgress :total="total" :progress="state.progress" />
            </template>
        </DialogContent>

        <input
            ref="fileInput"
            type="file"
            multiple
            accept=".jpg,.jpeg,.png,.webp,.heic,.heif"
            class="hidden"
            @change="onFilesChosen"
        />
    </Dialog>
</template>
