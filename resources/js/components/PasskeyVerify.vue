<script setup lang="ts">
import type { UrlMethodPair } from '@inertiajs/core';
import { router } from '@inertiajs/vue3';
import { usePasskeyVerify } from '@laravel/passkeys/vue';
import { KeyRound } from '@lucide/vue';
import { computed } from 'vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import { Spinner } from '@/components/ui/spinner';
import { memories } from '@/routes';

type Props = {
    routes?: {
        options: UrlMethodPair;
        submit: UrlMethodPair;
    };
    label?: string;
    loadingLabel?: string;
    separator?: string;
    separatorPosition?: 'before' | 'after';
    tabindex?: number;
};

const props = withDefaults(defineProps<Props>(), {
    separatorPosition: 'after',
});

const separatorLabel = computed(() => {
    if (props.separator) {
        return props.separator;
    }

    return props.separatorPosition === 'before'
        ? 'Or sign in with a passkey'
        : 'Or continue with email';
});

const { verify, isLoading, error, isSupported } = usePasskeyVerify({
    ...(props.routes
        ? {
              routes: {
                  options: props.routes.options.url,
                  submit: props.routes.submit.url,
              },
          }
        : {}),
    onSuccess: (response) => {
        router.visit(response.redirect ?? memories());
    },
});
</script>

<template>
    <div v-if="isSupported" class="grid gap-2">
        <div
            v-if="separatorPosition === 'before'"
            class="relative mt-4"
        >
            <div class="absolute inset-0 flex items-center">
                <Separator class="w-full" />
            </div>
            <div class="relative flex justify-center text-xs uppercase">
                <span class="bg-background px-2 text-muted-foreground">
                    {{ separatorLabel }}
                </span>
            </div>
        </div>

        <Button
            type="button"
            variant="outline"
            class="w-full"
            @click="verify"
            :disabled="isLoading"
            :tabindex="tabindex"
        >
            <Spinner v-if="isLoading" />
            <KeyRound v-else class="h-4 w-4" />
            {{
                isLoading
                    ? (props.loadingLabel ?? 'Authenticating...')
                    : (props.label ?? 'Sign in with a passkey')
            }}
        </Button>

        <div v-if="error" class="text-center">
            <InputError :message="error" />
        </div>

        <div v-if="separatorPosition === 'after'" class="relative my-6">
            <div class="absolute inset-0 flex items-center">
                <Separator class="w-full" />
            </div>
            <div class="relative flex justify-center text-xs uppercase">
                <span class="bg-background px-2 text-muted-foreground">
                    {{ separatorLabel }}
                </span>
            </div>
        </div>
    </div>
</template>
