# ACTOR-3-MCP-CLIENT-IN-TASKTRACKER

RA-код: `RA-TASKS-ACTOR-MCP_CLIENT`

## Кто это

Небюдский (не-человеческий) актор: AI-агент или CLI (например, Claude Code), обращающийся к `POST /api/mcp` по протоколу JSON-RPC 2.0. Авторизуется отдельным `McpToken` (не Sanctum), переданным через `?token=` или заголовок `Authorization: Bearer`.

## Важное отличие от ACTOR-1-USER

Единый REST-токен (Sanctum) и MCP-токен — разные механизмы, намеренно не смешиваются (см. `../../../CLAUDE.md` и `../../../AGENTS.md` в корне проекта, раздел про два вида токенов).

## Что может

Вызывать MCP-инструменты: `list_tasks`, `create_task`, `get_task`, `update_task_status`, `assign_task`, `delete_task` — с теми же доменными правилами, что и `ACTOR-1-USER` через REST, но транспортный код ответа почти всегда `200` (JSON-RPC оборачивает домен-ошибку в `error`-объект, см. `../modules/mcp-json-rpc.md`).

## Связанные use case

`../use-cases/` (MCP-версия), `../modules/mcp-json-rpc.md`.
