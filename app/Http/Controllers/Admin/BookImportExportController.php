<?php

namespace App\Http\Controllers\Admin;

use App\Exports\BooksExport;
use App\Http\Controllers\Controller;
use App\Imports\BooksImport;
use App\Models\ImportLog;
use App\Models\ExportLog;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class BookImportExportController extends Controller
{
    // Show the import/export page
    public function index()
    {
        $importLogs = ImportLog::where('user_id', auth()->id())
            ->latest()->take(10)->get();
        $exportLogs = ExportLog::where('user_id', auth()->id())
            ->latest()->take(10)->get();

        return view('books.import-export', compact('importLogs', 'exportLogs'));
    }

    // Handle file upload & import
public function import(Request $request)
{
    $request->validate([
        'file'               => ['required', 'file', 'mimes:xlsx,csv', 'max:51200'],
        'duplicate_handling' => ['required', 'in:update,skip'],
    ]);

    $log = ImportLog::create([
        'user_id'  => auth()->id(),
        'filename' => $request->file('file')->getClientOriginalName(),
        'status'   => 'pending',
    ]);

    Excel::import(
        new BooksImport($log->id, $request->duplicate_handling),
        $request->file('file')
    );

    return back()->with('success', 'Import queued! Check the log below for progress.');
}

public function export(Request $request)
{
    $filters = $request->only([
        'category_id',
        'min_price',
        'max_price',
        'in_stock',
        'date_from',  // ✅ new
        'date_to',    // ✅ new
    ]);

    $selectedColumns = $request->input('columns', []);  // ✅ new

    $log = ExportLog::create([
        'user_id' => auth()->id(),
        'format'  => $request->input('format', 'xlsx'),
        'filters' => $filters,
        'status'  => 'pending',
    ]);

    $filename = 'books_export_' . now()->format('Y-m-d_His') . '.' . $log->format;

    return Excel::download(
        new BooksExport($filters, $selectedColumns),
        $filename
    );
}

    // Download blank import template
    public function downloadTemplate()
    {
        $headers = ['ISBN', 'Title', 'Author', 'Price', 'Stock', 'Category', 'Description'];

        return Excel::download(new class($headers) implements \Maatwebsite\Excel\Concerns\FromArray, \Maatwebsite\Excel\Concerns\WithHeadings {
            protected array $headings;

            public function __construct(array $headings)
            {
                $this->headings = $headings;
            }

            public function array(): array
            {
                // One empty example row so users know the format
                return [
                    ['978-3-16-148410-0', 'Example Book Title', 'Author Name', '19.99', '100', 'Fiction', 'A short description']
                ];
            }

            public function headings(): array
            {
                return $this->headings;
            }
        }, 'books_import_template.xlsx');
    }
}
