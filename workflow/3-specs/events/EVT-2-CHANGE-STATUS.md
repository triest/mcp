# EVT-2-CHANGE_STATUS-IN-TASKTRACKER

RA-код: `RA-TASKS-EVENT-CHANGE_STATUS`

## Что происходит

Статус существующей задачи меняется по разрешённому переходу (см. диаграмму в `../entities/ENT-1-TASK.md`).

## Переход

`todo|in_progress → in_progress|done|cancelled` на `ENT-1-TASK`.

## Кто инициирует

`ACTOR-1-USER` (REST `PATCH /api/tasks/{id}`) или `ACTOR-3-MCP-CLIENT` (MCP `update_task_status`).

## Связанные use case

`../use-cases/UC-3-*.md`
