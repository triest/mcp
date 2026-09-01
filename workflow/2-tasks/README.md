# 2 — Tasks to do (backlog)

Business needs from `../1-business-tasks/` decomposed into discrete, actionable tasks — the **what**.

Each planning task (`PT-*`) from `../1-business-tasks/planning/` is broken down here into concrete backlog items. An item leaves this stage when it is detailed into a full spec in `../3-specs/`.

## Each task should have

- A clear title and short description
- Link back to its source business task
- Acceptance criteria (definition of done)

## Backlog

| Task | Title | Source | Spec |
|---|---|---|---|
| `TT-1` | Регистрация и вход пользователя | `../1-business-tasks/planning/PT-1-USER-TASK-MANAGEMENT.md` | `../3-specs/use-cases/` |
| `TT-2` | CRUD задач (создание, просмотр, обновление, удаление) | `../1-business-tasks/planning/PT-1-USER-TASK-MANAGEMENT.md` | `../3-specs/use-cases/` |
| `TT-3` | Смена статуса задачи (todo → in_progress → done / cancelled) | `../1-business-tasks/planning/PT-1-USER-TASK-MANAGEMENT.md` | `../3-specs/use-cases/` |
| `TT-4` | Назначение задачи исполнителю | `../1-business-tasks/planning/PT-1-USER-TASK-MANAGEMENT.md` | `../3-specs/use-cases/` |
| `TT-5` | Выпуск и отзыв MCP-токена | `../1-business-tasks/planning/PT-2-MCP-AGENT-TASK-MANAGEMENT.md` | `../3-specs/use-cases/` |
| `TT-6` | MCP JSON-RPC сервер: initialize / tools/list / tools/call | `../1-business-tasks/planning/PT-2-MCP-AGENT-TASK-MANAGEMENT.md` | `../3-specs/modules/mcp-json-rpc.md` |
| `TT-7` | Единая модель данных для REST и MCP (согласованность) | `../1-business-tasks/planning/PT-1-USER-TASK-MANAGEMENT.md`, `PT-2-MCP-AGENT-TASK-MANAGEMENT.md` | `../3-specs/entities/` |

## Acceptance criteria (общие для всех задач)

- REST-эндпоинт и/или MCP-инструмент реализован и покрывает use case из `../3-specs/use-cases/`.
- Ручная проверка через `curl` (см. `../../CLAUDE.md` в корне проекта) подтверждает ожидаемый HTTP-код.
- Изменения в БД проведены новой миграцией (не редактируем применённые).
