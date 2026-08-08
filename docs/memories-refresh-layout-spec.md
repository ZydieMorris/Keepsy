# Spec: Memories Gallery — Refresh Layout Regression

## 1. Overview

On the Memories page (`resources/js/pages/memories/Index.vue`), **refreshing the page causes the
gallery images to render enlarged — the gallery collapses to a single full-width column** instead
of the designed three-column editorial masonry. Navigating to another page and back (SPA
navigation) restores the correct layout.

This spec documents the symptom (verified by interview), the diagnostic findings already gathered,
the agreed requirements, and the acceptance criteria. **No code changes are to be made from this
spec alone** — the implementation step follows after the root cause is confirmed (see §8).

## 2. Symptom (as reported and clarified)

| Question | Answer (from interview) |
| --- | --- |
| Which images enlarge? | **All** photos in the gallery |
| How large? | **Full-width** — the gallery looks like it collapsed to **one column** |
| Every refresh? | **Yes, every refresh** (consistent) |
| Hard refresh (Ctrl/Cmd+Shift+R)? | **Also reproduces** — rules out browser cache |
| Self-corrects? | **No** — stays broken until the user navigates away and back |
| Browser | **Zen** (Firefox-based) — other browsers not yet tested |
| Dev or production build? | **Both** — reproduces with `npm run dev` running AND with the production build |
| Rest of the app? | **Looks normal** — header, nav, buttons are styled correctly; only the photo layout is wrong |
| Scope | **Memories page only** (Shared/People are still empty states) |
| Photo count | Happens with **any** number of photos (even a couple) |
| SPA navigation (away & back) | Layout is **normal** — this is the reference behaviour |

## 3. Reference Behaviour (the "final composition")

The correct layout is defined in `docs/memories-page.md` (§46 "Final Desktop Composition") and
`docs/memories-page-spec.md`:

- **Desktop (≥1024px): 3 columns**; tablet (768–1023px): 2 columns; mobile (<768px): 1 column.
- **No equal-height rows** — independent vertical flow per column so images keep varied aspect ratios.
- Images render `width: 100%; height: auto` (natural ratio, no cropping).
- Greedy shortest-column distribution (photos assigned to the column with the smallest cumulative
  aspect-ratio height).
- Staggered entrance animation: `opacity 0→1, translateY(10px)→0`, 300–500ms, 40ms stagger.
- Tight gaps (16–20px desktop), generous side padding (32–48px desktop).

**The goal is: a page refresh must render this exact composition — identical to what SPA
navigation produces.**

## 4. Current Implementation (relevant code)

- `resources/js/pages/memories/Index.vue` — composition root; passes `photos` + `next_cursor` to
  `MemoriesGallery`; registers an `onUploaded` refresh.
- `resources/js/components/memories/MemoriesGallery.vue` — the layout driver:
  - `columnCount` from VueUse `useMediaQuery('(min-width: 1024px)')` → 3, `(min-width: 768px)` → 2, else 1.
  - Grid container: `class="grid gap-3 md:gap-4 lg:gap-5"` with **inline**
    `:style="{ gridTemplateColumns: \`repeat(${columnCount}, minmax(0, 1fr))\` }"`.
  - Column balancing: greedy over `photo.height / photo.width`.
  - Infinite scroll sentinel (IntersectionObserver) → `router.get(..., { only: ['photos','next_cursor'] })`.
- `resources/js/components/memories/MemoriesColumn.vue` — `flex flex-col gap-*` of `MemoryPhoto`.
- `resources/js/components/memories/MemoryPhoto.vue` — the tile:
  ```html
  <figure class="group relative block w-full overflow-hidden rounded-[6px] motion-safe:animate-memory-enter"
          :style="{ animationDelay: `${delay}ms` }">
      <button type="button" class="block w-full ...">
          <img v-if="!photo.unviewable"
               :src="photo.src"
               :width="photo.width"
               :height="photo.height"
               loading="lazy"
               class="block h-auto w-full transition-transform ... group-hover:scale-[1.015]" />
          ...
  ```
