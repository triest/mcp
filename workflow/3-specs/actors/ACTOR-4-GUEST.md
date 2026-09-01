# ACTOR-4-GUEST-IN-TASKTRACKER

RA-код: `RA-TASKS-ACTOR-GUEST`

## Кто это

Неаутентифицированный посетитель `/tasks` или вызывающий REST/MCP без валидного токена.

## Что может

- Только `POST /api/auth/register`, `POST /api/auth/login`, `GET /api/health`.
- Любой другой запрос → `401`.

## Связанные use case

`../use-cases/UC-1-*.md` (регистрация/вход).
