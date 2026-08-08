# Project Structure

## Purpose

This document defines the project's structural conventions. It specifies where files should be placed and how directories should be organized to keep the codebase predictable, maintainable, and easy to navigate.

This project follows Laravel's default directory structure unless a convention is explicitly documented below.

Only project-specific structural decisions should be documented here.

---

## General Principles

- Follow Laravel's default directory structure whenever possible.
- Do not create new top-level directories without a clear purpose.
- Keep related files grouped together.
- Prioritize consistency over personal preference.
- If an appropriate location already exists, use it instead of creating a new one.

---

# Backend Structure

## Laravel Defaults

The backend follows Laravel's default application structure.

Avoid reorganizing Laravel's standard directories unless the project adopts a new architectural pattern.

Examples include:

- Controllers
- Models
- Enums
- Policies
- Providers
- Middleware

These should remain in their default Laravel locations.

---

## Enums

Application enums are stored in:

```text
app/
└── Enums/
```

Enums remain in a flat structure unless a future project requires additional organization.

Example:

```text
app/
└── Enums/
    ├── ProductStatusEnum.php
    ├── OrderStatusEnum.php
    └── UserRoleEnum.php
```

---

# Frontend Structure

The frontend is organized by responsibility.

```text
resources/
└── js/
    ├── components/
    ├── layouts/
    ├── lib/
    ├── pages/
    ├── types/
    └── utils/
```

---

## Pages

Pages represent route-level views.

Page folders should:

- Use lowercase names.
- Use singular feature names.

Example:

```text
pages/
├── product/
├── order/
├── customer/
```

---

## Components

Reusable Vue components are grouped by feature.

Structure:

```text
components/
├── product/
├── order/
├── dashboard/
```

Example:

```text
components/
└── product/
    ├── ProductTable.vue
    ├── ProductForm.vue
    ├── ProductDeleteModal.vue
```

Shared UI components provided by libraries (such as shadcn-vue) should remain in their default locations unless the project defines a different convention.

---

## Layouts

Application layouts belong in:

```text
resources/js/layouts/
```

---

## Types

Shared TypeScript types belong in:

```text
resources/js/types/
```

Example:

```text
types/
├── product.ts
├── order.ts
```

---

## Utilities

General frontend helper functions belong in:

```text
resources/js/utils/
```

---

## Libraries

Shared frontend libraries, plugins, and configuration wrappers belong in:

```text
resources/js/lib/
```

---

# Introducing New Directories

Before creating a new directory:

- Verify that an existing directory is not appropriate.
- Follow Laravel's conventions whenever possible.
- Keep the directory structure simple and predictable.
- Ask for developer approval if introducing a new organizational pattern.

---

# Guiding Principle

Every file should have one obvious location.

The project structure should make it easy for any developer to locate files without needing additional documentation.

When in doubt, follow Laravel's default structure and only introduce custom organization when it clearly improves maintainability.
