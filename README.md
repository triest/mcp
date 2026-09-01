# Task Tracker MCP

Свой мини-таск-трекер на Laravel 12 с REST API и встроенным MCP-сервером
(JSON-RPC 2.0 поверх HTTP) — аналог референсного проекта электронной очереди
(`queue-assignment`), только вместо очереди — задачи.

## Возможности

- Регистрация/логин пользователей (Laravel Sanctum, токены доступа).
- CRUD задач через REST API.
- Простой веб-интерфейс `/tasks` (Blade + vanilla JS): список задач,
  фильтр по статусу, создание, смена статуса, удаление, выпуск MCP-токена.
- MCP-сервер на `/api/mcp` — тот же функционал доступен агентам (Claude Code
  и другим MCP-клиентам) через набор инструментов.

## Стек

- PHP 8.2+ (проверено также на PHP 8.5), Laravel 12
- БД: SQLite локально (по умолчанию), MySQL на проде
- Laravel Sanctum — токены REST API
- Собственный JSON-RPC 2.0 MCP-эндпоинт (без внешних MCP-пакетов)

## Структура проекта

```
app/
  Models/Task.php            — модель задачи
  Models/McpToken.php        — модель отзываемых MCP-токенов
  Models/User.php            — пользователь (+ трейт HasApiTokens от Sanctum)
  Http/Controllers/
    AuthController.php       — register/login
    TaskController.php       — REST CRUD задач
    McpTokenController.php   — выпуск/отзыв MCP-токенов
    McpController.php        — MCP JSON-RPC эндпоинт (initialize/tools/list/tools/call)
  Http/Middleware/
    AuthenticateMcpToken.php — авторизация MCP-запросов по токену (?token= или Bearer)
  Services/
    McpToolRegistry.php      — определения инструментов + диспетчер вызовов
database/migrations/
  *_create_tasks_table.php
  *_create_mcp_tokens_table.php
routes/
  api.php                    — все API-роуты, включая /api/mcp
  web.php                    — редирект на /tasks, сама страница /tasks
resources/views/tasks.blade.php — веб-интерфейс списка задач
```

## Установка (локально)

```bash
composer install
cp .env.example .env
php artisan key:generate
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
php artisan serve
```

Убедитесь, что в `bootstrap/app.php` зарегистрирован `api`-роутинг и alias
`mcp.token => App\Http\Middleware\AuthenticateMcpToken::class` (см. код).

Проверка: `GET /api/health` → `{"status":"ok","time":"..."}`.

## REST API

| Метод | Путь | Описание |
|---|---|---|
| POST | `/api/auth/register` | Регистрация `{email, name, password}` → `access_token` |
| POST | `/api/auth/login` | Логин `{email, password}` → `access_token` |
| GET | `/api/tasks` | Список задач (`?status=todo\|in_progress\|done\|cancelled`) |
| POST | `/api/tasks` | Создать задачу `{title, description?, assignee_id?}` |
| GET | `/api/tasks/{id}` | Получить задачу |
| PATCH | `/api/tasks/{id}` | Обновить (title/description/status/assignee_id) |
| DELETE | `/api/tasks/{id}` | Удалить |
| POST | `/api/mcp-tokens` | Выпустить MCP-токен `{name?}` (нужен `Authorization: Bearer <access_token>`) |
| GET | `/api/mcp-tokens` | Список своих MCP-токенов |
| DELETE | `/api/mcp-tokens/{id}` | Отозвать MCP-токен |

Все роуты, кроме `health`, `auth/*` и `mcp`, требуют заголовок
`Authorization: Bearer <access_token>` (Sanctum).

## MCP-сервер

Эндпоинт: `POST /api/mcp` — JSON-RPC 2.0 (методы `initialize`, `tools/list`,
`tools/call`). Авторизация — MCP-токен (не путать с access_token Sanctum),
через query-параметр `?token=` или заголовок `Authorization: Bearer`.

Доступные инструменты:

- `list_tasks(status?)`
- `create_task(title, description?, assignee_id?)`
- `get_task(task_id)`
- `update_task_status(task_id, status)`
- `assign_task(task_id, assignee_id)`
- `delete_task(task_id)`

### Подключение через Claude Code (работает с обычным http, без SSL)

```bash
claude mcp add --transport http task-tracker http://<домен>/api/mcp \
  --header "Authorization: Bearer <MCP-токен>"
claude mcp list
```

### Подключение через веб-форму Claude (Settings → Connectors)

Требует **публичный HTTPS**-адрес (localhost/http не подходят по дизайну
формы). URL с токеном прямо в query-параметре:

```
https://<домен>/api/mcp?token=<MCP-токен>
```

OAuth-дискавери в форме в этом случае просто пропускается — сервер не
объявляет OAuth-эндпоинтов и отвечает напрямую по токену.

## Веб-интерфейс

`/tasks` — вход/регистрация, создание задач, список с фильтром по статусу,
смена статуса, удаление, кнопка «Выпустить MCP-токен» (сразу собирает
готовую ссылку для подключения).

## Деплой (пример: Спринтхост / shared-хостинг с SSH)

1. Залить код по SSH/git на сервер (вне `public_html`, приватная часть).
2. `composer install --no-dev --optimize-autoloader`
3. Document root сайта в панели → `.../public` (папка Laravel с `index.php`),
   НЕ корень проекта.
4. `.env`: `DB_CONNECTION=mysql` + данные MySQL из панели, `APP_URL=https://<домен>`.
5. `php artisan migrate --force`
6. Включить SSL (Let's Encrypt) в панели хостинга для домена — без него
   веб-форма коннектора Claude работать не будет (но Claude Code по http
   подключится и так).

## Известные ограничения / TODO

- MCP-авторизация — простой статический токен, не полноценный OAuth 2.0.
  Для «официального» подключения через веб-форму Claude нужен отдельный
  OAuth-сервер (например, Laravel Passport + discovery + dynamic client
  registration + consent UI) — в проекте не реализовано.
- Список задач общий (без разделения по командам/проектам) — упрощённая
  модель для демонстрации MCP.
