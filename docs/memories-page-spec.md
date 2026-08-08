# Spec: Memories Page — Frontend-First Implementation

## 1. Overview

Implement the Keepsy **Memories page** as an editorial, premium photo gallery per the design
reference in [`docs/memories-page.md`](./memories-page.md). This is a **frontend-first
iteration**: the gallery, photo viewer, upload flow (simulated), and interactions are built
against seeded sample data. Real uploads, storage, and persistence are deferred to a
backend iteration (see §10).

The page must feel like a private photography gallery — not a dashboard. Photos dominate;
the UI stays quiet.

## 2. Design Reference

The authoritative visual/UX spec is `docs/memories-page.md`. Key requirements it imposes:

- **Three-column editorial gallery** (desktop), two (tablet), one (mobile), with **varied
  aspect ratios and no equal-height rows** — dynamic, never identical tiles.
- **Tight, consistent gaps**: 16–20px (desktop), 12–16px (tablet), 8–12px (mobile).
- **Wide immersive container**: 32–48px side padding (desktop), 24px (tablet), 12–16px (mobile).
- **Quiet header** (existing Keepsy header), 64–72px desktop / 64px tablet / 60px mobile.
- **Subtle hover**: scale 1 → 1.015, 250–400ms, ease-out; optional metadata overlay.
- **Minimal overlay metadata**: title (e.g., "Singapore") + date (e.g., "July 18, 2026").
- **Hidden photo actions** until interaction: Favorite ♡, Share, More ⋯.
- **Upload via header `+`** (tooltip "Add memories") — no large upload banner above the gallery.
- **Minimal upload modal**: centered, spacious, rounded (16–20px), drag & drop + file picker.
- **Page-level drag & drop** with subtle gallery dimming + "Drop to add memories" overlay.
- **Upload preview** with real thumbnails, count, and destination ("Personal memories").
- **Upload progress** ("Uploading memories… 12 of 16") without interrupting the gallery.
- **New photos fade in** on completion: opacity 0→1, translateY(12px)→0, 400–600ms.
- **Staggered gallery load**: opacity 0→1, translateY(10px)→0, 300–500ms, 40ms stagger.
- **Infinite scrolling** (continuous append; no pagination controls).
- **Empty state**: "Your memories are waiting." + "Add memories" button.
- **Full-screen photo viewer**: dark `#111111`, smooth expansion (350–500ms, ease-out),
  Esc → close, ← / → prev/next, "Title · Month Year", "1 / 28".
- **Colors**: bg `#F8F7F5`, surface `#FFFFFF`, text `#252329` / `#4F4A51` / `#77727A` /
  `#A29DA4`, border `#E9E6E8`, brand `#6D5A72`. Neutral UI; photos provide the color.
- **Radius**: gallery 4–6px, modal 16–20px, buttons 8–10px. **No shadows on photos.**
- **Performance**: lazy loading, responsive sizes, proper dimensions, no full-res floods.
- **Accessibility**: meaningful alt text, keyboard nav, focus states, clear targets.

## 3. Decisions (from interview — do not revisit)

| Topic | Decision |
| --- | --- |
| Iteration scope | **Frontend-first**: gallery/viewer/upload UI against seeded sample data |
| Backend/upload | Deferred to a follow-up backend iteration (§10) |
| Albums | **Deferred** — flat photo list; single "Personal memories" bucket; hide the album selector in the upload preview |
| Photo metadata | **User-editable title** + date (taken_at). Titles displayed in overlay/viewer |
| File storage | **Local disk now, cloud later** — storage behind a swappable abstraction |
| Image processing | **spatie/laravel-medialibrary** (approved) — for the backend iteration's thumbnails/responsive sizes |
| HEIC | **Accepted, stored original-only** — no conversion this iteration; those photos show an unavailable preview until a converter exists |
| Design tokens | **Update global tokens** in `app.css` — new neutral palette applies app-wide |
| Interactions | **Favorite + rename work locally** (local state); delete removes from the view; share = disabled placeholder |
| Upload flow | **Fully simulated end-to-end** — modal, select/drop, previews, fake progress, fade-in to gallery (no server) |
| Photo viewer | **Custom Vue component** (transitions + keyboard); no library |
| Seed images | **Reuse the 4 existing `public/images/login/*.jpg`** photos, duplicated with varied titles/dates |
| Testing | **Pest-only** (existing suite stays green); no new JS test framework |

