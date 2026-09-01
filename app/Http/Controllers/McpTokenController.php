<?php

namespace App\Http\Controllers;

use App\Models\McpToken;
use Illuminate\Http\Request;

class McpTokenController extends Controller
{
    public function store(Request $request)
    {
        $token = McpToken::generate($request->user()->id, $request->input('name', 'default'));

        return response()->json($token, 201);
    }

    public function index(Request $request)
    {
        return McpToken::where('user_id', $request->user()->id)->get();
    }

    public function destroy(Request $request, string $id)
    {
        $token = McpToken::where('user_id', $request->user()->id)->findOrFail($id);
        $token->update(['revoked_at' => now()]);

        return response()->json(['status' => 'revoked']);
    }
}
