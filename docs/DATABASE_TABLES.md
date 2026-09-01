# Task Tracker MCP — таблицы БД

Формат — по аналогии с `reports_msrv/docs/DATABASE_TABLES.md`.

## `tasks`

| Колонка | Тип | Назначение |
|---|---|---|
| `id` | uuid, PK | Идентификатор задачи |
| `title` | string | Название |
| `description` | text, nullable | Описание |
| `status` | enum(`todo`,`in_progress`,`done`,`cancelled`) | Статус, default `todo` |
| `assignee_id` | FK → `users.id`, nullable | Исполнитель |
| `created_by_id` | FK → `users.id`, nullable | Автор |
| `created_at`/`updated_at` | timestamps | |

Миграция: `database/migrations/2026_09_01_000001_create_tasks_table.php`.

## `mcp_tokens`

| Колонка | Тип | Назначение |
|---|---|---|
| `id` | uuid, PK | Идентификатор токена |
| `user_id` | FK → `users.id` | Владелец |
| `token` | string(64), unique | Значение токена (случайная строка) |
| `name` | string, nullable | Метка токена (например, «my-connector») |
| `revoked_at` | timestamp, nullable | Момент отзыва; `NULL` = активен |
| `created_at`/`updated_at` | timestamps | |

Миграция: `database/migrations/2026_09_01_000002_create_mcp_tokens_table.php`.

## `users`, `personal_access_tokens`

Стандартные таблицы Laravel/Sanctum, не специфичны для модуля TaskTracker —
см. `database/migrations/0001_01_01_*` и миграцию Sanctum.
