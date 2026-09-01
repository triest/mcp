# ENT-2-MCP-TOKEN-IN-TASKTRACKER

RA-код: `RA-TASKS-ENTITY-MCP_TOKEN` → `McpToken`

## Что это

Отдельный от Sanctum-токенов механизм авторизации для `ACTOR-3-MCP-CLIENT`. Позволяет отзывать доступ агентов независимо от веб-сессии пользователя.

## Ключевые поля

| Поле | Тип | Заметки |
|---|---|---|
| `id` | uuid, PK | `HasUuids` |
| `user_id` | uuid, FK → users | `cascadeOnDelete` |
| `token` | string(64), unique | `Str::random(64)` |
| `name` | string, nullable | метка, для чего выпущен |
| `revoked_at` | timestamp, nullable | `null` = активен |
| `created_at`, `updated_at` | timestamps | |

## Состояния

| Состояние | Условие |
|---|---|
| `active` (не персистентно как отдельное поле — вычисляется) | `revoked_at IS NULL` |
| `revoked` | `revoked_at IS NOT NULL` |

## Инварианты

- Отзыв токена — мягкий (`revoked_at` проставляется), запись не удаляется физически — сохраняется история выпуска/отзыва.
- Удаление/поиск токена всегда скопированы по `user_id` — пользователь не может отозвать чужой токен (`McpTokenController::destroy`, 404 вместо 403, чтобы не раскрывать существование чужих токенов).
