<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Order;
use App\Models\Review;
use App\Models\ImportLog;
use App\Models\ExportLog;
use App\Services\BackupService;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }
$oldValues = $request->user()->only(['name', 'email']);
        $request->user()->save();
\App\Services\AuditService::log(
    'profile_updated',
    \App\Models\User::class,
    auth()->id(),
    $oldValues,
    $request->user()->only(['name', 'email']),
    auth()->id()
);
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        // \App\Services\AuditService::log('logout', 'App\Models\User', $user->id);
        \App\Services\AuditService::log(
    'profile_updated',
    \App\Models\User::class,
    auth()->id(),
    $oldValues,
    $request->user()->only(['name', 'email']),
    auth()->id()
);
        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Main dashboard – shows customer data for regular users
     * and admin data widgets for administrators.
     */
    public function dashboard(Request $request): View
    {
        $user = $request->user();

        // --- Customer data (existing) ---
        $recentOrders = Order::where('user_id', $user->id)
                            ->with(['orderItems.book'])
                            ->latest()
                            ->limit(5)
                            ->get();

        $recentReviews = Review::where('user_id', $user->id)
                              ->with('book')
                              ->latest()
                              ->limit(5)
                              ->get();

        $adminReviews = Review::latest()->limit(10)->get();

        $data = compact('user', 'recentOrders', 'recentReviews', 'adminReviews');

        // --- Admin widgets (only when user is admin) ---
        if ($user->isAdmin()) {
            $data = array_merge($data, $this->getAdminDashboardWidgets());
        }

        return view('dashboard', $data);
    }

    /**
     * Gather all data needed for the five admin dashboard widgets.
     */
    private function getAdminDashboardWidgets(): array
    {
        return [
            'importLogs'           => ImportLog::latest()->take(5)->get(),
            'exportLogs'           => ExportLog::latest()->take(5)->get(),
            'todayImportSuccess'   => ImportLog::whereDate('created_at', today())
                                            ->where('status', 'completed')
                                            ->count(),
            'todayImportFailed'    => ImportLog::whereDate('created_at', today())
                                            ->where('status', 'failed')
                                            ->count(),
            'todayExportSuccess'   => ExportLog::whereDate('created_at', today())
                                            ->where('status', 'completed')
                                            ->count(),
            'todayExportFailed'    => ExportLog::whereDate('created_at', today())
                                            ->where('status', 'failed')
                                            ->count(),
            'pendingJobs'          => DB::table('jobs')->count(),

            'backupStatus'         => $this->getBackupStatus(),

            'latestAudits'         => $this->getLatestAudits(),
            'criticalAuditCount'   => \OwenIt\Auditing\Models\Audit::whereIn('event', [
                                        'login', 'logout', 'password_reset', '2fa_disabled', 'role_changed'
                                     ])->whereDate('created_at', today())->count(),

            'apiTodayRequests'     => $this->getApiTodayRequests(),
            'apiTodayRateLimited'  => $this->getApiTodayRateLimited(),
            'topEndpoints'         => $this->getTopEndpoints(),

            'dbSize'               => $this->getDatabaseSize(),
            'backupDiskUsage'      => $this->getBackupDiskUsage(),
            'queueLength'          => DB::table('jobs')->count(),
            'failedJobsCount'      => DB::table('failed_jobs')->count(),
        ];
    }

    // -------- Helper methods ----------

    private function getBackupStatus(): array
    {
        try {
            $service = app(BackupService::class);
            return $service->getBackupStatusArray();
        } catch (\Exception $e) {
            return [
                'healthy'       => false,
                'status_message' => 'Unable to fetch backup status',
                'latest_date'   => 'Unknown',
                'latest_size'   => '?',
                'disk'          => '?',
                'count'         => 0,
            ];
        }
    }

    private function getLatestAudits(): array
    {
        return \OwenIt\Auditing\Models\Audit::latest()->take(10)->get()
            ->map(function ($audit) {
                $userName = 'System';
                // Try to get user name from the morph relation or from the JSON snapshot
                if ($audit->user) {
                    $userName = $audit->user->name ?? 'User #'.$audit->user_id;
                } elseif ($audit->user_id) {
                    $userName = 'User #'.$audit->user_id;
                }
                return [
                    'event'          => $audit->event,
                    'auditable_type' => class_basename($audit->auditable_type),
                    'created_at'     => $audit->created_at->diffForHumans(),
                    'user_name'      => $userName,
                ];
            })->toArray();
    }

    private function getApiTodayRequests()
    {
        if (class_exists(\App\Models\ApiRequestLog::class)) {
            return \App\Models\ApiRequestLog::whereDate('created_at', today())->count();
        }
        return 'N/A (api_request_logs table missing)';
    }

    private function getApiTodayRateLimited()
    {
        if (class_exists(\App\Models\ApiRequestLog::class)) {
            return \App\Models\ApiRequestLog::whereDate('created_at', today())
                    ->where('rate_limited', true)->count();
        }
        return 'N/A';
    }

    private function getTopEndpoints()
    {
        if (class_exists(\App\Models\ApiRequestLog::class)) {
            return \App\Models\ApiRequestLog::select('endpoint', DB::raw('count(*) as total'))
                ->whereDate('created_at', today())
                ->groupBy('endpoint')
                ->orderByDesc('total')
                ->take(5)
                ->get();
        }
        return collect();
    }

    private function getDatabaseSize(): string
    {
        try {
            $result = DB::select(
                "SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size
                 FROM information_schema.tables
                 WHERE table_schema = ?",
                [config('database.connections.mysql.database')]
            );
            return $result[0]->size . ' MB';
        } catch (\Exception $e) {
            return 'Unavailable';
        }
    }

    private function getBackupDiskUsage(): string
    {
        try {
            $backupPath = storage_path('app/' . config('backup.backup.name'));
            if (is_dir($backupPath)) {
                $totalSize = 0;
                $iterator = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($backupPath)
                );
                foreach ($iterator as $file) {
                    if ($file->isFile()) {
                        $totalSize += $file->getSize();
                    }
                }
                return round($totalSize / 1024 / 1024, 2) . ' MB';
            }
        } catch (\Exception $e) {}
        return 'N/A';
    }
}
