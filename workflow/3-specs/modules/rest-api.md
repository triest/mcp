# Модуль: REST API

Классический REST-контракт для `ACTOR-1-USER` (человек, веб-UI `/tasks` или любой HTTP-клиент). Аутентификация — Laravel Sanctum (`access_token`, bearer).

## Эндпоинты

| Метод | Путь | Auth | Назначение |
|---|---|---|---|
| GET | `/api/health` | нет | health-check |
| POST | `/api/auth/register` | нет | регистрация, выдаёт `access_token` |
| POST | `/api/auth/login` | нет | вход, выдаёт `access_token` |
| GET | `/api/tasks` | Sanctum | список задач (`?status=` фильтр) |
| POST | `/api/tasks` | Sanctum | создать задачу |
| GET | `/api/tasks/{id}` | Sanctum | получить задачу |
| PATCH | `/api/tasks/{id}` | Sanctum | частичное обновление (статус, title, description, assignee) |
| DELETE | `/api/tasks/{id}` | Sanctum | удалить задачу |
| POST | `/api/mcp-tokens` | Sanctum | выпустить MCP-токен |
| GET | `/api/mcp-tokens` | Sanctum | список своих MCP-токенов |
| DELETE | `/api/mcp-tokens/{id}` | Sanctum | отозвать MCP-токен |

## HTTP-исходы (полная грамматика — REST)

См. таблицу REST-версии в `../../../tasks-naming-spec.md` (раздел "Use Case grammar / HTTP outcomes — REST version", ~17 строк с реальными кодами `200/201/401/403/404/422`).

## Связанные use case

`../use-cases/UC-1-*.md` … `UC-5-*.md` (REST-ветка).
