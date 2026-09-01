# Task Tracker MCP — обзор

Laravel 12 микросервис: REST API + собственный MCP-сервер (JSON-RPC 2.0
поверх HTTP) для управления задачами. Аналог референсного проекта
`queue-assignment` (электронная очередь), только предметная область —
задачи, а не очередь.

Собственного фронтенда в классическом смысле нет — есть один Blade-экран
`/tasks` (вход/регистрация, список, создание, смена статуса, удаление,
выпуск MCP-токена), построенный поверх того же REST API, которым пользуются
и обычные клиенты, и MCP-агенты.

## Модуль в фокусе

**TaskTracker** (`app/Models/Task.php`, `app/Models/McpToken.php`,
`app/Http/Controllers/{Task,Mcp,McpToken,Auth}Controller.php`,
`app/Services/McpToolRegistry.php`) — единственный модуль проекта: создание,
назначение, смена статуса и удаление задач, плюс выпуск/отзыв MCP-токенов
для подключения агентов.

## Ключевые факты (сверено с кодом)

- БД: SQLite локально (`database/database.sqlite`), MySQL на проде
  (Спринтхост, `a1301727_tracker`).
- Авторизация REST — Laravel Sanctum (`access_token`); авторизация MCP —
  отдельная сущность `McpToken` (не Sanctum), через `?token=` или
  `Authorization: Bearer`.
- MCP-эндпоинт `POST /api/mcp` — собственная реализация JSON-RPC 2.0
  (`initialize`/`tools/list`/`tools/call`), без внешних MCP-пакетов.
- 6 MCP-инструментов: `list_tasks`, `create_task`, `get_task`,
  `update_task_status`, `assign_task`, `delete_task`.
- Полное описание REST API и MCP — в корневом `README.md`.

## Смежные модули вне фокуса

Нет — проект односоставный (`TaskTracker` = весь продукт).