## 4. Current State

- `resources/js/pages/memories/Index.vue` — empty state with an "Upload photos" button that
  shows a "coming soon" toast.
- `resources/js/components/KeepsyHeader.vue` — has the `+` upload button
  (`handleUploadClick` → "Uploads are coming soon" toast) and matches the design's header
  layout (logo, Memories/Shared/People nav, `+`, bell, user menu).
- No Photo/Album models; no image-processing packages installed.
- Design tokens in `resources/css/app.css` currently use `#FDFDFC` bg / neutral-900 text;
  the plum brand (`hsl(288 12% 40%)` ≈ `#6D5A72`) already matches the new palette.
- `MemoriesController@index` renders `memories/Index` (bare, no props).

## 5. Data Contract (frontend `Photo` type)

Seed data and future API responses share this shape. Defined in `resources/js/types/memories.ts`:

```ts
type Photo = {
    id: string;            // stable local id (seed: slug; future: db id)
    title: string;         // user-editable ("Singapore")
    taken_at: string;      // ISO date — displayed as "July 18, 2026" / "July 2026"
    src: string;           // gallery/thumbnail URL
    full: string;          // viewer URL (same file this iteration)
    width: number;         // natural px — drives aspect-ratio + column balancing
    height: number;
    favorite: boolean;
    uploadable?: boolean;  // true for locally-simulated uploads (object URLs)
};
```

Seed dataset: `resources/js/lib/memorySeed.ts` — ~16 items derived from the 4 existing
`/images/login/hero-1.jpg`, `tile-1.jpg`, `tile-2.jpg`, `tile-3.jpg`, each with distinct
titles, dates, and varying width/height (mixed portrait/landscape) to exercise the
editorial rhythm. Re-exported so the mock "load more" generator can produce additional pages.

## 6. Component Architecture

Follow the design doc's structure (§35). The existing `KeepsyHeader.vue` **is** the
"AppHeader" — no new layout header is needed, but its dimensions/padding should be nudged
to the spec (desktop 64–72px; horizontal padding 32–48px desktop / 24px tablet / 16–20px mobile).

```
resources/js/
├── components/
│   └── memories/
│       ├── MemoriesGallery.vue        — 3-col (lg) / 2-col (md) / 1-col (base) editorial layout
│       ├── MemoriesColumn.vue         — independent vertical flow of MemoryPhoto items
│       ├── MemoryPhoto.vue            — image + hover overlay + actions trigger
│       ├── MemoryPhotoActions.vue     — ♡ favorite, share (disabled), ⋯ more menu (rename/delete)
│       ├── MemoryDropZone.vue         — page-level drag overlay ("Drop to add memories")
│       ├── MemoryUploadModal.vue      — Dialog wrapper; orchestrates preview → progress → done
│       ├── MemoryUploadPreview.vue    — thumbnails grid + count + destination label
│       ├── MemoryUploadProgress.vue   — progress bar + "N of M photos"
│       ├── MemoryEmptyState.vue       — "Your memories are waiting." + Add memories
│       ├── MemoryViewer.vue           — full-screen viewer (Teleport), keyboard nav
│       └── MemoryUploadButton.vue     — (optional) shared trigger used by header + empty state
├── composables/
│   └── useMemoryUpload.ts             — shared modal state (isOpen, open/close) via provide/inject
├── lib/
│   └── memorySeed.ts                  — seed dataset + mock pagination generator
├── types/
│   └── memories.ts                    — Photo type
└── pages/memories/Index.vue           — composition root (rewrite)
```

The page stays responsible for composition; gallery-specific behavior lives in the
`memories/` components.

## 7. Behavior Details

### 7.1 Gallery layout & column distribution

- Desktop: `grid grid-cols-3` with tight `gap-4`/`gap-5`; tablet `md:grid-cols-2`; mobile 1 col.
- **Dynamic column distribution** (design §37): assign photos to the shortest column by
  cumulative aspect-ratio height (greedy balancing) — never hardcoded. Column 1 and 3
  naturally begin with taller/portrait images; column 2 carries the shorter rhythm.
