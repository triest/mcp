# 9 — Deploy

## Local

OSPanel, `J:\OSPanel\home\task-treker.local`, SQLite, `php artisan serve` or OSPanel's own Apache/nginx virtual host for `task-treker.local`.

## Production — Sprinthost (shared hosting)

- Host: `a1301727.xsph.ru` (IP `141.8.192.25`), SSH access as `a1301727`.
- PHP 8.5.0 (CentOS 7 base image), no composer preinstalled — installed to `$HOME/bin` via `composer-setup.php`.
- MySQL: database `a1301727_tracker`, user `a1301727_tracker`.
- Deployed via `git` + manual file sync over SSH (no CI/CD pipeline yet).
- App server: Passenger. Required the hosting panel's "application type" for the site/subpath to be set to PHP (it was initially misconfigured as Node.js, causing a `Cannot lstat(.../server.js)` error) — resolved via the hosting control panel.
- `.env` configured for MySQL in production, SQLite locally.

## Known deploy limitations

- No HTTPS/TLS certificate on `a1301727.xsph.ru` yet (see `../8-security-check/README.md`) — this blocks using the standard claude.ai web "Add custom connector" flow, which requires an `https://` URL. Current workaround: connect via Claude Code CLI (`claude mcp add --transport http ...`), which works over plain HTTP.
- No automated deploy pipeline — deploys are manual SSH sessions walked through interactively.
