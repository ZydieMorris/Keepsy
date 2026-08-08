Keepsy — Memories Page Design & Structure

1. Page Overview

The Memories Page is the primary experience inside Keepsy.

It should feel like entering a private, premium photography gallery rather than a conventional dashboard or photo-management application.

The user's photographs are the main visual element.

The interface should remain quiet and minimal so the photos receive most of the attention.

Core Design Philosophy

Photography
    ↓
Whitespace
    ↓
Minimal Navigation
    ↓
Subtle Interaction
    ↓
Supporting UI

The page should NOT feel like:

A SaaS dashboard

A file manager

A traditional photo-management application

A card-based admin interface

A grid of identical thumbnails

The page SHOULD feel like:

A premium photography gallery

An editorial photo layout

A private digital memory space

Minimal

Elegant

Personal

Modern

2. Reference Layout

The Memories page should closely follow the provided visual reference.

The most important characteristic is the three-column editorial gallery with different image proportions.

┌───────────────────────────────────────────────────────────────┐
│                         HEADER                                │
├───────────────────┬───────────────────┬───────────────────────┤
│                   │                   │                       │
│                   │    LANDSCAPE      │                       │
│                   │      IMAGE        │                       │
│                   ├───────────────────┤                       │
│                   │                   │                       │
│    LARGE          │    LANDSCAPE /    │      LARGE             │
│    PORTRAIT       │    PORTRAIT       │      PORTRAIT          │
│                   │      IMAGE        │                       │
│                   │                   │                       │
│                   ├───────────────────┤                       │
│                   │                   │                       │
│                   │    LANDSCAPE      │                       │
│                   │      IMAGE        │                       │
│                   │                   │                       │
├───────────────────┤                   ├───────────────────────┤
│                   │                   │                       │
│    PORTRAIT /     │                   │     PORTRAIT /         │
│    LANDSCAPE      │                   │     LANDSCAPE          │
│                   │                   │                       │
└───────────────────┴───────────────────┴───────────────────────┘

The exact image arrangement should remain dynamic based on the available photos, but the visual rhythm should preserve varied image sizes rather than forcing every image into identical cards.

3. Page Structure

MemoriesPage
│
├── AppHeader
│
└── MemoriesGallery
    │
    ├── GalleryColumn
    │   ├── Photo
    │   ├── Photo
    │   └── Photo
    │
    ├── GalleryColumn
    │   ├── Photo
    │   ├── Photo
    │   └── Photo
    │
    └── GalleryColumn
        ├── Photo
        ├── Photo
        └── Photo

The gallery should occupy most of the available viewport.

Do not add unnecessary page containers around the gallery.

4. Header

The header should follow the established Keepsy header design.

Keepsy

Memories        Shared        People

                              +    ○

Header Requirements

Clean horizontal layout

White or warm off-white background

Thin bottom border

Compact height

Minimal typography

No large navigation blocks

No sidebar

No large colored background

No dashboard-style cards

Desktop

Height:
64px–72px

Horizontal Padding:
32px–48px

Tablet

Height:
64px

Horizontal Padding:
24px

Mobile

Height:
60px

Horizontal Padding:
16px–20px

5. Gallery Layout

The main gallery uses three columns on desktop.

Each column should have an independent vertical flow so images can preserve different aspect ratios.

Gallery
├── Column 1
│   ├── Image
│   ├── Image
│   └── Image
│
├── Column 2
│   ├── Image
│   ├── Image
│   └── Image
│
└── Column 3
    ├── Image
    ├── Image
    └── Image

Do not use equal-height rows.

6. Column 1

The first column should begin with a visually dominant image.

┌───────────────────┐
│                   │
│                   │
│      PORTRAIT     │
│                   │
│                   │
│                   │
└───────────────────┘

┌───────────────────┐
│       IMAGE       │
└───────────────────┘

The first image can be portrait and should occupy significant vertical space.

7. Column 2

The center column should contain multiple shorter images.

┌──────────────────────┐
│      LANDSCAPE       │
└──────────────────────┘

┌──────────────────────┐
│        IMAGE         │
└──────────────────────┘

┌──────────────────────┐
│      LANDSCAPE       │
└──────────────────────┘

This creates the central visual rhythm.

8. Column 3

The third column should visually balance the first column.

┌───────────────────┐
│                   │
│      PORTRAIT     │
│                   │
│                   │
└───────────────────┘

