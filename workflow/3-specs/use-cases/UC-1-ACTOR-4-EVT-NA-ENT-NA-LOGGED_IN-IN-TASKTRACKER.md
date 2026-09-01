# UC-1-ACTOR-4-EVT-NA-ENT-NA-LOGGED_IN-IN-TASKTRACKER

RA-код: `RA-TASKS-GUEST-LOGIN-НА-USER-200`

## Актор

`ACTOR-4-GUEST` → `ACTOR-1-USER`.

## Действие

`POST /api/auth/login`.

## Предусловие

Email существует, пароль верен.

## Результат

Выдан Sanctum `access_token`.

## Ответ

`200`.

## Отказы

| Код | Причина |
|---|---|
| `401` | неверные учётные данные |
