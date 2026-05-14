<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MvBestsellerStat extends Model
{
    protected $table = 'mv_bestseller_stats';

    protected $fillable = [
        'category_id',
        'total_books',
        'avg_price',
        'total_inventory',
        'bestseller_count',
        'latest_publication',
        'last_refreshed_at',
    ];

    protected $casts = [
        'latest_publication' => 'date',
        'last_refreshed_at'  => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
