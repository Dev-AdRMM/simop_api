<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Wallet extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'provider',
        'api_key',
        'callback_url',
        'settings',
        'active',
    ];

    protected $casts = [
        'settings' => 'array',
        'active' => 'boolean',
    ];

    // 🔹 Gera automaticamente uma API Key única ao criar
    protected static function booted()
    {
        static::creating(function ($wallet) {
            if (empty($wallet->api_key)) {
                $wallet->api_key = (string) Str::uuid();
            }
        });
    }

    // 🔹 Relação com usuário (cliente)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 🔹 Escopo: apenas carteiras ativas
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
