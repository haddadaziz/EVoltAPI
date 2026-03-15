<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CompleteExpiredReservations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservations:complete-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Marks active reservations as completed if their end_time has passed';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredCount = \App\Models\Reservation::where('status', 'active')
            ->where('end_time', '<=', now())
            ->update(['status' => 'completed']);

        $this->info("Completed {$expiredCount} expired reservations.");
    }
}
