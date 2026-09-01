<?php

namespace App\Http\Middleware;

use App\Models\McpToken;
use Closure;
use Illuminate\Http\Request;

class AuthenticateMcpToken
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->query('token') ?? $request->bearerToken();

        if (! $token) {
            return response()->json([
                'jsonrpc' => '2.0',
                'error' => ['code' => -32001, 'message' => 'Missing MCP token. Pass ?token=<your-mcp-token>.'],
                'id' => null,
            ], 401);
        }

        $mcpToken = McpToken::where('token', $token)->first();

        if (! $mcpToken || ! $mcpToken->isValid()) {
            return response()->json([
                'jsonrpc' => '2.0',
                'error' => ['code' => -32001, 'message' => 'Invalid or revoked MCP token.'],
                'id' => null,
            ], 401);
        }

        $request->attributes->set('mcp_user_id', $mcpToken->user_id);

        return $next($request);
    }
}
