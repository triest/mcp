# UC-5-ACTOR-1-EVT-5-ENT-2-ISSUED-IN-TASKTRACKER

RA-код: `RA-TASKS-USER-ISSUE_TOKEN-НА-MCP_TOKEN-201`

## Актор

`ACTOR-1-USER`.

## Действие

`EVT-5-ISSUE_TOKEN`.

## Предусловие

Пользователь аутентифицирован (Sanctum).

## Результат

Новый `ENT-2-MCP-TOKEN` создан, привязан к пользователю; открытый (plaintext) токен возвращается один раз в ответе.

## Ответ

`201`.

## Input/Output/Expected

| input | output | expected |
|---|---|---|
| `POST /api/mcp-tokens {name: "Claude Code"}` | `201 {token: "...", name: "Claude Code"}` | `/api/mcp?token=...` начинает принимать запросы этим токеном |
