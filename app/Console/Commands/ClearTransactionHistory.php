<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;

class ClearTransactionHistory extends Command
{
    protected $signature = 'transactions:clear-all';
    protected $description = 'Clear all transaction history (use with caution)';

    public function handle()
    {
        $totalTransactions = Transaction::count();
        $totalDetails = TransactionDetail::count();

        if ($totalTransactions === 0) {
            $this->info('No transactions found in database.');
            return;
        }

        $this->warn("WARNING: This will delete ALL transaction history!");
        $this->info("Found {$totalTransactions} transactions and {$totalDetails} transaction details to delete.");

        if (!$this->confirm('Are you sure you want to delete ALL transaction history? This action cannot be undone!')) {
            $this->info('Operation cancelled.');
            return;
        }

        if (!$this->confirm('Final confirmation: Delete ALL transaction history?')) {
            $this->info('Operation cancelled.');
            return;
        }

        try {
            DB::beginTransaction();

            // Delete all transaction details first
            $deletedDetails = TransactionDetail::truncate();

            // Delete all transactions
            $deletedTransactions = Transaction::truncate();

            DB::commit();

            $this->info("Successfully cleared all transaction history!");
            $this->info("Deleted {$totalTransactions} transactions and {$totalDetails} transaction details.");

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Error occurred while clearing transaction history: " . $e->getMessage());
            return 1;
        }
    }
}
