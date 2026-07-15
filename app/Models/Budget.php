<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Budget extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'amount_limit',
        'month',
    ];

    protected $casts = [
        'amount_limit' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Montant déjà dépensé sur ce budget (calculé depuis les transactions du mois).
     */
    public function getSpentAttribute(): float
{
    $year = substr($this->month, 0, 4);
    $monthNumber = substr($this->month, 5, 2);

    return $this->category->transactions()
        ->where('type', 'expense')
        ->whereYear('date', $year)
        ->whereMonth('date', $monthNumber)
        ->sum('amount');
}
}