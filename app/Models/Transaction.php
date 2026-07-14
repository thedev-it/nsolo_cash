<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id',
        'category_id',
        'type',
        'amount',
        'description',
        'date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function savingGoal(): \Illuminate\Database\Eloquent\Relations\BelongsTo
{
    return $this->belongsTo(SavingsGoal::class);
}

    /**
     * Montant signé : positif si revenu, négatif si dépense.
     * Utilisé pour mettre à jour le solde du compte (balance).
     */
    public function signedAmount(): float
    {
        return $this->type === 'income'
            ? (float) $this->amount
            : -(float) $this->amount;
    }
}