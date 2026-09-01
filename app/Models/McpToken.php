<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class McpToken extends Model
{
    use HasUuids;

    protected $fillable = ['user_id', 'token', 'name', 'revoked_at'];
    protected $casts = ['revoked_at' => 'datetime'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function generate(int $userId, ?string $name = null): self
    {
        return static::create([
            'user_id' => $userId,
            'token' => Str::random(64),
            'name' => $name,
        ]);
    }

    public function isValid(): bool
    {
        return $this->revoked_at === null;
    }
}
