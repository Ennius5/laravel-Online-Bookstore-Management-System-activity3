<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use OwenIt\Auditing\Models\Audit;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        $query = Audit::with('user');

        // Filtering
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }
        if ($request->filled('auditable_type')) {
            $query->where('auditable_type', 'like', '%'.$request->auditable_type.'%');
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $audits = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get distinct events and auditable types for filter dropdowns
        $events = Audit::distinct()->pluck('event');
        $types = Audit::distinct()->pluck('auditable_type');

        return view('admin.audit.index', compact('audits', 'events', 'types'));
    }

    public function show($id)
    {
        $audit = Audit::findOrFail($id);
        return view('admin.audit.show', compact('audit'));
    }
}
