# 6 — Results

What shipped, and where it runs.

## Environments

| Environment | URL / path | Status |
|---|---|---|
| Local (OSPanel) | `http://task-treker.local` | working (SQLite) |
| Production (Sprinthost) | `http://a1301727.xsph.ru` | working over HTTP (`/api/health` → `{"status":"ok"}`), MySQL `a1301727_tracker` |

## What works

- Full REST CRUD for tasks, auth (register/login via Sanctum), MCP-token issuance/revocation.
- MCP JSON-RPC server (`POST /api/mcp`) — `initialize`, `tools/list`, `tools/call` with 6 tools.
- Claude Code CLI successfully registered as an MCP client against the production server (`claude mcp add --transport http task-tracker http://a1301727.xsph.ru/api/mcp --header "Authorization: Bearer ..."`).
- Web UI at `/tasks` for manual task management without any MCP client.

## What does not work / is deferred

- The web "Add custom connector" flow (`claude.ai` Settings → Connectors) cannot be used yet — it requires HTTPS and attempts OAuth dynamic client registration, neither of which this server supports (see `../7-eval/README.md` and `../9-deploy/README.md`).
- No per-project/team separation — task list is flat and shared across all users (documented as a known limitation in the repo root `README.md`).