┌───────────────────┐
│       IMAGE       │
└───────────────────┘

The first image should generally be visually prominent.

9. Image Sizing

Do not force every image to the same aspect ratio.

Preserve the original image ratio where practical.

.gallery-image {
    width: 100%;
    height: auto;
    display: block;
}

For controlled containers:

.gallery-image {
    width: 100%;
    object-fit: cover;
}

Do not crop important photographic subjects unnecessarily.

10. Gallery Spacing

The reference uses tight spacing between photographs.

Desktop:
16px–20px

Tablet:
12px–16px

Mobile:
8px–12px

Do not use large card gaps.

11. Gallery Container

The gallery should have enough horizontal breathing room while still allowing photographs to dominate the screen.

Desktop:
32px–48px left/right

Tablet:
24px

Mobile:
12px–16px

Avoid a narrow centered container.

The gallery should feel wide and immersive.

12. Photo Hover Interaction

Hovering over a photo should create a subtle interaction.

Scale:
1 → 1.015

Duration:
250ms–400ms

Easing:
ease-out

Optional overlay:

opacity:
0 → 1

Keep the interaction quiet and premium.

13. Photo Overlay

On desktop hover, an image can display minimal metadata.

┌──────────────────────────┐
│                          │
│          PHOTO           │
│                          │
│                          │
│  Singapore               │
│  July 18, 2026           │
└──────────────────────────┘

Avoid:

Large text

Multiple buttons

Heavy gradients

Large icons

Excessive information

14. Photo Actions

Photo actions should remain hidden until interaction.

Possible actions:

Favorite
Share
More

Example:

                         ♡
                         ⋯

These controls should appear only when needed.

15. Upload Action

Uploading should be accessible from the header.

Primary action:

+

Optional tooltip:

Add memories

Do not place a large upload card above the gallery.

The gallery should remain the main experience.

16. Upload Modal

Clicking the + button should open a minimal upload interface.

┌──────────────────────────────────────────┐
│                                          │
│              Add memories                │
│                                          │
│      Drop photos here                    │
│      or choose from your device          │
│                                          │
│                 +                        │
│                                          │
│          JPG · PNG · WEBP · HEIC         │
│                                          │
│            Choose photos                 │
│                                          │
└──────────────────────────────────────────┘

The modal should be:

Minimal

Centered

Spacious

Rounded

Light

Elegant

Avoid making it look like a file-management interface.

17. Drag and Drop

Users should be able to drag photos directly onto the Memories page.

When dragging:

Gallery
    ↓
Subtle dimming
    ↓
Drop zone appears

Example:

┌──────────────────────────────────────────┐
│                                          │
│          Drop to add memories            │
│                                          │
└──────────────────────────────────────────┘

Do not completely replace the gallery.

Use a subtle overlay.

18. Upload Preview

After selecting photos:

Add memories

┌─────────┐ ┌─────────┐ ┌─────────┐
│  PHOTO  │ │  PHOTO  │ │  PHOTO  │
└─────────┘ └─────────┘ └─────────┘

8 photos selected


Where should these memories go?

[ Personal memories ]

Cancel                    Upload

The preview should use small versions of the actual photos.

19. Upload Progress

During upload:

Uploading memories...

██████████████████░░░░

12 of 16 photos

Do not interrupt the entire gallery.

20. Upload Completion

After successful upload:

16 memories added

The new photos should smoothly enter the gallery.

Upload complete
      ↓
Thumbnail appears
      ↓
Image fades in
      ↓
Image moves into gallery position

Recommended:

opacity:
0 → 1

transform:
translateY(12px) → translateY(0)

duration:
400ms–600ms

21. Gallery Loading Animation

When the page initially loads, images should appear progressively.

opacity:
0 → 1

transform:
translateY(10px) → translateY(0)

duration:
300ms–500ms

Use a small stagger.

Image 1:
0ms

Image 2:
40ms

Image 3:
80ms

Image 4:
120ms

Do not animate every image dramatically.

22. Infinite Scrolling

The Memories page should support continuous scrolling.

When the user approaches the bottom:

Load more memories

New photos should append naturally to the gallery.

Avoid pagination controls unless required later.

23. Empty State

If the user has no memories, do not show a generic dashboard empty state.

Use:

Your memories are waiting.

