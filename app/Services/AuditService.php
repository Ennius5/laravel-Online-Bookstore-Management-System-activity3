<?php

namespace App\Services;

use App\Models\Audit;

class AuditService
{
public static function log($event, $auditableType, $auditableId, $oldValues = [], $newValues = [], $userId = null)
{
    $resolvedUserId = $userId ?? auth()->id();

    Audit::create([
        'user_type'      => $resolvedUserId ? \App\Models\User::class : null,
        'user_id'        => $resolvedUserId,
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
