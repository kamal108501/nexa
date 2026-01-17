<?php

namespace App\Console\Commands;

use App\Models\OptionContract;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DeleteExpiredOptionContracts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'options:delete-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Soft delete option contracts that have passed their expiry date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for expired option contracts...');

        // Find option contracts where expiry_date is before today and not already deleted
        $expiredContracts = OptionContract::where('expiry_date', '<', now()->toDateString())
            ->whereNull('deleted_at')
            ->get();

        if ($expiredContracts->isEmpty()) {
            $this->info('No expired option contracts found.');
            Log::info('No expired option contracts found.');
            return 0;
        }

        $count = $expiredContracts->count();
        $this->info("Found {$count} expired option contracts. Soft deleting...");

        // Soft delete expired contracts
        foreach ($expiredContracts as $contract) {
            $contract->delete();
            $this->line("Deleted: {$contract->contract_code} - Expired: {$contract->expiry_date->format('Y-m-d')}");
        }

        $this->info("Successfully soft deleted {$count} expired option contracts.");
        Log::info("Soft deleted {$count} expired option contracts via cron job.");

        return 0;
    }
}
