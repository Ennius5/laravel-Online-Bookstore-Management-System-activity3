<?php

namespace App\Services;

use OwenIt\Auditing\Models\Audit;

class AuditService
{
    public static function log($event, $auditableType, $auditableId, $oldValues = [], $newValues = [])
    {
        Audit::create([
            'user_type' => auth()->check() ? get_class(auth()->user()) : null,
            'user_id' => auth()->id(),
            'event' => $event,
            'auditable_type' => $auditableType,
            'auditable_id' => $auditableId,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'url' => request()->fullUrl(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'tags' => null,
        ]);
    }
}
