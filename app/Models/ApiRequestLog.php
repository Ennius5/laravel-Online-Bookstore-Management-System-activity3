<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiRequestLog extends Model
{
    protected $fillable = ['user_id', 'endpoint', 'status_code', 'rate_limited'];
}