Add your first photos and start
building your private gallery.

              + Add memories

Keep the empty state visually minimal.

24. Responsive Behavior

Desktop

Use three columns.

┌────────────┬────────────┬────────────┐
│            │            │            │
│  Column 1  │  Column 2  │  Column 3  │
│            │            │            │
└────────────┴────────────┴────────────┘

Tablet

Use two columns.

┌────────────────┬────────────────┐
│                │                │
│    Column 1    │    Column 2    │
│                │                │
└────────────────┴────────────────┘

Mobile

Use one column.

┌────────────────────────────┐
│                            │
│           PHOTO            │
│                            │
└────────────────────────────┘

Preserve natural aspect ratios.

25. Mobile Header

Keepsy                           +   ○

Hide desktop navigation on mobile and use a menu if necessary.

26. Typography

Use the Keepsy typography system.

Primary Font:
Plus Jakarta Sans

Logo:
26px / 700

Navigation:
12px–13px / 500

Supporting Text:
14px / 400

Photo Metadata:
12px / 400

The gallery itself should contain very little typography.

27. Color System

Background:
#F8F7F5

Surface:
#FFFFFF

Primary Text:
#252329

Secondary Text:
#4F4A51

Muted Text:
#77727A

Subtle:
#A29DA4

Border:
#E9E6E8

Brand:
#6D5A72

The UI should remain mostly neutral.

The user's photographs should provide most of the color.

28. Border Radius

Use subtle rounding.

Gallery images:
4px–6px

Upload modal:
16px–20px

Buttons:
8px–10px

Avoid excessive rounded cards.

29. Shadows

Do not use shadows on individual photographs.

Use shadows only for floating UI such as:

Upload modal
Dropdown
Context menu

30. Photo Viewer

Clicking a photo should open a full-screen viewer.

┌──────────────────────────────────────────────┐
│                                              │
│                                              │
│                   PHOTO                      │
│                                              │
│                                              │
│  Singapore · July 2026              1 / 28  │
└──────────────────────────────────────────────┘

Use a dark background:

#111111

The photo should become the primary focus.

31. Photo Viewer Animation

When opening a photo:

Gallery thumbnail
      ↓
Smooth expansion
      ↓
Full-screen image

Recommended:

Duration:
350ms–500ms

Easing:
ease-out

Support:

Escape → Close
Arrow Left → Previous
Arrow Right → Next

32. Interaction Principles

Default:
Quiet

Hover:
Subtle

Click:
Smooth

Upload:
Clear

Success:
Elegant

Error:
Helpful

Navigation:
Minimal

Never overwhelm the user with controls.

33. Performance Requirements

The gallery may contain many images.

Use:

Lazy loading

Responsive image sizes

Thumbnail generation

Optimized image formats where supported

Progressive loading

Proper image dimensions

Virtualization only if required

Do not load every full-resolution image immediately.

34. Accessibility

Every photo should have meaningful alternative text where appropriate.

<img
    src="..."
    alt="Two people walking beside the ocean"
/>

Interactive elements must support:

Keyboard navigation

Focus states

Accessible labels

Sufficient contrast

Clear click targets

35. Component Structure

Recommended Vue structure:

resources/js/
│
├── components/
│   ├── layout/
│   │   └── AppHeader.vue
│   │
│   └── memories/
│       ├── MemoriesGallery.vue
│       ├── MemoriesColumn.vue
│       ├── MemoryPhoto.vue
│       ├── MemoryPhotoActions.vue
│       ├── MemoryUpload.vue
│       ├── MemoryUploadModal.vue
│       ├── MemoryUploadPreview.vue
│       ├── MemoryUploadProgress.vue
│       ├── MemoryDropZone.vue
│       ├── MemoryEmptyState.vue
│       └── MemoryViewer.vue
│
└── pages/
    └── memories/
        └── Index.vue

36. Page Component

Conceptual structure:

<script setup lang="ts">
</script>

<template>
    <div class="min-h-screen">
        <AppHeader />

        <main>
            <MemoriesGallery />
        </main>

        <MemoryUploadModal />
    </div>
</template>

Keep the page responsible for composition.

Move gallery-specific behavior into dedicated components.

37. Gallery Component

Conceptual structure:

<template>
    <section class="gallery">
        <MemoriesColumn :photos="columnOne" />

        <MemoriesColumn :photos="columnTwo" />

        <MemoriesColumn :photos="columnThree" />
    </section>
