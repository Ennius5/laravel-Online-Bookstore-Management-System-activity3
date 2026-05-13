<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Spatie\Backup\Tasks\Monitor\BackupDestinationStatusFactory;
use Spatie\Backup\Config\MonitoredBackupsConfig;   // ✅ import this

class BackupController extends Controller
{

public function index()
{
    $monitoredConfig = MonitoredBackupsConfig::fromArray(config('backup.monitor_backups'));
    $statuses = BackupDestinationStatusFactory::createForMonitorConfig($monitoredConfig);

    $backupStatus = [];
    foreach ($statuses as $status) {
        $destination = $status->backupDestination();
        $newestBackup = $destination->newestBackup();
        $backupStatus[] = [
            'disk'         => $destination->diskName(),
            'healthy'      => $status->isHealthy(),
            'amount'       => $destination->backups()->count(),
            'newest'       => $newestBackup ? $newestBackup->date()->format('Y-m-d H:i:s') : null,
            'used_storage' => $this->formatBytes($destination->usedStorage()),
        ];
    }

    // Also list manual .sql backups
    $appName    = config('backup.backup.name');
    $backupDir  = storage_path('app/' . $appName);
    $manualBackups = [];

    if (file_exists($backupDir)) {
        $files = glob($backupDir . '/*.sql');
        rsort($files); // newest first
        foreach ($files as $file) {
            $manualBackups[] = [
                'filename' => basename($file),
                'size'     => $this->formatBytes(filesize($file)),
                'date'     => date('Y-m-d H:i:s', filemtime($file)),
            ];
        }
    }

    return view('admin.backup.index', compact('backupStatus', 'manualBackups'));
}

/**
 * Convert bytes to human-readable format.
 */
private function formatBytes($bytes, $precision = 2)
{
    if ($bytes <= 0) {
        return '0 B';
    }

    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);

    return round($bytes / (1024 ** $pow), $precision) . ' ' . $units[$pow];
}

public function trigger()
{
    \Illuminate\Support\Facades\Log::info('Manual backup triggered by user: ' . auth()->user()->name . ' (ID: ' . auth()->id() . ')');

    $artisan = base_path('artisan');

    // ✅ Run in background — don't wait for it to finish
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        // Windows: start /B runs process in background
        pclose(popen("start /B php \"{$artisan}\" backup:run --only-db", "r"));
    } else {
        // Linux/Mac
        exec("php \"{$artisan}\" backup:run --only-db > /dev/null 2>&1 &");
    }

    return redirect()->route('admin.backup.index')
        ->with('status', 'Backup started in the background! Check back in a few minutes.');
}
}
