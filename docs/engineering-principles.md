# Engineering Principles

## Purpose

This document defines the engineering principles that guide technical decisions throughout the project. All implementation decisions should align with these principles to ensure the codebase remains consistent, maintainable, and easy to understand.

---

# Core Philosophy

The primary goal of this project is to build software that is:

- Consistent
- Maintainable
- Reusable
- Readable
- Scalable
- Performant
- Testable

When trade-offs exist, prioritize **long-term maintainability and consistency** over short-term convenience.

---

# Decision Making

When multiple valid solutions exist, AI should:

- Not assume the preferred implementation.
- Present the available approaches.
- Explain the advantages and disadvantages of each approach.
- Recommend the solution that best aligns with the project's standards.
- Wait for developer approval before implementation.

If the project already has an established pattern:

- Follow the existing pattern whenever it is consistent.
- If inconsistencies are discovered, stop and request clarification before introducing a different approach.

---

# Framework Philosophy

Always prefer the built-in capabilities of the project's technology stack before recommending external solutions.

The project follows a **Framework First** approach:

- Use Laravel features before searching for Laravel packages.
- Use Vue features before introducing third-party Vue libraries.
- Use Inertia capabilities before adding additional client-server abstractions.

External packages should only be recommended when:

- The framework does not provide an adequate solution.
- The package significantly improves developer experience, maintainability, or reliability.
- The long-term benefits clearly outweigh the added dependency.

> **Developer approval is required before introducing any new package or dependency.**

---

# Architecture

Follow a clean and predictable architecture throughout the application.

General principles:

- Keep controllers thin.
- Place business logic in dedicated services or actions when complexity increases.
- Keep models focused on data relationships and domain behavior.
- Separate presentation logic from business logic.
- Prefer composition over inheritance.
- Follow the Single Responsibility Principle (SRP) where practical.

The goal is not to create unnecessary layers, but to keep responsibilities clear and maintainable.

---

# Abstraction

Create abstractions only when they:

- Improve consistency across the project.
- Reduce meaningful code duplication.
- Simplify long-term maintenance.
- Establish reusable project-wide patterns.

Avoid abstractions that:

- Exist only to reduce the number of lines of code.
- Introduce unnecessary complexity.
- Solve problems that do not yet exist.
- Hide simple logic behind excessive indirection.

> Favor simple and explicit implementations until a clear need for abstraction emerges.

---

# Code Quality

Write code that is:

- Easy to understand.
- Predictable.
- Self-explanatory.
- Consistent with the rest of the codebase.

General guidelines:

- Prefer readability over cleverness.
- Write descriptive class, method, and variable names.
- Keep functions focused on a single responsibility.
- Avoid deeply nested logic.
- Eliminate dead or unused code.
- Keep files organized and cohesive.

Optimize for future maintenance rather than short-term development speed.

---

# Performance

Write performant code without sacrificing readability.

Guidelines:

- Optimize only when necessary.
- Avoid premature optimization.
- Prevent unnecessary database queries.
- Prefer eager loading when appropriate.
- Minimize unnecessary frontend rendering.
- Reuse computed or cached values when beneficial.

Performance improvements should not significantly reduce code clarity.

---

# Testing

Code should be written with testability in mind.

Guidelines:

- Favor loosely coupled components.
- Keep business logic isolated from framework-specific code when appropriate.
- Write code that can be easily unit tested.
- Prefer deterministic behavior over hidden side effects.

The project uses **Pest** as its primary testing framework.

---

# Refactoring

Do **not** refactor existing code without developer approval.

When improvement opportunities are identified, AI should:

1. Explain the issue.
2. Describe the proposed refactor.
3. Explain the expected benefits.
4. Wait for developer approval before making changes.

Refactoring should improve maintainability without unnecessarily changing established project patterns.

---

# AI Collaboration

AI is an engineering partner, **not** an autonomous developer.

AI should:

- Follow existing project conventions.
- Reuse existing patterns before introducing new ones.
- Explain significant architectural decisions.
- Present alternatives when multiple valid approaches exist.
- Ask for clarification when project conventions are unclear.
- Request approval before introducing architectural changes.
- Request approval before installing new packages.
- Request approval before performing major refactors.

The developer is responsible for all final architectural and implementation decisions.

---

# Guiding Principles

Every contribution to the project should strive to be:

- Consistent with the existing codebase.
- Easy to read and understand.
- Easy to maintain.
- Easy to extend.
- Easy to test.
- Predictable in behavior.
- Aligned with the project's established architecture.

When in doubt:

> **Choose the solution that future developers—including your future self—will understand most easily.**

---

# Engineering Checklist

Before considering a task complete, verify the following:

- [ ] The implementation follows existing project conventions.
- [ ] Built-in framework features were preferred over external packages.
- [ ] No unnecessary abstractions were introduced.
- [ ] The solution is simple, readable, and maintainable.
- [ ] Business logic is placed in the appropriate layer.
- [ ] Performance considerations have been evaluated.
- [ ] The implementation can be tested effectively.
- [ ] Architectural changes have been approved.
- [ ] New dependencies have been approved.
- [ ] The resulting code feels like a natural part of the existing codebase.
