# 8 — Security check

## Reviewed

- MCP auth is a separate mechanism from Sanctum by design (`../3-specs/modules/mcp-json-rpc.md`) — an MCP token cannot be used against Sanctum-protected REST routes and vice versa.
- Token revocation is soft-delete (`revoked_at`), not physical deletion — preserves an audit trail of who issued/revoked what and when.
- `McpTokenController::destroy` returns `404` (not `403`) when a user tries to revoke a token they don't own, to avoid confirming to an attacker that a given token id exists (see `../3-specs/entities/ENT-2-MCP-TOKEN.md`).
- `.gitignore` covers `vendor/`, `.env`, `/auth.json`, `composer.phar`, `.env.*.local`, `/storage/*.key` — no secrets committed to the repo.
- Passwords hashed via Laravel's default bcrypt (through Sanctum's auth flow) — never stored or logged in plaintext.

## Known limitations (accepted for MVP, not fixed without being asked)

- No HTTPS on the production domain (`a1301727.xsph.ru` is described by the hosting owner as a "technical" subdomain without certificate support at this time) — MCP tokens and Sanctum tokens currently travel over plain HTTP in production. This is a real exposure or MITM/interception, accepted as a temporary state until a proper domain + certificate is available.
- No OAuth 2.0 server — the MCP endpoint uses a single long-lived static bearer token per client rather than short-lived, scoped, revocable-by-flow OAuth tokens. Deferred by explicit user choice (see `../../CLAUDE.md`, "known limitations").
- No rate limiting configured on `/api/auth/login` or `/api/mcp` — brute-force / abuse protection is not yet in place.
