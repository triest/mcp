# EVT-3-ASSIGN-IN-TASKTRACKER

RA-код: `RA-TASKS-EVENT-ASSIGN`

## Что происходит

Задаче назначается (или снимается) исполнитель.

## Переход

`ENT-1-TASK.assignee_id: null|X → Y|null` — состояние самой задачи (`status`) не меняется этим событием.

## Кто инициирует

`ACTOR-1-USER` (REST) или `ACTOR-3-MCP-CLIENT` (MCP `assign_task`), затрагивает `ACTOR-2-ASSIGNEE`.

## Связанные use case

`../use-cases/UC-4-*.md`
