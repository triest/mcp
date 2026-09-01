# EVT-4-UPDATE_DETAILS-IN-TASKTRACKER

RA-код: `RA-TASKS-EVENT-UPDATE_DETAILS`, `RA-TASKS-EVENT-DELETE`

## Что происходит

- `UPDATE_DETAILS`: правка `title`/`description` без смены статуса или исполнителя.
- `DELETE`: физическое удаление задачи (`TaskController::destroy` / MCP `delete_task`) — жёсткое удаление, без soft-delete (сознательное упрощение для MVP).

## Кто инициирует

`ACTOR-1-USER` или `ACTOR-3-MCP-CLIENT`.

## Связанные use case

`../use-cases/UC-2-*.md`
