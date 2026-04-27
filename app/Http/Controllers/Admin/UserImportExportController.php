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
        return view('admin.users.import-export');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,csv', 'max:51200'],
        ]);

        Excel::import(new UsersImport(), $request->file('file'));

        return back()->with('success', 'User import started in the background!');
    }

    public function export(Request $request)
    {
        $redactPII = $request->boolean('redact_pii');
        $filename  = 'users_export_' . now()->format('Y-m-d_His') . '.xlsx';

        return Excel::download(new UsersExport($redactPII), $filename);
    }
}
