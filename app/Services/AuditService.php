<?php

namespace App\Services;

use App\Models\Audit;

class AuditService
{
public static function log($event, $auditableType, $auditableId, $oldValues = [], $newValues = [])
{
    // ✅ Use passed $auditableId for user_id when auditing User events
    // Fall back to auth()->id() for other model events
    $userId = null;
    if ($auditableType === \App\Models\User::class || $auditableType === 'App\Models\User') {
        $userId = $auditableId;
    } else {
        $userId = auth()->id();
    }

    Audit::create([
        'user_type'      => $userId ? \App\Models\User::class : null,
        'user_id'        => $userId,
        'event'          => $event,
        'auditable_type' => $auditableType,
        'auditable_id'   => $auditableId,
        'old_values'     => $oldValues,
        'new_values'     => $newValues,
        'url'            => request()->fullUrl(),
        'ip_address'     => request()->ip(),
        'user_agent'     => request()->userAgent(),
        'tags'           => null,
    ]);
}
}