- Images render `width: 100%; height: auto` to preserve natural ratio (no cropping,
  `object-fit` only for controlled thumb containers).
- Container: full width with 32–48px (lg) / 24px (md) / 12–16px (base) horizontal padding.
- No page container wrappers beyond the layout's `<main>`.

### 7.2 Photo hover & overlay

- `group-hover:scale-[1.015]` on the image, 300ms `ease-out` (250–400ms band).
- On hover (desktop): bottom-gradient-free, minimal overlay with `title` (13px/500) and
  formatted `taken_at` date (12px/400), fading in via opacity. No buttons, no heavy gradients.

### 7.3 Photo actions (local-only this iteration)

- **♡ Favorite** — toggles `photo.favorite` in local reactive state (heart fills; subtle).
- **Share** — present but disabled (tooltip "Coming soon").
- **⋯ More** — dropdown (existing shadcn `DropdownMenu`): **Rename** (opens a small dialog
  or inline input; updates `photo.title` locally) and **Delete** (removes from the local
  array; optional lightweight confirm). No server persistence.

### 7.4 Staggered load

- On mount: fade-up stagger `opacity 0→1, translateY(10px)→0`, 300–500ms, per-photo delay
  `index * 40ms`. Keep subtle; don't animate every item dramatically.

### 7.5 Infinite scroll (mock)

- A `useInfiniteScroll`-style trigger (IntersectionObserver sentinel near the bottom)
  loads the next mock page from `memorySeed` (artificial ~500ms delay, spinner/skeleton),
  appending photos which then animate in. Backend iteration replaces this with real cursor
  pagination (§10).

### 7.6 Empty state

- If no photos: centered, minimal — "Your memories are waiting." / "Add your first photos
  and start building your private gallery." + `+ Add memories` button (opens the modal).
  Reuse the existing `KeepsyEmptyState` component with new copy, or the dedicated
  `MemoryEmptyState` per the design structure.

### 7.7 Upload (fully simulated)

Flow: click header `+` (or empty-state button) → modal opens → select/drop files →
validate (type jpeg/jpg/png/webp/heic, size, count cap ~20) → previews via
`URL.createObjectURL` → "Upload" → fake progress (n of m) → completion → new `Photo`
objects (title = filename minus extension, `taken_at = now`, `uploadable: true`) fade into
the gallery top → toast "N memories added" (vue-sonner). Destination label fixed to
"Personal memories" (album selector hidden — albums deferred).

- Header `+` currently toasts "coming soon"; it must now call `useMemoryUpload().open()`.
- **Page-level drag & drop**: when files are dragged over the page, dim the gallery
  slightly and show the "Drop to add memories" overlay; drop → opens modal with the files.
- No server call; no HEIC conversion (stored/original-only note applies to the future
  backend; in the simulation HEIC files pass client validation and preview as icons).

### 7.8 Photo viewer (custom)

- `MemoryViewer.vue` via `Teleport`; full-screen dark `#111111`.
- Opening: smooth expansion from the thumbnail (350–500ms, ease-out) — simplest robust
  approach is fade + scale from the clicked photo's position.
- Footer: `Title · Month Year` left, `n / total` right. Keyboard: `Esc` close, `←`/`→`
  navigate. Body scroll locked while open. Focus trapped; focus returns to the thumbnail.
- Clicking outside/close button closes.

### 7.9 Design tokens (global update)

Update `resources/css/app.css` token definitions (light mode) to the design palette and add
dark-mode equivalents (not specified in the design doc — map neutrals to the existing dark
values, e.g. bg ≈ `#15140f`, surface ≈ `#1e1c1a`, border ≈ `#2e2c30`; brand stays plum;
confirm exact dark values during implementation):

```
--background: #F8F7F5        (was #FDFDFC)
--foreground: #252329        (was neutral-900)
--card / --popover / --secondary / --muted / --accent: derived from surfaces #FFFFFF / #EFEDEA…
--muted-foreground: #77727A
--border / --input: #E9E6E8
--ring: #6D5A72 (unchanged brand)
```

Also nudge radius conventions (gallery 4–6px; modal 16–20px; buttons 8–10px) where they
diverge from current tokens. App-wide impact is intended (header, settings, auth).

## 8. Accessibility

