# EVT-5-ISSUE_TOKEN-IN-TASKTRACKER / EVT-6-REVOKE_TOKEN-IN-TASKTRACKER

RA-коды: `RA-TASKS-EVENT-ISSUE_TOKEN`, `RA-TASKS-EVENT-REVOKE_TOKEN`

## Что происходит

- `ISSUE_TOKEN`: выпуск нового `ENT-2-MCP-TOKEN`, привязанного к `ACTOR-1-USER`, для дальнейшего использования `ACTOR-3-MCP-CLIENT`.
- `REVOKE_TOKEN`: мягкий отзыв — `revoked_at` проставляется, повторные MCP-запросы с этим токеном получают `401`.

## Переход

`∅ → active` / `active → revoked` на `ENT-2-MCP-TOKEN`.

## Кто инициирует

Только `ACTOR-1-USER`, через `/tasks` UI ("Выпустить MCP-токен") или `POST /api/mcp-tokens` / `DELETE /api/mcp-tokens/{id}`.

## Связанные use case

`../use-cases/UC-5-*.md`
