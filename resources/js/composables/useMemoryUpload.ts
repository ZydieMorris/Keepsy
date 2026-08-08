import { router } from '@inertiajs/vue3';
import { reactive } from 'vue';
import { memories } from '@/routes';

export type UploadPhase = 'pick' | 'preview' | 'uploading';

type UploadState = {
    isOpen: boolean;
    phase: UploadPhase;
    files: File[];
    progress: number;
};

const state = reactive<UploadState>({
    isOpen: false,
    phase: 'pick',
    files: [],
    progress: 0,
});

let uploadedHandler: (() => void) | null = null;

/**
 * Shared, app-wide upload state. The header's "+" button calls `open()`, the
 * modal (mounted once in the layout) renders from `state`, and `upload()`
 * streams the files to the backend. The Memories page registers an `onUploaded`
 * handler so its gallery refreshes once the upload completes.
 */
export const useMemoryUpload = () => {
    const open = (files: File[] = []): void => {
        state.files = files;
        state.phase = files.length > 0 ? 'preview' : 'pick';
        state.progress = 0;
        state.isOpen = true;
    };

    const close = (): void => {
        state.isOpen = false;
        state.files = [];
        state.progress = 0;
    };

    const setPhase = (phase: UploadPhase): void => {
        state.phase = phase;
    };

    const setProgress = (progress: number): void => {
        state.progress = progress;
    };

    /**
     * Upload the selected files to the backend, reporting progress along the way.
     */
    const upload = (files: File[]): Promise<void> => {
        const form = new FormData();
        files.forEach((file) => form.append('images[]', file));

        setPhase('uploading');
        setProgress(0);

        return new Promise((resolve, reject) => {
            router.post(memories().url, form, {
                preserveScroll: true,
                onProgress: (event) => {
                    setProgress(Math.round(event?.percentage ?? 0));
                },
                onSuccess: () => resolve(),
                onError: () => reject(new Error('Upload failed')),
            });
        });
    };

    const onUploaded = (handler: () => void): void => {
        uploadedHandler = handler;
    };

    const emitUploaded = (): void => {
        uploadedHandler?.();
    };

    return {
        state,
        open,
        close,
        setPhase,
        setProgress,
        upload,
        onUploaded,
        emitUploaded,
    };
};
