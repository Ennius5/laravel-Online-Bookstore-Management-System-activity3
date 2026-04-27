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

    return view('admin.backup.index', compact('backupStatus'));
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
        Artisan::call('backup:run');
        return redirect()->route('admin.backup.index')->with('status', 'Backup started successfully!');
    }
}
