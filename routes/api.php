<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\McpController;
use App\Http\Controllers\McpTokenController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/health', fn () => response()->json(['status' => 'ok', 'time' => now()->toIso8601String()]));

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('tasks', TaskController::class);

    Route::post('/mcp-tokens', [McpTokenController::class, 'store']);
    Route::get('/mcp-tokens', [McpTokenController::class, 'index']);
    Route::delete('/mcp-tokens/{id}', [McpTokenController::class, 'destroy']);
});

// MCP endpoint: token-authenticated via ?token=..., not via Sanctum.
Route::post('/mcp', [McpController::class, 'handle'])->middleware('mcp.token');
