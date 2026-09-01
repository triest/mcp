# CLAUDE.md

Инструкции для Claude Code при работе в этом репозитории.

## Что это

Laravel 12 таск-трекер с REST API и собственным MCP-сервером
(`POST /api/mcp`, JSON-RPC 2.0 поверх HTTP, без внешних MCP-пакетов).
Полное описание — в README.md, читай его первым.

## Архитектура — коротко

- `app/Services/McpToolRegistry.php` — единственное место, где определены
  MCP-инструменты (`definitions()`) и их обработка (`call()`). Добавляя
  новый MCP-инструмент, правь только этот файл + при необходимости
  контроллер/модель, которые он вызывает.
- `app/Http/Controllers/McpController.php` — тонкий JSON-RPC роутер
  (`initialize` / `tools/list` / `tools/call`), бизнес-логики тут быть не
  должно — она в `McpToolRegistry`.
- `app/Http/Middleware/AuthenticateMcpToken.php` — авторизация MCP-запросов
  отдельным токеном (модель `McpToken`), НЕ связана с Sanctum/access_token
  обычного REST API. Не путать эти два вида токенов.
- REST API (`AuthController`, `TaskController`, `McpTokenController`) и MCP
  используют одни и те же модели (`Task`, `McpToken`, `User`) и одну БД —
  любое изменение модели видно из обоих интерфейсов.

## Правила при изменениях

- Меняя схему `tasks`/`mcp_tokens` — создавай новую миграцию, не редактируй
  задним числом уже применённые (`database/migrations/2026_09_01_*`).
- Меняя набор MCP-инструментов — держи `inputSchema` в
  `McpToolRegistry::definitions()` в актуальном состоянии, это то, что видят
  MCP-клиенты.
- `routes/api.php` — единственное место регистрации `/api/mcp` и REST-роутов.
  Не создавай параллельных файлов роутов.
- После правок в `bootstrap/app.php` (alias `mcp.token`, `api`-роутинг) —
  обязательно проверяй `php artisan route:list`, что `/api/mcp` виден и
  завязан на нужный middleware.

## Локальный запуск / проверка

```bash
composer install
php artisan migrate
php artisan serve
curl http://127.0.0.1:8000/api/health
```

Быстрая проверка MCP вручную:

```bash
curl -X POST "http://127.0.0.1:8000/api/mcp?token=<MCP-токен>" \
  -H "Content-Type: application/json" \
  -d '{"jsonrpc":"2.0","id":1,"method":"tools/list","params":{}}'
```

## Известные ограничения (не пытаться "починить" без явного запроса)

- Авторизация MCP — статический токен, не OAuth 2.0. Веб-форма
  "Add custom connector" в Claude требует HTTPS + либо OAuth, либо открытый
  сервер — сейчас подключение туда работает только когда URL с токеном
  указан вручную и HTTPS настроен на хостинге. Для локальной разработки
  используется Claude Code (`claude mcp add --transport http ...`), которому
  HTTPS не требуется.
- Нет разделения задач по проектам/командам — плоский список.
