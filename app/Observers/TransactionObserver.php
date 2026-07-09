<?php

namespace App\Observers;

use App\Models\Transaction;

class TransactionObserver
{
    /**
     * À la création d'une transaction : on applique son effet sur le solde.
     */
    public function created(Transaction $transaction): void
    {
        $this->applyToBalance($transaction, $transaction->signedAmount());
    }

    /**
     * À la modification : on annule l'ancien effet puis on applique le nouveau
     * (gère aussi le cas où le compte a changé).
     */
    public function updated(Transaction $transaction): void
    {
        $original = $transaction->getOriginal();

        $oldSignedAmount = $original['type'] === 'income'
            ? (float) $original['amount']
            : -(float) $original['amount'];

        // Annule l'effet sur l'ancien compte (au cas où account_id aurait changé)
        if ($original['account_id'] !== $transaction->account_id) {
            $this->adjustBalance($original['account_id'], -$oldSignedAmount);
            $this->applyToBalance($transaction, $transaction->signedAmount());
        } else {
            $this->applyToBalance($transaction, $transaction->signedAmount() - $oldSignedAmount);
        }
    }

    /**
     * À la suppression : on retire son effet du solde.
     */
    public function deleted(Transaction $transaction): void
    {
        $this->applyToBalance($transaction, -$transaction->signedAmount());
    }

    private function applyToBalance(Transaction $transaction, float $delta): void
    {
        $this->adjustBalance($transaction->account_id, $delta);
    }

    private function adjustBalance(int $accountId, float $delta): void
    {
        \App\Models\Account::whereKey($accountId)->increment('balance', $delta);
    }
}