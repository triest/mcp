# UC-2-ACTOR-1-EVT-1-ENT-1-CREATED-IN-TASKTRACKER

RA-код: `RA-TASKS-USER-CREATE-НА-TASK-201` / MCP: `RA-TASKS-MCP_CLIENT-CREATE-НА-TASK-200`

## Актор

`ACTOR-1-USER` (REST) или `ACTOR-3-MCP-CLIENT` (MCP, инструмент `create_task`).

## Действие

`EVT-1-CREATE` — создание задачи.

## Предусловие

`title` заполнен.

## Результат

`ENT-1-TASK` создана в состоянии `todo`; `created_by_id` = вызывающий пользователь (для MCP — владелец использованного `McpToken`).

## Ответ

REST: `201`. MCP: `200` (тело `tools/call` содержит созданную задачу).

## Input/Output/Expected (REST)

| input | output | expected |
|---|---|---|
| `{title: "Проверить деплой"}` | `201 {id, title, status: "todo", ...}` | задача появляется в `GET /api/tasks` |

## Отказы

| Код | Причина |
|---|---|
| `422` | `title` пуст |
| `401` | не аутентифицирован / невалидный MCP-токен |
