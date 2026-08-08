import { createInertiaApp } from '@inertiajs/vue3';
import { initializeTheme } from '@/composables/useAppearance';
import AuthGalleryLayout from '@/layouts/auth/AuthGalleryLayout.vue';
import AuthLayout from '@/layouts/AuthLayout.vue';
import KeepsyLayout from '@/layouts/KeepsyLayout.vue';
import { initializeFlashToast } from '@/lib/flashToast';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    layout: (name) => {
        switch (true) {
            case name === 'landing/Index':
                return null;
            case name === 'auth/Login' || name === 'auth/Register':
                return AuthGalleryLayout;
            case name.startsWith('auth/'):
                return AuthLayout;
            default:
                return KeepsyLayout;
        }
    },
    progress: {
        color: '#6D5A72',
    },
});

// This will set light / dark mode on page load...
initializeTheme();

// This will listen for flash toast data from the server...
initializeFlashToast();
