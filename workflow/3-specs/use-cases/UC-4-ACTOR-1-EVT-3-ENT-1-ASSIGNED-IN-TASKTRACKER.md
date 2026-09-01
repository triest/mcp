# UC-4-ACTOR-1-EVT-3-ENT-1-ASSIGNED-IN-TASKTRACKER

RA-код: `RA-TASKS-USER-ASSIGN-НА-TASK-200` / MCP: `RA-TASKS-MCP_CLIENT-ASSIGN-НА-TASK-200`

## Актор

`ACTOR-1-USER` или `ACTOR-3-MCP-CLIENT` (инструмент `assign_task`), затрагивает `ACTOR-2-ASSIGNEE`.

## Действие

`EVT-3-ASSIGN`.

## Предусловие

Задача существует; `assignee_id` (если не null) ссылается на существующего пользователя.

## Результат

`ENT-1-TASK.assignee_id` обновлён.

## Ответ

`200`.

## Отказы

| Код | Причина |
|---|---|
| `404` | задача или назначаемый пользователь не найдены |
