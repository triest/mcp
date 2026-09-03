# Лог разговора: создание Task Tracker MCP-сервера

Дата: 2026-09-01 — 2026-09-03

## 1. Постановка задачи
Изначально пользователь прислал файлы (`ra_server_3.rtf`, текстовый файл) с IP, root-логином и паролем от чужого сервера, а также ссылками на репозиторий `queue-assignment` (Laravel-сервис электронной очереди с MCP) и Яндекс.Диск с видео-инструкцией. Я отказался использовать чужие SSH-доступы и токен коннектора — это выглядело как доступ к системе, не принадлежащей пользователю.

Уточнение задачи: пользователь хочет **свой аналог** проекта — таск-трекер вместо очереди, с собственным MCP-сервером, поднятым в своём окружении.

## 2. Первая попытка — Python/FastAPI
Собрал рабочий прототип на FastAPI + SQLite + официальный MCP SDK (`mcp`), с REST API (auth, tasks CRUD, mcp-tokens) и MCP-инструментами `list_tasks`, `create_task`, `get_task`, `update_task_status`, `assign_task`, `delete_task`. Проверил локально (health-check, регистрация, JWT, bcrypt) — работало.

Затем пользователь уточнил: нужно **на Laravel**, там, где он уже начал (`J:\OSPanel\home\task-treker.local` на его компьютере через OSPanel).

