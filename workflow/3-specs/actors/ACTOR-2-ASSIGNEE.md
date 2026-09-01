# ACTOR-2-ASSIGNEE-IN-TASKTRACKER

RA-код: `RA-TASKS-ACTOR-ASSIGNEE`

## Кто это

Не отдельная роль в БД, а проекция `ACTOR-1-USER`: пользователь, на которого назначена конкретная задача (`tasks.assignee_id`). Любой `USER` может стать `ASSIGNEE` для любой задачи — модель без ролей/прав доступа по проектам.

## Что меняется в модели

- Задача получает `assignee_id`, ссылку на `users.id`.
- Снятие назначения — `assignee_id = null`.

## Связанные use case

`../use-cases/UC-4-*.md` (назначение задачи).
