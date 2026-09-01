# 3 — Specs

Detailed, implementation-ready specifications for each backlog item from `../2-tasks/`. This is where the **how** is nailed down before code: actors, entities, states, domain events and use cases.

Full RA-notation source of truth for this project: `../../tasks-naming-spec.md` (root of the repo). The files here are the same model, split into the flat per-kind files this template expects — `actors/`, `entities/`, `events/`, `modules/`, `use-cases/` — not a second, competing source of truth.

## Traceability

| Backlog task | Spec files |
|---|---|
| `TT-1` Регистрация и вход | `actors/ACTOR-1-USER.md`, `modules/rest-api.md`, `use-cases/UC-1-*.md` |
| `TT-2` CRUD задач | `entities/ENT-1-TASK.md`, `events/EVT-1-CREATE.md`, `modules/rest-api.md`, `use-cases/UC-2-*.md` |
| `TT-3` Смена статуса | `events/EVT-2-CHANGE-STATUS.md`, `use-cases/UC-3-*.md` |
| `TT-4` Назначение | `actors/ACTOR-2-ASSIGNEE.md`, `events/EVT-3-ASSIGN.md`, `use-cases/UC-4-*.md` |
| `TT-5` MCP-токены | `entities/ENT-2-MCP-TOKEN.md`, `events/EVT-5-ISSUE-REVOKE-TOKEN.md`, `use-cases/UC-5-*.md` |
| `TT-6` MCP JSON-RPC | `actors/ACTOR-3-MCP-CLIENT.md`, `modules/mcp-json-rpc.md` |
| `TT-7` Единая модель | `entities/ENT-1-TASK.md`, `entities/ENT-2-MCP-TOKEN.md` |

See `CLAUDE.md` in this folder for the naming convention used across all files here.
