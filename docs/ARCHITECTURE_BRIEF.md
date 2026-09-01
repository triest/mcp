# Task Tracker MCP — архитектурная справка

Формат — по аналогии с `reports_msrv/docs/ARCHITECTURE_BRIEF.md`.

## Слои

- **REST API** (`app/Http/Controllers/AuthController.php`,
  `TaskController.php`, `McpTokenController.php`) — CRUD задач,
  auth (Sanctum), выпуск/отзыв MCP-токенов.
- **MCP-сервер** (`app/Http/Controllers/McpController.php` +
  `app/Services/McpToolRegistry.php`) — тонкий JSON-RPC роутер поверх той
  же бизнес-логики, что и REST; вся логика инструментов сосредоточена в
  `McpToolRegistry`, контроллер только маршрутизирует `initialize` /
  `tools/list` / `tools/call`.
- **Авторизация** — два независимых механизма: Sanctum `access_token` для
  REST (`auth:sanctum`), `McpToken` + middleware `AuthenticateMcpToken` для
  MCP (`mcp.token`). Не путать между собой (см. `AGENTS.md`).
- **UI** — один Blade-экран `resources/views/tasks.blade.php`, чистый
  vanilla JS поверх REST API, без сборки/бандлера.
- **Данные** — `Task` (корень), `McpToken` (независимая сущность
  авторизации), `User` (модель Laravel, вне модуля).

## Поток запроса

REST: `routes/api.php` → `auth:sanctum` → контроллер → модель → БД.

MCP: `routes/api.php` (`/api/mcp`) → `mcp.token` middleware (проверка
`McpToken`) → `McpController::handle` (JSON-RPC диспетчер) →
`McpToolRegistry::call()` → модель → БД → JSON-RPC `result`/`error`.

## Внешние зависимости

Нет внешних сервисов/очередей/интеграций — самодостаточное Laravel-приложение
с одной БД.
