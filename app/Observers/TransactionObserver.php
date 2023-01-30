<?php

namespace App\Observers;

use App\Models\Transaction;

class TransactionObserver
{
    public function updating(Transaction $transaction) {
        dd($transaction);
    }
    public function created(Transaction $transaction)
    {

    }

    public function updated(Transaction $transaction)
    {
    }

    public function deleted(Transaction $transaction)
    {
    }

    public function restored(Transaction $transaction)
    {
    }

    public function forceDeleted(Transaction $transaction)
    {
    }
}
