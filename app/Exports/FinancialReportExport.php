<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;

class FinancialReportExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    const TAX_RATE = 0.12; // 12% VAT

    protected array $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = Order::query()
            ->with(['orderItems'])
            ->whereNotIn('status', ['cancelled']);

        if (!empty($this->filters['date_from'])) {
            $query->whereDate('created_at', '>=', $this->filters['date_from']);
        }
        if (!empty($this->filters['date_to'])) {
            $query->whereDate('created_at', '<=', $this->filters['date_to']);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'Order ID', 'Date', 'Status',
            'Subtotal', 'Tax (12%)', 'Total'
        ];
    }

    public function map($order): array
    {
        $subtotal = $order->total_amount;
        $tax      = $subtotal * self::TAX_RATE;
        $total    = $subtotal + $tax;

        return [
            $order->id,
            $order->created_at->format('Y-m-d'),
            ucfirst($order->status),
            number_format($subtotal, 2),
            number_format($tax, 2),
            number_format($total, 2),
        ];
    }
}
