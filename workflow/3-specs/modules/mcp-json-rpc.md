# Модуль: MCP JSON-RPC

Кастомный (без внешних MCP-пакетов) сервер JSON-RPC 2.0 поверх HTTP для `ACTOR-3-MCP-CLIENT`.

## Точка входа

`POST /api/mcp` — единственный эндпоинт, метод передаётся в теле JSON-RPC (`initialize`, `tools/list`, `tools/call`).

## Авторизация

Отдельная от Sanctum: middleware `mcp.token` (`AuthenticateMcpToken`) проверяет `?token=` query-параметр или заголовок `Authorization: Bearer`, ищет активный (`revoked_at IS NULL`) `ENT-2-MCP-TOKEN`.

## Инструменты (`tools/call` → `name`)

| Инструмент | REST-эквивалент | Событие |
|---|---|---|
| `list_tasks` | `GET /api/tasks` | — (чтение) |
| `create_task` | `POST /api/tasks` | `EVT-1-CREATE` |
| `get_task` | `GET /api/tasks/{id}` | — (чтение) |
| `update_task_status` | `PATCH /api/tasks/{id}` (status) | `EVT-2-CHANGE_STATUS` |
| `assign_task` | `PATCH /api/tasks/{id}` (assignee_id) | `EVT-3-ASSIGN` |
| `delete_task` | `DELETE /api/tasks/{id}` | `EVT-4-DELETE` |

## Важное транспортное отличие от REST

Ответ JSON-RPC почти всегда `HTTP 200`, даже когда домен вернул ошибку — она заворачивается в `{"error": {"code": -32000, "message": ...}}` внутри тела 200-го ответа (см. `McpController::handle`, `catch (\Throwable $e)`). Единственное исключение — `401` от `mcp.token` middleware при отсутствующем/невалидном/отозванном токене, который отдаётся до того, как запрос доходит до JSON-RPC диспетчера.

Полная MCP-версия таблицы исходов (с псевдо-меткой `200E` для доменных ошибок внутри 200) — в `../../../tasks-naming-spec.md`, раздел "MCP version" (8 строк).

## Связанные use case

`../use-cases/` (MCP-ветка каждого UC).
