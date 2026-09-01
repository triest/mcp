# UC-3-ACTOR-1-EVT-2-ENT-1-STATUS_CHANGED-IN-TASKTRACKER

RA-код: `RA-TASKS-USER-CHANGE_STATUS-НА-TASK-200` / MCP: `RA-TASKS-MCP_CLIENT-CHANGE_STATUS-НА-TASK-200`

## Актор

`ACTOR-1-USER` или `ACTOR-3-MCP-CLIENT` (инструмент `update_task_status`).

## Действие

`EVT-2-CHANGE_STATUS`.

## Предусловие

Задача существует; новый статус — одно из `todo|in_progress|done|cancelled`.

## Результат

`ENT-1-TASK.status` обновлён.

## Ответ

`200`.

## Отказы

| Код | Причина |
|---|---|
| `404` | задача не найдена |
| `422` | невалидное значение статуса |
