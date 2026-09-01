# 10 — Observation

Production issues, warnings and informational notes discovered after deploy, feeding back into `../1-business-tasks/observation/` for the next planning cycle.

- `errors/` — bugs, incidents, broken flows.
- `warnings/` — things that work but shouldn't be relied on as-is (see `../8-security-check/README.md` for the current list: no HTTPS in production, no OAuth 2.0, no rate limiting).
- `infos/` — neutral notes worth keeping (e.g. hosting quirks, panel settings that mattered).

Currently empty — the project has just been deployed and no observation cycle has run yet. The known limitations already identified during build (HTTPS, OAuth, rate limiting, flat task list with no project/team separation) are tracked in `../8-security-check/README.md` and the repo root `README.md` rather than duplicated here; they should be moved into `warnings/` here once the project has an actual observation/monitoring cadence.
