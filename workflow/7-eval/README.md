# 7 — Eval

Verification that what shipped (`../6-results/`) actually satisfies the specs (`../3-specs/`).

## Deviation from the reference template

The `regagro-workflow-demo` template expects an `auto/` subfolder here with Storybook-driven automated screenshot/interaction tests against the component library in `../4-design/react|vue/`. This project has no component library and no Storybook (see `../4-design/README.md`), so there is no `auto/` folder.

## What was actually done instead — manual verification

- `curl` smoke tests against both environments for every REST endpoint (health, register, login, tasks CRUD, mcp-tokens issue/revoke) — expected HTTP codes cross-checked against `../3-specs/modules/rest-api.md`.
- `curl`/Claude Code CLI round-trip against `POST /api/mcp` — `tools/list` returns all 6 tool definitions; `tools/call` for `create_task`/`list_tasks` verified against `../3-specs/modules/mcp-json-rpc.md`.
- `php artisan route:list` to confirm all routes in `../3-specs/modules/rest-api.md` are actually registered.
- `php -l` on every changed PHP file (syntax check) before each deploy.

## Known gaps

- No automated test suite (PHPUnit/Pest) yet — all verification above is manual. Adding one is a natural next step if the project grows past MVP.
