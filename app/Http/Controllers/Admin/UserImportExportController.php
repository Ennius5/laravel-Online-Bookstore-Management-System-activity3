<?php

namespace App\Http\Controllers\Admin;

use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Imports\UsersImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class UserImportExportController extends Controller
{
    public function index()
    {
            $importLogs = \App\Models\ImportLog::where('user_id', auth()->id())
        ->latest()->take(10)->get();
    $exportLogs = \App\Models\ExportLog::where('user_id', auth()->id())
        ->latest()->take(10)->get();
        return view('users.import-export',compact('importLogs', 'exportLogs'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,csv', 'max:51200'],
        ]);

        Excel::import(new UsersImport(), $request->file('file'));
\App\Services\AuditService::log(
    'users_imported',
    \App\Models\User::class,
    null,
    [],
    ['filename' => $request->file('file')->getClientOriginalName()],
    auth()->id()
);
        return back()->with('success', 'User import started in the background!');
    }

    public function export(Request $request)
    {
        $redactPII = $request->boolean('redact_pii');
        $filename  = 'users_export_' . now()->format('Y-m-d_His') . '.xlsx';
\App\Services\AuditService::log(
    'users_exported',
    \App\Models\User::class,
    null,
    [],
    ['redact_pii' => $redactPII],
    auth()->id()
);

return Excel::download(new UsersExport($redactPII), $filename);
        return Excel::download(new UsersExport($redactPII), $filename);
    }
}
