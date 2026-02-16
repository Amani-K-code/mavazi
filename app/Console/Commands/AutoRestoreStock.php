<?php

namespace App\Console\Commands;

use App\Models\Reservation;
use App\Models\Inventory;
use Illuminate\Console\Command;

class AutoRestoreStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:auto-restore-stock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expired = Reservation::where('status', 'pending')
            ->where('expires_at', '<', now())
            ->get();


        foreach ($expired as $res) {
            $item = $res->inventory;

            //Move stock BACK
            $item->increment('stock_quantity', $res->quantity);
            $item->decrement('reserved_quantity', $res->quantity);

            $res->update(['status' => 'restored']);
        }
    }
}
