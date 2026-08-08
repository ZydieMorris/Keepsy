# Development Workflow

## Purpose

This document defines the standard workflow for AI-assisted development within this project. It establishes how AI should approach feature development, collaborate with the developer, and make implementation decisions while maintaining consistency across the codebase.

The objective is to ensure every feature follows the project's established standards while keeping the developer in control of architectural and technical decisions.

---

# Understand Before Building

Before implementing any feature, AI should:

- Fully understand the requested feature and its requirements.
- Analyze the existing codebase for similar implementations.
- Reuse existing patterns whenever possible.
- Follow the project's established conventions before introducing new approaches.
- Ask questions whenever requirements or expectations are unclear.

---

# Follow Existing Patterns

Before creating new implementations, AI should:

- Search the project for similar features.
- Reuse the existing architecture, coding patterns, and conventions whenever applicable.
- Continue following an established pattern if one already exists.
- Stop and request clarification if inconsistencies are discovered before introducing a new pattern.

> **Guiding Rule:** Consistency across the codebase is more important than introducing a technically "better" implementation.

---

# Architectural Decisions

When multiple valid implementation approaches exist, AI should:

- **Not** automatically choose an implementation.
- Present the available options.
- Explain the advantages and disadvantages of each approach.
- Recommend the approach that best aligns with the project's architecture and conventions.
- Wait for developer approval before proceeding with implementation.

> The developer always makes the final architectural decision.

---

# Framework First

Always prefer the built-in capabilities of the project's technology stack before recommending external packages.

Before introducing a new dependency, AI should:

- Verify that Laravel, Vue, Inertia, or the existing stack does not already provide an adequate solution.
- Explain why an external package would be beneficial.
- Request developer approval before installing or recommending any new package.

---

# Feature Development

When implementing new functionality, AI should:

- Follow the established project architecture.
- Reuse existing components, composables, services, and utilities whenever appropriate.
- Keep implementations consistent with existing features.
- Create reusable abstractions only when they improve maintainability, consistency, or eliminate meaningful duplication.
- Avoid unnecessary complexity or speculative abstractions.

---

# Refactoring

Do **not** refactor working code without developer approval.

If improvements are identified, AI should:

1. Explain the issue.
2. Describe the proposed improvement.
3. Explain the expected benefits.
4. Wait for developer approval before making any changes.

---

# Communication

During development, AI should:

- Clearly explain significant technical decisions.
- Explain assumptions before implementing them.
- Ask for clarification whenever project conventions are unclear.
- Never assume developer preferences when multiple valid solutions exist.

> AI should optimize for collaboration rather than autonomy.

---

# Developer Approval

AI must request approval before:

- Introducing new architectural patterns.
- Installing new packages or dependencies.
- Performing significant refactoring.
- Changing existing project conventions.
- Introducing a new implementation pattern when no established pattern exists.

---

# Guiding Principles

The objective is not only to generate working code, but to produce code that is:

- Consistent
- Maintainable
- Reusable
- Readable
- Easy to navigate
- Easy to test
- Scalable

Every implementation should feel like it naturally belongs to the same codebase and follows the same engineering standards.

---

# AI Development Checklist

Before implementing any feature, AI should verify the following:

- [ ] I understand the feature requirements.
- [ ] I searched for similar implementations in the project.
- [ ] I am following existing coding conventions.
- [ ] I am reusing existing components, utilities, or services where appropriate.
- [ ] I am using built-in framework features before recommending external packages.
- [ ] I have presented architectural options if multiple valid approaches exist.
- [ ] I have requested approval before introducing significant architectural changes, packages, or refactors.
- [ ] My implementation is consistent with the rest of the codebase.
- [ ] My solution prioritizes readability and maintainability over cleverness.
- [ ] My implementation introduces no unnecessary complexity.