</template>

The column distribution should be calculated dynamically rather than manually hardcoded for every user.

38. Photo Component

Conceptual structure:

<template>
    <button
        type="button"
        class="group relative block w-full overflow-hidden"
    >
        <img
            :src="photo.url"
            :alt="photo.alt"
            class="block h-auto w-full transition-transform duration-500 group-hover:scale-[1.015]"
        />

        <MemoryPhotoActions />
    </button>
</template>

Keep the default state clean.

39. Upload Architecture

The upload flow should be:

Click +
   ↓
Open upload modal
   ↓
Select / drop photos
   ↓
Validate files
   ↓
Show previews
   ↓
Upload
   ↓
Show progress
   ↓
Process images
   ↓
Add photos to gallery
   ↓
Show success feedback

40. File Validation

Initial allowed formats:

JPEG
JPG
PNG
WEBP
HEIC

Validate:

File type
File size
Image dimensions
Maximum number of files

Server-side validation is required in addition to client-side validation.

41. Security

Uploaded photos must be protected by authorization.

Server-side validation must verify:

MIME type
File extension
File size
Image validity
Authorization
Ownership

Users should only access their private photos or photos explicitly shared with them.

42. Ownership

Conceptually:

User
  │
  └── Memories
        │
        ├── Photo
        ├── Photo
        └── Photo

Every uploaded photo belongs to a user.

43. Sharing Compatibility

The gallery should support future sharing features.

A photo can eventually contain:

Owner
Shared With
Visibility
Created At
Updated At

Sharing actions should be accessible through:

Photo Actions
     ↓
Share

Do not expose sharing controls everywhere.

44. Design Don'ts

❌ Dashboard statistics
❌ Total photo cards
❌ Large "Upload Photos" banner
❌ Sidebar navigation
❌ Excessive filters
❌ Large search bar
❌ Heavy shadows
❌ Bright UI colors
❌ Excessive rounded cards
❌ Identical photo tiles
❌ Large headings above the gallery

The gallery remains the primary interface.

45. Design Do's

✓ Use a three-column editorial layout
✓ Preserve image proportions
✓ Use varied image heights
✓ Keep gaps small and consistent
✓ Use generous whitespace
✓ Keep the header minimal
✓ Make photos dominant
✓ Use subtle hover interactions
✓ Use smooth gallery transitions
✓ Make uploading effortless
✓ Use a cinematic photo viewer
✓ Support responsive layouts
✓ Keep UI colors neutral

46. Final Desktop Composition

The final desktop experience should feel approximately like:

┌──────────────────────────────────────────────────────────────┐
│ Keepsy       Memories      Shared      People        +    ○  │
├───────────────────┬───────────────────┬──────────────────────┤
│                   │                   │                      │
│                   │                   │                      │
│      LARGE        │    LANDSCAPE      │       LARGE          │
│     PORTRAIT      │                   │      PORTRAIT         │
│                   ├───────────────────┤                      │
│                   │                   │                      │
│                   │      IMAGE        │                      │
│                   ├───────────────────┤                      │
│                   │                   │                      │
├───────────────────┤    LANDSCAPE      ├──────────────────────┤
│                   │                   │                      │
│      IMAGE        │                   │        IMAGE         │
│                   │                   │                      │
└───────────────────┴───────────────────┴──────────────────────┘

The exact photos and their dimensions will change dynamically.

The visual rhythm should remain consistent.

47. Final Experience

Login
  ↓
Memories
  ↓
Beautiful gallery appears
  ↓
Photos gradually load
  ↓
User scrolls naturally
  ↓
Hover reveals subtle actions
  ↓
Click opens cinematic viewer
  ↓
+ allows effortless uploading
  ↓
New photos smoothly enter the gallery

The user should feel:

"This is my gallery."

not:

"This is my photo management dashboard."

48. Final Design Statement

Keepsy Memories is a private editorial photo gallery, not a dashboard.

The design should make the user's photographs the interface.

The header should be quiet.

The gallery should dominate.

The image arrangement should feel intentional but dynamic.

Interactions should be subtle.

Uploading should feel effortless.

Viewing a memory should feel immersive.

The overall experience should be:

Minimal
+
Editorial
+
Elegant
+
Personal
+
Photographic
+
Modern
