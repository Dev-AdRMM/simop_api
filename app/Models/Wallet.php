<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Wallet extends Model
{
    use HasFactory, SoftDeletes;

    public $incrementing = false; // 🔹 Importante: desativa auto increment
    protected $keyType = 'string'; // 🔹 O tipo da chave primária é string

    protected $fillable = [
        'user_id',
        'name',
        'provider',
        'api_key',
        'callback_url',
        'settings',
        'balance',
        'status',
        'active',
        'suspension_reason',
    ];

    protected $casts = [
        'settings' => 'array',
        'active' => 'boolean',
        'balance' => 'decimal:2',
    ];

    # Gera automaticamente uma API Key única ao criar
    protected static function booted()
    {
        static::creating(function ($wallet) {
            if (empty($wallet->id)) {
                $wallet->id = (string) Str::uuid(); // ✅ Gera UUID automaticamente
            }

            if (empty($wallet->api_key)) {
                $wallet->api_key = (string) Str::uuid();
            }
        });
    }

    # Relação com o cliente dono da carteira
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    # Escopo: carteiras operacionais
    public function scopeActive($query)
    {
        return $query->where('active', true)->where('status', 'active');
    }

    # Método: suspender carteira
    public function suspend(string $reason = 'Suspensão administrativa'): void
    {
        $this->update([
            'status' => 'suspended',
            'suspension_reason' => $reason,
        ]);
    }

    #Método: reativar carteira
    public function activate(): void
    {
        $this->update([
            'status' => 'active',
            'suspension_reason' => null,
        ]);
    }
}
