# 4 — Design

Visual/UX design artifacts for the specs in `../3-specs/`.

## Deviation from the reference template

The `regagro-workflow-demo` template expects `figma/`, `react/`, and `vue/` subfolders here (design mockups plus component-library implementations reviewed against them). This project has **none of that**: the whole UI is a single Blade view, `resources/views/tasks.blade.php`, with inline CSS and vanilla JS — no component library, no design tool, no Storybook.

This is a deliberate scope decision for an MVP task tracker, not an omission. If the UI grows beyond one page (multiple screens, reusable components), this stage should be revisited and the standard subfolders introduced.

## What exists instead

- The actual UI: `resources/views/tasks.blade.php` (project root, not this folder — the template treats implementation as living in `../5-tasks/` and the codebase itself, not here).
- Informal description of the UI/UX: see `../../README.md`, section "Web UI".
