<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'price',
        'max_loads_per_month',
        'max_loads_per_week',
        'max_carriers',
        'max_dispatchers',
        'max_employees',
        'max_drivers',
        'is_trial',
        'trial_days',
        'active',
        'is_custom',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_trial' => 'boolean',
        'active' => 'boolean',
        'is_custom' => 'boolean',
    ];

    /**
     * Relacionamento com usuário (para planos customizados)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Scope para buscar apenas planos globais
     */
    public function scopeGlobal($query)
    {
        return $query->whereNull('user_id');
    }

    /**
     * Scope para buscar planos customizados
     */
    public function scopeCustom($query)
    {
        return $query->whereNotNull('user_id');
    }

    /**
     * Scope para buscar planos de um usuário específico
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Verifica se é um plano customizado
     */
    public function isCustom(): bool
    {
        return $this->user_id !== null || $this->is_custom;
    }

    /**
     * Verifica se é um plano global
     */
    public function isGlobal(): bool
    {
        return $this->user_id === null && !$this->is_custom;
    }
}