- The entrance animation `memory-enter` uses `animation-fill-mode: both` (defined in
  `resources/css/app.css` under `@theme --animate-memory-enter`), so during each tile's delay the
  element holds the `from` state (`opacity: 0; transform: translateY(10px)`).

## 5. Diagnostic Findings Already Verified (do not re-verify)

These were checked during investigation (2026-08-09) and are **not** the cause:

- **Served CSS is complete** (both dev and production build): `.grid{display:grid}`,
  `.w-full{width:100%}`, `.h-auto`, `grid-cols-*`, `minmax(0,1fr)`, `aspect-ratio:3/4`,
  `animate-memory-enter` are all present. → Not missing/stale CSS.
- **Backend data is correct**: photos carry real `width`/`height` (e.g. 3840×2160), `src` points to
  generated thumbnails that serve `200`, `public/storage` symlink exists. → Not a data problem.
- **`useMediaQuery` initialises synchronously** in the installed VueUse (12.8.2) — first effect run
  evaluates `window.matchMedia(...).matches` (via an `ssrWidth` fallback path) before returning a
  computed ref. → The media query *should* yield 3 columns on desktop cold load (see §6 H1 for the
  remaining uncertainty).
- **Server/uploads**: the PHP upload pipeline is fixed (temp dir + 25MB limits) and the fresh
  `php artisan serve` (port 8000) accepts uploads; the frontend was rebuilt (`npm run build`).
- **Hard refresh reproduces** → not a browser-cache issue.
- **Production build also reproduces** → not a Vite-dev-server-only quirk.

## 6. Candidate Root Causes (to confirm during implementation)

Ordered by likelihood. The implementer should confirm the cause with a browser repro before
committing to a fix (see §8).

- **H1 — `columnCount` resolves to 1 on cold mount.** In VueUse 12.8.2,
  `useMediaQuery(query)` computes its first value via the `useSSRWidth()` fallback
  (`ssrWidth = defaultWindow?.innerWidth` at setup) before switching to `matchMedia` change events.
  If, on a cold full page load, that value is missing/0 (module evaluation timing, Zen/Firefox
  behaviour), `columnCount` becomes 1 and **stays** 1 (no viewport change fires a media-query
  change to correct it) → one column of full-width images, persistent until the component remounts
  (which is exactly what SPA navigation does). This is the only mechanism found that explains a
  *persistent* single-column state.
- **H2 — Images render at natural size on first paint.** On cold load, Firefox/Zen may paint the
  lazy `<img>` at its natural size (3840px) before the stylesheet’s `w-full h-auto` constrains it.
  If combined with the `memory-enter` animation (fill-mode `both` + stagger delay) this could
  produce a stretched/full-width appearance that persists until re-render. Weaker fit: the rest of
  the app is styled, and CSS was verified present.
- **H3 — Stale in-memory module/asset state on cold load.** Possible in dev-mode HMR edge cases;
  ruled out for production build, so treat as low priority.

## 7. Requirements (agreed with the user)

- **R1 — Refresh parity.** A normal or hard refresh of `/memories` must render the gallery
  **identically to SPA navigation**: 3 columns (≥1024px), 2 (768–1023px), 1 (<768px); greedy
  shortest-column masonry; images at natural aspect ratio (`width:100%; height:auto`), no cropping.
- **R2 — Stable layout from first paint.** The gallery must never render as full-width/enlarged at
  any point during load. **Reserve each tile’s aspect-ratio space** (subtle placeholder acceptable
  and approved) so images cannot blow out the layout while loading.
- **R3 — Keep the entrance animation.** The staggered `memory-enter` animation (40ms stagger,
  cap 400ms) must be preserved.
- **R4 — Scope: Memories page only.** Shared/People remain untouched (still empty states). If the
  same gallery pattern is later reused, the fix should be portable to it.
- **R5 — Works in all modes.** Must render correctly with the Vite dev server running **and** with
  the production build, across hard and normal refreshes.
- **R6 — Performance.** Keep `loading="lazy"`, thumbnails, and cursor pagination. No full-resolution
  floods (per design `docs/memories-page.md` §33).
- **R7 — No design change.** The editorial composition (varied ratios, no equal-height rows) is the
  goal, not uniform tiles.

