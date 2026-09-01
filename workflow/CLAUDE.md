# Task Tracker MCP — Agentic Development Workflow

Формат конвейера — точная копия эталона
`C:\Users\user\Desktop\regagro-workflow-demo\regagro-workflow-demo\CLAUDE.md`.
Work flows through numbered stages, each living in its own folder. An artifact
only advances to the next stage once the previous one is complete.

## Workflow stages

```
0-vibes/ → 1-business-tasks/ → 2-tasks/ → 3-specs/ → 4-design/ → 5-tasks/
 idea/prd    observe & plan      backlog     specs      design    implementation
                                                                      │
   ┌──────────────────────────────────────────────────────────────────┘
   ▼
6-results/ → 7-eval/ → 8-security-check/ → 9-deploy/ → 10-observation/
  built       quality      security         release      watch → (loops back to 1)
```

The pipeline is a **loop**: `10-observation/` feeds signals back into
`1-business-tasks/observation/` to start the next cycle.

**Особенность этой ревизии:** проект уже реализован в ходе обычной
переписки с пользователем (см. `0-vibes/raw/`), поэтому конвейер собран
**ретроспективно** — стадии `0-vibes … 4-design` реконструированы по факту
реализованного кода, `5-tasks … 10-observation` описывают, что реально
сделано, со ссылками на конкретные файлы. Открытые вопросы помечены
`⚠ TODO`.

**Отличия от эталона (`regagro-workflow-demo`):** в `4-design/` нет
`figma/`/`react/`/`vue/` — UI модуля состоит из одного Blade-экрана без
компонентной библиотеки и Storybook, см. `4-design/README.md`. В `7-eval/`
нет `auto/` (Storybook-скриншот-тестов) по той же причине — см.
`7-eval/README.md`.

### 0. Vibes — `0-vibes/`
The earliest, pre-planning stage: raw ideas, inspiration, and loose direction —
the **vibe**, before anything is structured. Contains:

- `0-vibes/prd/` — Product Requirements Documents: the first structured
  artifact that turns a vibe into stated product intent, ready to hand off to
  business tasks.

### 1. Business tasks source — `1-business-tasks/`
Where work originates: **observation and planning**. Raw business needs,
research notes, stakeholder input, opportunities, and high-level plans. This is
the "why" — problems worth solving, before they are broken down into concrete
work. Split into two halves:

- `1-business-tasks/observation/` — signals from the running system, users, and
  ops, triaged by severity: `errors/`, `warnings/`, `infos/`.
- `1-business-tasks/planning/` — high-level plans, roadmaps, and priorities
  shaped from what observation surfaces.

### 2. Tasks to do — `2-tasks/`
The actionable **backlog**. Business tasks from stage 1 are decomposed here into
discrete, deliverable tasks with clear acceptance criteria. This is the "what" —
scoped units of work ready to be picked up.

### 3. Specs — `3-specs/`
Detailed **specifications** for tasks. Each spec expands a task into concrete
requirements: behavior, data, edge cases, API contracts, and acceptance tests.
This is the "how it should work" — the source of truth an implementation is
validated against. Organized by concern (naming conventions in
[`3-specs/CLAUDE.md`](3-specs/CLAUDE.md)):

- `3-specs/actors/` — who/what interacts with the system (roles, personas,
  external systems).
- `3-specs/entities/` — domain objects and their data (fields, relationships,
  invariants).
- `3-specs/events/` — things that happen (domain events, triggers, state
  transitions, side effects).
- `3-specs/modules/` — functional units that compose actors, entities, and
  events into behavior.
- `3-specs/use-cases/` — end-to-end scenarios tying an actor + event + entity
  to a result.

Дополнительно: полная сводная спецификация в отдельной RA-нотации (код вида
`RA-TASKS-<АКТОР>-<ДЕЙСТВИЕ>-НА-<ENTITY>-<HTTP>`, сделана скиллом
`spec-builder-ra`) — [`../tasks-naming-spec.md`](../tasks-naming-spec.md).
Файлы `3-specs/` этого конвейера — та же модель в формате
`regagro-workflow-demo` (`ACTOR-{n}-NAME` и т.д.), не второй источник истины.

### 4. Design — `4-design/`
**Design components** produced from specs. См. `4-design/README.md` —
в этом проекте design ограничен одним Blade-экраном, без Figma/React/Vue.

### 5. Tasks — `5-tasks/`
Concrete **implementation/build tasks** derived from the specs and designs.
Each task links back to its spec (`3-specs/…`).

### 6. Results — `6-results/`
The **output of implementation**: what was actually built.

### 7. Eval — `7-eval/`
**Evaluation** of results against the specs — the quality gate.

### 8. Security check — `8-security-check/`
**Security review** before shipping — the security gate.

### 9. Deploy — `9-deploy/`
**Release** of the security-cleared results to their target environment.

### 10. Observation — `10-observation/`
**Watching the deployed system** in production, triaged by severity (`errors/`,
`warnings/`, `infos/`). Closes the loop: signals feed back into
`1-business-tasks/observation/` to start the next cycle.

## Conventions

- Stage folders are numbered to reflect the direction of flow.
- Do not skip stages — a spec references a task, a design references a spec.
- Keep each artifact traceable to its upstream source (link back by id/name).
