<?php

namespace App\Http\Controllers;

use App\Services\McpToolRegistry;
use Illuminate\Http\Request;

class McpController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->json()->all();
        $id = $payload['id'] ?? null;
        $method = $payload['method'] ?? null;
        $params = $payload['params'] ?? [];

        try {
            $result = match ($method) {
                'initialize' => $this->initialize(),
                'tools/list' => ['tools' => McpToolRegistry::definitions()],
                'tools/call' => $this->toolsCall($request, $params),
                default => throw new \InvalidArgumentException("Unknown method: {$method}"),
            };

            return response()->json(['jsonrpc' => '2.0', 'id' => $id, 'result' => $result]);
        } catch (\Throwable $e) {
            return response()->json([
                'jsonrpc' => '2.0',
                'id' => $id,
                'error' => ['code' => -32000, 'message' => $e->getMessage()],
            ], 200);
        }
    }

    private function initialize(): array
    {
        return [
            'protocolVersion' => '2024-11-05',
            'serverInfo' => ['name' => 'task-tracker', 'version' => '1.0.0'],
            'capabilities' => ['tools' => ['listChanged' => false]],
        ];
    }

    private function toolsCall(Request $request, array $params): array
    {
        $name = $params['name'] ?? '';
        $args = $params['arguments'] ?? [];
        $userId = $request->attributes->get('mcp_user_id');

        $data = McpToolRegistry::call($name, $args, $userId);

        return [
            'content' => [
                ['type' => 'text', 'text' => json_encode($data, JSON_UNESCAPED_UNICODE)],
            ],
        ];
    }
}
