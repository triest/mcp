# 5 — Tasks (implementation)

What was actually implemented, mapped back to `../2-tasks/` backlog items and `../3-specs/` specs.

| Backlog | Implemented as | Spec |
|---|---|---|
| `TT-1` | `AuthController::register/login`, `routes/api.php` | `../3-specs/use-cases/UC-1-*.md` |
| `TT-2` | `TaskController` (index/store/show/update/destroy), `Task` model, migration `create_tasks_table` | `../3-specs/use-cases/UC-2-*.md`, `../3-specs/entities/ENT-1-TASK.md` |
| `TT-3` | `TaskController::update` (status branch), MCP `update_task_status` | `../3-specs/use-cases/UC-3-*.md` |
| `TT-4` | `TaskController::update` (assignee_id branch), MCP `assign_task` | `../3-specs/use-cases/UC-4-*.md` |
| `TT-5` | `McpTokenController`, `McpToken` model, migration `create_mcp_tokens_table` | `../3-specs/use-cases/UC-5-*.md`, `../3-specs/entities/ENT-2-MCP-TOKEN.md` |
| `TT-6` | `McpController`, `McpToolRegistry`, `AuthenticateMcpToken` middleware, route `POST /api/mcp` | `../3-specs/modules/mcp-json-rpc.md` |
| `TT-7` | Shared `Task`/`User` models used by both `TaskController` (REST) and `McpToolRegistry` (MCP) | `../3-specs/entities/ENT-1-TASK.md` |

## UI

`resources/views/tasks.blade.php` + `routes/web.php` (`/tasks`) — see `../4-design/README.md` for why there is no separate design-stage artifact.

## Source files

See `../../README.md` ("Project structure") and `../../CLAUDE.md` ("Architecture") in the repo root for the full file map.
