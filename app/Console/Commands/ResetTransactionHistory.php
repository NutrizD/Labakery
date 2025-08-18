<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ResetTransactionHistory extends Command
{
    protected $signature = 'transactions:reset-history {--days=1 : Number of days to keep (default: 1)}';
    protected $description = 'Reset transaction history by deleting old transactions while preserving sales report data';

    public function handle()
    {
        $daysToKeep = $this->option('days');
        $cutoffDate = Carbon::now()->subDays($daysToKeep);

        $this->info("Starting transaction history reset...");
        $this->info("Keeping transactions from the last {$daysToKeep} day(s)");
        $this->info("Cutoff date: {$cutoffDate->format('Y-m-d H:i:s')}");

        // Count transactions to be deleted
        $transactionsToDelete = Transaction::where('created_at', '<', $cutoffDate)->count();
        
        if ($transactionsToDelete === 0) {
            $this->info('No old transactions found to delete.');
            return;
        }

        $this->info("Found {$transactionsToDelete} old transactions to delete.");

        if (!$this->confirm('Do you want to proceed with deleting these transactions?')) {
            $this->info('Operation cancelled.');
            return;
        }

        try {
            DB::beginTransaction();

            // Delete transaction details first (due to foreign key constraints)
            $deletedDetails = TransactionDetail::whereHas('transaction', function($query) use ($cutoffDate) {
                $query->where('created_at', '<', $cutoffDate);
            })->delete();

            // Delete old transactions
            $deletedTransactions = Transaction::where('created_at', '<', $cutoffDate)->delete();

            DB::commit();

            $this->info("Successfully deleted {$deletedTransactions} transactions and {$deletedDetails} transaction details.");
            $this->info("Transaction history has been reset successfully!");

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Error occurred while resetting transaction history: " . $e->getMessage());
            return 1;
        }
    }
}
