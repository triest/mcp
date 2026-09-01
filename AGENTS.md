# AGENTS.md

Общие инструкции для AI-агентов (Claude Code, Cursor, Codex, Copilot Workspace
и т.п.), работающих с этим репозиторием.

## Обзор проекта

Laravel 12 приложение: таск-трекер с REST API и MCP-сервером на
`POST /api/mcp` (JSON-RPC 2.0). Полное описание архитектуры и API — в
README.md. Для Claude Code дополнительно см. CLAUDE.md.

## Стек и требования

- PHP 8.2+, Composer
- Laravel 12, Laravel Sanctum
- БД: SQLite (по умолчанию, `database/database.sqlite`) или MySQL

## Установка и проверка перед коммитом

```bash
composer install
php artisan migrate
php artisan serve
```

Обязательно перед отправкой изменений:

1. `php artisan route:list` — убедиться, что роуты `/api/mcp`,
   `/api/tasks`, `/api/auth/*` не сломаны.
2. `php -l` на изменённые PHP-файлы (синтаксис).
3. Ручная проверка `/api/health` и хотя бы одного MCP-метода
   (`tools/list`) через curl — см. пример в CLAUDE.md.

## Структура (см. также README.md → «Структура проекта»)

- `app/Services/McpToolRegistry.php` — единственный источник истины по
  MCP-инструментам (описание + реализация).
- `app/Http/Controllers/McpController.php` — JSON-RPC роутер, без бизнес-логики.
- `app/Http/Middleware/AuthenticateMcpToken.php` — авторизация MCP отдельным
  токеном, не смешивать с Sanctum access_token обычного REST API.
- `routes/api.php` / `routes/web.php` — все роуты, не создавать параллельных.

## Соглашения

- Новые MCP-инструменты — только через `McpToolRegistry` (и обновление
  `inputSchema` там же).
- Новые поля в БД — новая миграция, не редактировать существующие
  применённые миграции задним числом.
- Не коммитить `.env`, `vendor/`, `database/database.sqlite`,
  `storage/*.key` — уже покрыто `.gitignore`, но перепроверяй `git status`
  перед коммитом.
- Секреты (пароли, токены хостинга, MySQL-креды) никогда не хранить в
  репозитории — только в `.env` на конкретном окружении.

## Известные ограничения

- MCP-авторизация — простой статический токен (не OAuth 2.0). Полноценный
  OAuth-сервер (Laravel Passport + discovery + dynamic client registration)
  не реализован — см. README.md → «Известные ограничения / TODO».
- Задачи не разделены по проектам/командам.
