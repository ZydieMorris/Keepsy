# Spec: Controller-Backed Index Pages (Memories / Shared / People)

## 1. Overview

Replace the static `Route::inertia(...)` definitions for the three authenticated index pages
(`memories`, `shared`, `people`) with dedicated controllers that return the Inertia pages.
This is a **routing refactor only** — no UI, frontend, or behavior changes.

## 2. Current State

`routes/web.php`:

```php
Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('memories', 'memories/Index')->name('memories');
    Route::inertia('shared', 'shared/Index')->name('shared');
    Route::inertia('people', 'people/Index')->name('people');
});
```

Relevant context:

- Frontend pages: `resources/js/pages/memories/Index.vue`, `shared/Index.vue`, `people/Index.vue`
  (all currently empty states using `KeepsyEmptyState`).
- `resources/js/app.ts` layout resolver keys off the **component name** (`memories/Index`,
  `shared/Index`, `people/Index`) → all resolve to `KeepsyLayout`. Component names stay the
  same, so layouts are unaffected.
- Route names are consumed by Wayfinder-generated functions (`memories()`, `shared()`,
  `people()`) in `@/routes`, used by `KeepsyHeader.vue`, the landing page redirect, and
  `PasskeyVerify.vue`. **Route names must not change.**
- Existing controller conventions (from `Settings/ProfileController` and
  `Settings/SecurityController`): extend `App\Http\Controllers\Controller`, PHPDoc summary,
  typed return `: Response`, `Inertia::render()` facade.
- `tests/Feature/HomeTest.php` currently covers the `memories` route (guest redirect +
  authenticated access). `shared` and `people` have no dedicated tests.

## 3. Decisions (from interview — do not revisit)

| Topic | Decision |
| --- | --- |
| Controller split | One controller per page: `MemoriesController`, `SharedController`, `PeopleController` |
| Location | `app/Http/Controllers/` root (Laravel default — no new folders) |
| Naming | Plural controller names matching routes; method `index()` on each |
| Style | Match existing conventions: PHPDoc + `: Response` return type + `Inertia::render()` facade |
| Props | **Bare render, no props** — behavior identical to today |
| Method signature | Parameterless `index()` (Request type-hinting deliberately out of scope for now) |
| Route names | Unchanged (`memories`, `shared`, `people`) → zero frontend changes |
| Middleware | Keep the `['auth', 'verified']` group exactly as-is |
| Scope | Only the three index pages. The `/` home closure (landing) and `routes/settings.php` are untouched |
| Testing | Full Pest feature tests for all three: guest redirect to login, authenticated access, `assertInertia` component assertion |
| Test layout | One test file per page: `tests/Feature/MemoriesTest.php`, `SharedTest.php`, `PeopleTest.php` |
| Abstraction | Three simple standalone controllers — no shared base class or trait |
| Spec location | `docs/` (this file) |

## 4. Target Implementation

### 4.1 New controllers

`app/Http/Controllers/MemoriesController.php`:

```php
<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class MemoriesController extends Controller
{
    /**
     * Show the user's memories (photo albums) page.
     */
    public function index(): Response
    {
        return Inertia::render('memories/Index');
    }
}
```

`app/Http/Controllers/SharedController.php` — identical shape, renders `shared/Index`,
docblock "Show the albums shared with the user."

`app/Http/Controllers/PeopleController.php` — identical shape, renders `people/Index`,
docblock "Show the people the user shares with."

### 4.2 Route updates (`routes/web.php`)

```php
use App\Http\Controllers\MemoriesController;
use App\Http\Controllers\PeopleController;
use App\Http\Controllers\SharedController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('memories', [MemoriesController::class, 'index'])->name('memories');
    Route::get('shared', [SharedController::class, 'index'])->name('shared');
    Route::get('people', [PeopleController::class, 'index'])->name('people');
});
```

Notes:

- The `use Inertia\Inertia;` import in `web.php` **stays** — the `/` home closure still
  calls `Inertia::render('landing/Index')`.
- The `use Illuminate\Http\Request;` import stays for the `/` closure.

### 4.3 Tests

`tests/Feature/MemoriesTest.php`:

```php
<?php

use App\Models\User;

test('guests are redirected to the login page', function () {
    $this->get(route('memories'))->assertRedirect(route('login'));
});

test('authenticated users can visit the memories page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('memories'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('memories/Index'));
});
```

`tests/Feature/SharedTest.php` and `tests/Feature/PeopleTest.php` — identical structure with
`route('shared')` / `component('shared/Index')` and `route('people')` / `component('people/Index')`.

**Overlap with HomeTest:** `HomeTest.php` already covers the memories guest-redirect and
authenticated-access tests. Recommended cleanup during implementation: slim `HomeTest.php`
down to landing/`/`-behavior tests only (guest landing page, authenticated home → memories
redirect) and let `MemoriesTest.php` own the memories route coverage, so there is a single
source of truth. Confirm with the developer before deleting any existing tests.

## 5. Planned Future Prop Shapes (growth path — NOT implemented in this change)

Documented so the controllers have a clear direction when the features are built:

- **`memories/Index`** — `albums`: user's own albums, e.g.
  `{ id, name, cover_url, photo_count, created_at }` (+ maybe `total_photos`).
- **`shared/Index`** — albums shared with the user plus pending invitations, e.g.
  `{ albums: [...], invitations: [{ id, album: {...}, sender: {...}, status, accepted_at }] }`.
- **`people/Index`** — people the user shares albums with, e.g.
  `{ people: [{ id, name, avatar_url, shared_album_count }] }`.

These shapes are illustrative; they will be finalized when each feature is scoped.

## 6. Non-Goals

- No data queries or props in this change.
- No frontend/Vue changes (component names, routes, and layout resolution are untouched).
- No changes to the `/` landing route or `routes/settings.php`.
- No controller abstractions, traits, or shared base classes.
- No route renames.

## 7. Files Touched (planned)

New:

- `app/Http/Controllers/MemoriesController.php`
- `app/Http/Controllers/SharedController.php`
- `app/Http/Controllers/PeopleController.php`
- `tests/Feature/MemoriesTest.php`
- `tests/Feature/SharedTest.php`
- `tests/Feature/PeopleTest.php`

Modified:

- `routes/web.php` (three `Route::get` with controllers)
- `tests/Feature/HomeTest.php` (slimmed — pending confirmation, see §4.3)

## 8. Validation Checklist

- [ ] `vendor/bin/pint` on the touched PHP files
- [ ] `php artisan test --compact` — all tests green (including new page tests)
- [ ] No frontend changes → no `types:check` / `lint` / `build` impact expected
- [ ] Route names verified unchanged via `php artisan route:list` (`memories`, `shared`, `people`)

## 9. Environment Note (pre-existing, not part of this change)

On this machine, the Windows env var `SESSION_PATH=C:/Program Files/Git/` overrides `.env`'s
`SESSION_PATH=/` and breaks all Laravel requests (invalid cookie path → 500), and the shell
exports `APP_ENV=local`, which blocks PHPUnit's env overrides. Until fixed, run tests as:

```bash
env -u SESSION_PATH -u APP_ENV php artisan test --compact
```

Permanent fix: delete or correct the Windows `SESSION_PATH` user environment variable.