- Every gallery image gets meaningful `alt` (derived from title; falls back to descriptive text).
- Interactive elements: visible focus states, `aria-label`s (favorite/share/more/viewer),
  ≥ 44px click targets where reasonable.
- Viewer: keyboard nav, `role="dialog"`/`aria-modal`, focus management, Esc close.

## 9. Performance

- `loading="lazy"` + explicit `width`/`height` on gallery images (prevents layout shift).
- Viewer loads the full-size file only when opened (single instance).
- Thumbnails/responsive sizes are a backend-iteration concern (medialibrary conversions).

## 10. Backend Iteration (growth path — NOT in this change)

Documented so the frontend contract (§5) already anticipates it:

- **`photos` table**: `id`, `user_id` (FK, cascade), `title` (nullable), `taken_at`
  (nullable datetime), `favorited_at` (nullable), `timestamps`.
- **Ownership/authorization**: photos belong to a user; a `PhotoPolicy` gates view/delete;
  users only see their own photos or photos explicitly shared with them.
- **Upload endpoint**: `POST /memories` — server-side validation (mimes:
  jpeg/jpg/png/webp/heic; max size; max count), MIME sniffing, dimension checks.
- **Storage**: originals on the **private** local disk; thumbnails on the public disk —
  behind a disk abstraction so S3 can be swapped in without refactoring call sites.
- **spatie/laravel-medialibrary** for conversions (thumb ~600px, gallery ~1400px) and
  responsive image variants.
- **HEIC**: stored original-only; no conversion this iteration (accept & flag as unviewable).
- **Infinite scroll**: real cursor pagination (`Inertia::merge()` + sentinel), ~24/page.
- **Delete**: `DELETE /memories/{photo}` with ownership policy; removes file + conversion set.

## 11. Non-Goals (this iteration)

- No real uploads, no file persistence, no image processing.
- No albums (deferred), no sharing, no favorites/sharing persistence.
- No changes to Shared/People pages beyond shared design tokens.
- No new dependencies (no JS libs; backend packages only when the backend iteration starts).

## 12. Files Touched (planned)

New:

- `resources/js/components/memories/MemoriesGallery.vue`
- `resources/js/components/memories/MemoriesColumn.vue`
- `resources/js/components/memories/MemoryPhoto.vue`
- `resources/js/components/memories/MemoryPhotoActions.vue`
- `resources/js/components/memories/MemoryDropZone.vue`
- `resources/js/components/memories/MemoryUploadModal.vue`
- `resources/js/components/memories/MemoryUploadPreview.vue`
- `resources/js/components/memories/MemoryUploadProgress.vue`
- `resources/js/components/memories/MemoryEmptyState.vue`
- `resources/js/components/memories/MemoryViewer.vue`
- `resources/js/composables/useMemoryUpload.ts`
- `resources/js/lib/memorySeed.ts`
- `resources/js/types/memories.ts`

Modified:

- `resources/js/pages/memories/Index.vue` (rewrite: composition root)
- `resources/js/components/KeepsyHeader.vue` (wire `+` to open the upload modal; padding/height nudge)
- `resources/css/app.css` (global design tokens)

## 13. Validation Checklist

- [ ] `npm run types:check` — no TS/Vue errors
- [ ] `npm run lint:check` — eslint clean
- [ ] `npm run build` — production build succeeds
- [ ] Existing Pest suite still green (`env -u SESSION_PATH -u APP_ENV php artisan test --compact` — see §14)
- [ ] Browser review: 3/2/1-column behavior, stagger, hover, viewer keyboard nav,
      simulated upload (modal → preview → progress → fade-in), page drag & drop,
      empty state, dark mode

## 14. Environment Note (pre-existing)

Tests on this machine must be run with the env-vars unset (Windows `SESSION_PATH` points at
the Git install dir and the shell exports `APP_ENV=local`):

```bash
env -u SESSION_PATH -u APP_ENV php artisan test --compact
```

## 15. Open Details to Confirm at Implementation

- Exact dark-mode token values for the new palette (§7.9).
- Title editing UX: inline input on the photo vs small dialog in the "⋯" menu (§7.3).
- Seed dataset composition: which of the 4 images get portrait vs landscape assignments so
  the editorial rhythm reads well on first load (§5).
- Viewer open animation fidelity (positional expansion vs simple fade+scale) (§7.8).
