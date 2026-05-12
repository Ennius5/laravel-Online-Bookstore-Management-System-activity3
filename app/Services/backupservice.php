<?php

namespace App\Services;

use Spatie\Backup\BackupDestination\BackupDestinationFactory;
use Spatie\Backup\Config\Config;

class BackupService
{
    public function getBackupStatusArray(): array
    {
        $result = [
            'healthy'       => false,
            'status_message' => 'Unable to fetch backup status',
            'latest_date'   => 'Never',
            'latest_size'   => '0 B',
            'disk'          => 'Unknown',
            'count'         => 0,
        ];

        try {
            // Build the top-level Config object from the whole backup config
            $fullConfig = Config::fromArray(config('backup'));
            $destinations = BackupDestinationFactory::createFromArray($fullConfig);

            $latestBackup = null;
            $disk = 'Unknown';
            $count = 0;
            $allHealthy = true;
            $messages = [];

            foreach ($destinations as $dest) {
                if (!$dest->fresh()) {
                    $allHealthy = false;
                    $messages[] = $dest->diskName() . ': unhealthy';
                }

                $newest = $dest->newestBackup();
                if ($newest && (!$latestBackup || $newest->date()->gt($latestBackup->date()))) {
                    $latestBackup = $newest;
                    $disk = $dest->diskName();
                    $count = $dest->backups()->count();
                }
            }

            $result['healthy'] = $allHealthy && $latestBackup !== null;
            $result['status_message'] = $result['healthy']
                ? 'All healthy'
                : (empty($messages) ? 'No backups found' : implode(', ', $messages));

            if ($latestBackup) {
                $result['latest_date'] = $latestBackup->date()->format('Y-m-d H:i:s');
                $result['latest_size'] = \Spatie\Backup\Helpers\Format::humanReadableSize($latestBackup->sizeInBytes());
                $result['disk'] = $disk;
                $result['count'] = $count;
            }
        } catch (\Exception $e) {
            $result['healthy'] = false;
            $result['status_message'] = 'Error: ' . $e->getMessage();
        }

        return $result;
    }
}
