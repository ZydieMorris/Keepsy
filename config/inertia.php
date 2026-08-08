<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Server Side Rendering
    |--------------------------------------------------------------------------
    |
    | Server-side rendering is disabled for this application. The memories
    | gallery is a viewport-dependent client-side layout (its column count is
    | resolved from `window.innerWidth`), so an SSR pre-render (which has no
    | `window`) can only match it at the cost of a viewport-width handshake
    | into the SSR server — a complexity that was deliberately not pursued.
    | SSR was producing hydration mismatches on refresh and the "images
    | enlarge" regression (SSR HTML rendered a single column that Vue could
    | not fully patch; SPA navigation, which renders client-side, looked
    | correct).
    |
    | Trade-off accepted: with SSR off, first paint is JS-dependent (the mount
    | div is blank until the bundle boots) instead of pre-rendered HTML. For a
    | session-authenticated app with no SEO benefit, that cost is worth it.
    |
    | NOTE: if SSR is ever re-enabled (`enabled => true`), it also requires an
    | SSR server running (`php artisan inertia:start-ssr`) AND the gallery's
    | initial render must be made SSR-consistent — otherwise the mismatches
    | above return.
    |
    */

    'ssr' => [
        'enabled' => false,
        'url' => 'http://127.0.0.1:13714',
        // 'bundle' => base_path('bootstrap/ssr/ssr.mjs'),

    ],

    /*
    |--------------------------------------------------------------------------
    | Pages
    |--------------------------------------------------------------------------
    |
    | These options configure how Inertia discovers page components on the
    | filesystem. The paths and extensions are used to locate components
    | when rendering responses and during testing assertions.
    |
    */

    'pages' => [

        'paths' => [
            resource_path('js/pages'),
        ],

        'extensions' => [
            'js',
            'jsx',
            'svelte',
            'ts',
            'tsx',
            'vue',
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Testing
    |--------------------------------------------------------------------------
    |
    | The values described here are used to locate Inertia components on the
    | filesystem. For instance, when using `assertInertia`, the assertion
    | attempts to locate the component as a file relative to the paths.
    |
    */

    'testing' => [

        'ensure_pages_exist' => true,

    ],

];