## 8. Implementation Plan (for the implementation step)

1. **Confirm the root cause in a browser.** Add a temporary diagnostic (or use devtools) to log
   `columnCount` / `isDesktop` on cold load for `/memories`. If `columnCount` is 1 on first render:
   **H1 confirmed.**
   - If H1: make the breakpoint resolution explicit and deterministic — e.g. compute
     `columnCount` from `window.innerWidth` at mount with an explicit correct initial value (or
     replace the VueUse `useMediaQuery` usage with a small composable that initialises from
     `matchMedia` and only reacts to `change` events). Ensure the initial value is correct on cold
     load.
   - If H2: ensure tiles constrain the `<img>` via CSS `aspect-ratio: w/h` (from
     `photo.width`/`photo.height`) on the image or its wrapper, in addition to `w-full h-auto`, so
     the box is correct before/without the image decoding; confirm no interaction with the
     animation’s `fill-mode: both`/delay.
2. **Reserve aspect-ratio space (R2).** Add `aspect-ratio` to the tile box (subtle background
   placeholder; invisible or near-invisible per the approved "subtle placeholder" option). Verify
   the reserved box matches the thumb conversion ratio.
3. **Preserve the animation (R3)** — only change it if the confirmed root cause involves it.
4. **Verify** against §9.

## 9. Acceptance Criteria

- [ ] Hard refresh (Ctrl/Cmd+Shift+R) of `/memories` renders the **3-column** editorial masonry on
      desktop — pixel-identical in structure to SPA navigation.
- [ ] Tablet (768–1023px) renders 2 columns after refresh; mobile (<768px) renders 1 column.
- [ ] No full-width/enlarged images at any point during the load (no visible flash of the broken
      single-column state).
- [ ] Tile aspect ratios are preserved from first paint; images fill their reserved boxes.
- [ ] The staggered entrance animation still plays.
- [ ] Reproduces correctly in **both** dev mode (`npm run dev`) and production build.
- [ ] Uploading new photos and infinite scroll (load more) still work.
- [ ] `npm run types:check`, `npm run lint:check`, and `npm run build` pass.
- [ ] Existing Pest suite stays green (see environment note below).

## 10. Validation Plan (manual, per user preference)

1. Log in → `/memories` → press **Ctrl+Shift+R** → confirm 3-column masonry, no enlargement.
2. Navigate to Shared → back to Memories → confirm identical layout to the refreshed view.
3. Resize to tablet and mobile widths → refresh at each → confirm 2 / 1 columns.
4. Repeat with `npm run dev` stopped (production build).
5. Test in Chrome/Edge in addition to Zen (Zen is the only browser tested so far; the fix should be
   browser-agnostic).
6. Spot-check upload flow and load-more after the fix.

## 11. Non-Goals

- No backend/API changes; no changes to the `Photo` data contract.
- No changes to the viewer, upload modal, or header.
- No composition redesign (no uniform tiles/cropping).
- No new dependencies unless the confirmed root cause requires one (prefer built-in CSS
  `aspect-ratio` and a small local composable).

## 12. Environment Notes (pre-existing, apply when running tests)

- The shell exports `APP_ENV=local`, which defeats PHPUnit’s env overrides — `phpunit.xml` now
  forces `APP_ENV=testing` (env + server entries) so `php artisan test` runs green.
- Windows `SESSION_PATH` may point at the Git install dir; the app config now validates/normalises
  it. Herd PHP’s `upload_tmp_dir` / upload limits were raised to 25MB for photo uploads (system
  php.ini, backed up at `php.ini.bak-upload-fix`).
- Dev server: `php artisan serve` on :8000; Vite dev on :5173 (served via IPv6 `[::1]` in the HTML).

## 13. Open Items / To Confirm at Implementation

- Exact placeholder treatment (fully invisible vs. subtle tint) — "subtle" approved; exact styling
  to be decided during implementation.
- Whether the root cause is H1 or H2 (confirm in browser first).
- Whether to add a small unit test for the column-distribution/breakpoint logic (user chose manual
  validation; a unit test is optional but recommended for regression protection).
