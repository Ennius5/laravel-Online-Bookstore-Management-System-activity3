<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use \OwenIt\Auditing\Auditable;

class ImportLog extends Model implements AuditableContract
{
    use HasFactory, Auditable;

    protected $fillable = [
        'user_id',
        'filename',
        'status',
        'total_rows',
        'processed_rows',
        'failed_rows',
        'errors',
    ];

    protected $casts = [
        'errors' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
