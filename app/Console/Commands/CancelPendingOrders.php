<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use Carbon\Carbon;

class CancelPendingOrders extends Command
{
    protected $signature = 'order:cleanup-pending';
    protected $description = 'Cancel pending orders older than 24 hours';

    public function handle()
    {
        $cutoff = Carbon::now()->subHours(24);
        $orders = Order::where('status', Order::STATUS_PENDING)
            ->where('created_at', '<', $cutoff)
            ->get();

        $count = $orders->count();
        foreach ($orders as $order) {
            $order->status = Order::STATUS_CANCELLED;
            $order->save();
        }

        $this->info("Cancelled {$count} pending orders.");
        \Log::info("Cancelled {$count} pending orders older than 24 hours.");
    }
}
