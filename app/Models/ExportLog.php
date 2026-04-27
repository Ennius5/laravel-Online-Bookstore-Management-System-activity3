<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExportLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'format',
        'filters',
        'status',
        'download_link',
    ];

    protected $casts = [
        'filters' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
