<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;

class ListTransactions extends Command
{
    protected $signature = 'transactions:list';
    protected $description = 'List all transactions in the database';

    public function handle()
    {
        $transactions = Transaction::with('user')->get();
        if ($transactions->isEmpty()) {
            $this->info('No transactions found in database.');
            return;
        }
        
        $this->info('Transactions in database:');
        $this->table(
            ['ID', 'Invoice', 'User', 'Total Amount', 'Total Paid', 'Change'],
            $transactions->map(function ($transaction) {
                return [
                    $transaction->id,
                    $transaction->invoice_number,
                    $transaction->user->name,
                    'Rp' . number_format($transaction->total_amount),
                    'Rp' . number_format($transaction->total_paid),
                    'Rp' . number_format($transaction->change_amount)
                ];
            })->toArray()
        );
    }
}
