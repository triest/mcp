# EVT-1-CREATE-IN-TASKTRACKER

RA-код: `RA-TASKS-EVENT-CREATE`

## Что происходит

Новая задача создаётся — вручную человеком через `/tasks` (REST) или агентом через MCP-инструмент `create_task`.

## Переход

`∅ → todo` на `ENT-1-TASK`.

## Кто инициирует

`ACTOR-1-USER` (REST) или `ACTOR-3-MCP-CLIENT` (MCP) — оба используют один и тот же `TaskController::store` / `McpToolRegistry::createTask` путь в итоге создающий одну и ту же строку в БД.

## Связанные use case

`../use-cases/UC-2-*.md`
