<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use Carbon\Carbon;

class GenerateDailyReport extends Command
{
    protected $signature = 'report:generate-daily';
    protected $description = 'Generate daily sales report';

    public function handle()
    {
        $today = Carbon::today();
        $orders = Order::whereDate('created_at', $today)
            ->where('status', Order::STATUS_COMPLETED)
            ->with('orderItems.book')
            ->get();

        $totalRevenue = $orders->sum('total_amount');
        $totalOrders = $orders->count();

        // Here you could store the report in the database or send an email.
        // For simplicity we log it.
        \Log::info("Daily Sales Report: {$totalOrders} completed orders, total revenue: \${$totalRevenue}");

        $this->info("Report generated: {$totalOrders} orders, \${$totalRevenue} revenue.");
    }
}