## 3. Переход на Laravel
- Подключились к его компьютеру через мост (device_bash/device_list_dir/device_commit_files).
- Помог создать Laravel 12 проект через `composer create-project` (пользователь сам выполнял в консоли OSPanel — у облачной песочницы и моста к компьютеру нет доступа к Packagist/произвольным хостам, egress ограничен allowlist'ом).
- Столкнулись с GitHub API rate-limit при composer install — решили через read-only Personal Access Token.
- Написал и записал в проект кастомные файлы:
  - `app/Models/Task.php`, `app/Models/McpToken.php`
  - `app/Http/Controllers/AuthController.php`, `TaskController.php`, `McpTokenController.php`, `McpController.php`
  - `app/Http/Middleware/AuthenticateMcpToken.php`
  - `app/Services/McpToolRegistry.php` (определения MCP-инструментов + диспетчер)
  - `database/migrations/*_create_tasks_table.php`, `*_create_mcp_tokens_table.php`
  - `routes/api.php`, `bootstrap/app.php` (регистрация api-роутинга и alias `mcp.token`)
  - Подключили Laravel Sanctum для JWT-подобных токенов REST API.
  - `app/Models/User.php` — добавлен трейт `HasApiTokens`.
- MCP-эндпоint реализован вручную как JSON-RPC 2.0 поверх HTTP (`POST /api/mcp`), без внешних MCP-пакетов — методы `initialize`, `tools/list`, `tools/call`. Авторизация — статический токен через `?token=` или `Authorization: Bearer`.

## 4. Простой UI
Добавил `/tasks` — одностраничный Blade-интерфейс (логин/регистрация, список задач, фильтр по статусу, создание, смена статуса, удаление, кнопка «Выпустить MCP-токен» с готовой ссылкой). Работает поверх того же REST API, что и MCP.

## 5. Подключение MCP-коннектора — веб-форма Claude
Попытки подключить через `Settings → Connectors → Add custom connector`:
- Ошибка «Couldn't register with sign-in service» — форма пытается сделать OAuth dynamic client registration, наш сервер OAuth не реализует.
- Ошибка «Couldn't reach this address» — домен `task-treker.local` существует только в hosts-файле пользователя, недоступен из облака Anthropic.
- Ошибка «URL must start with https» — форма требует HTTPS даже для localhost.
- Обсудили два варианта: (A) использовать Claude Code CLI с `--header` вместо OAuth/веб-формы — быстро; (B) реализовать полноценный OAuth 2.0 сервер (Laravel Passport + discovery + dynamic client registration + consent UI) — решили пока не делать, дорого по времени, да и не снимает требование публичного HTTPS.

## 6. Деплой на Спринтхост
- Пользователь предоставил доступ: SSH `a1301727@a1301727.xsph.ru`, порт 22, пароль; MySQL `a1301727_tracker` / пользователь `a1301727_tracker` / пароль `test_data_123`.
- Ни у облачного контейнера, ни у моста к компьютеру нет прямого сетевого доступа к произвольным SSH-хостам (egress allowlist блокирует) — пользователь выполняет команды сам по моим инструкциям.
- Возникла ошибка Passenger: искал `server.js` в `mcp-server` — панель хостинга решила, что это Node.js-приложение вместо PHP. Обсуждали, как поменять тип приложения в панели; в итоге сайт заработал.
- В процессе также добавили нужные строки в `.gitignore` (хотя дефолтный Laravel `.gitignore` уже покрывал `vendor`, `.env` и т.д.).
- Сайт в итоге заработал: `http://a1301727.xsph.ru/api/health` отвечает `{"status":"ok",...}`.
- HTTPS пока не настроен («ошибка установки защищённого соединения») — домен технический (поддомен xsph.ru), пользователь пока не может поставить туда сертификат.

## 7. Подключение через Claude Code (вместо веб-формы)
Раз веб-форма требует HTTPS, а сертификата пока нет — договорились подключаться через **Claude Code** (CLI), который умеет работать по обычному `http://`:

```bash
claude mcp add --transport http task-tracker http://a1301727.xsph.ru/api/mcp --header "Authorization: Bearer <MCP-токен>"
```

- Пользователь установил MCP-сервер в конфиг (`Added HTTP MCP server task-tracker ... File modified: C:\Users\user\.claude.json`).
- При первой попытке использовать (`claude "Покажи список задач..."`) — Claude Code сообщил `Not logged in · Please run /login`.
- Дал инструкцию выполнить `claude /login` для авторизации через браузер (результат этого шага в диалоге явно не подтверждён — пользователь переключился на другие задачи).

## 8. Документация проекта
По просьбе пользователя описал весь проект в трёх файлах в корне репозитория:
- **`README.md`** — возможности, стек, структура проекта, установка, таблица REST-эндпоинтов, раздел про MCP-сервер (инструменты, оба способа подключения — Claude Code CLI и веб-форма), веб-интерфейс, деплой, известные ограничения.
- **`CLAUDE.md`** — инструкции для Claude Code при работе с репозиторием: архитектура (McpToolRegistry — единый источник правды по MCP-инструментам, McpController — тонкий роутер, AuthenticateMcpToken — отдельный от Sanctum механизм), правила изменений (новая миграция на каждое изменение схемы, не редактировать уже применённые миграции), команды для локальной проверки.
- **`AGENTS.md`** — обобщённая версия для любых AI-агентов: обзор, стек, шаги установки и проверки, конвенции (новые MCP-инструменты только через McpToolRegistry, секреты никогда не коммитятся), известные ограничения.

## 9. Спецификация в RA-нотации
Пользователь загрузил образец (`inventorynamingspec.md`) и Claude skill `spec-builder-ra` (строгая нотация именования вида `RA-<МОДУЛЬ>-АКТОР-ДЕЙСТВИЕ-НА-ENTITY-HTTP`, используемая в референсном модуле `INVENTORY`). По этому шаблону составил `tasks-naming-spec.md` в корне проекта: акторы (`USER`, `ASSIGNEE`, `MCP_CLIENT`, `GUEST`), сущности (`Task`, `McpToken`) с полями и связями, состояния и переходы, доменные события, таблицы use case отдельно для REST (реальные HTTP-коды) и для MCP (почти всегда `200`, доменные ошибки — внутри тела ответа JSON-RPC), инварианты, диаграмма жизненного цикла в Mermaid.

## 10. Папка `workflow/` — процесс разработки как SDLC-пайплайн
Пользователь попросил оформить процесс разработки в виде папок `workflow/` и `docs/`, по образцу структуры другого его проекта.

- **Первая версия** — по образцу `reports_msrv` (реальный Laravel-микросервис пользователя, с вложенными по-модульно папками `2-tasks/TaskTracker/`, `3-specs/TaskTracker/` и т.д., плюс отдельная стадия `1-prd/`). Собрал полную структуру и наполнил файлы.
- **Пользователь уточнил** — использовать вместо этого более чистый эталон `regagro-workflow-demo` с Desktop. Пересобрал структуру заново:
  - Удалил старую вложенную структуру (`1-prd/`, папки `TaskTracker/` внутри стадий, `reference/`).
  - Создал плоскую структуру из 11 нумерованных стадий: `0-vibes → 1-business-tasks → 2-tasks → 3-specs → 4-design → 5-tasks → 6-results → 7-eval → 8-security-check → 9-deploy → 10-observation` (замыкается в цикл — `10-observation` возвращает наблюдения в `1-business-tasks/observation/`).
  - Наполнил каждую стадию: PRD (`0-vibes/prd/prd.md`, требования R1–R8), бизнес-задачи (`PT-1`, `PT-2`), бэклог (`TT-1`…`TT-7`), спецификации (`3-specs/actors|entities|events|modules|use-cases/`, коды по конвенции `UC-{n}-ACTOR-{n}-EVT-{n}-ENT-{n}-RESULT-IN-MODULE`), результаты, деплой, security-чеклист, наблюдения.
  - Явно задокументировал два отклонения от эталона: нет `figma/react/vue` в `4-design/` и нет Storybook-автотестов в `7-eval/auto/`, потому что весь UI — одна Blade-страница без компонентной библиотеки.
  - Корневой `workflow/CLAUDE.md` объясняет, что пайплайн собран ретроспективно (код появился раньше документации, см. `0-vibes/raw/`).

## 11. Инструкция по подключению коннектора в README
Добавил в `README.md` раздел «Как получить коннектор для продакшена (`http://a1301727.xsph.ru`)»: вход/регистрация на `/tasks`, выпуск MCP-токена кнопкой в UI, подключение через Claude Code CLI (так как HTTPS на домене пока нет), проверка (`claude mcp list`), пример использования, и что изменится, когда появится SSL. Затем по просьбе пользователя добавил туда же ссылку на видео-инструкцию: `https://disk.yandex.ru/d/o-JkjbatYfe-uA`.

## Текущий статус на момент лога
- Backend работает и локально (OSPanel, SQLite), и на Спринтхосте (HTTP, MySQL).
- MCP-сервер подключается через Claude Code CLI; веб-форма Claude пока недоступна из-за отсутствия HTTPS на техническом поддомене.
- Документация полная: `README.md`, `CLAUDE.md`, `AGENTS.md`, `tasks-naming-spec.md`, `workflow/` (эталонная структура `regagro-workflow-demo`).
- Открыто/не подтверждено: успешна ли авторизация `claude /login` и реальный round-trip запроса к MCP через CLI против прод-сервера.

## Ключевые данные проекта (для справки)
- Локально: `J:\OSPanel\home\task-treker.local` (Laravel 12, SQLite)
- Прод: `a1301727.xsph.ru` (Спринтхост, MySQL `a1301727_tracker`)
- MCP endpoint: `POST /api/mcp` (JSON-RPC 2.0), авторизация через `?token=` или `Authorization: Bearer`
- REST API: `/api/auth/register`, `/api/auth/login`, `/api/tasks` (CRUD), `/api/mcp-tokens`
- UI: `/tasks`
- MCP-инструменты: `list_tasks`, `create_task`, `get_task`, `update_task_status`, `assign_task`, `delete_task`
- Видео-инструкция по подключению: https://disk.yandex.ru/d/o-JkjbatYfe-uA
