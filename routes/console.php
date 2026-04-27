<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Backup\Tasks\Monitor\BackupDestinationStatusFactory;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Log;

// Backup
Schedule::command('backup:run')->dailyAt('02:00')
    ->withoutOverlapping()
    ->onFailure(fn() => Log::critical('backup:run failed.'))
    ->onSuccess(fn() => Log::info('backup:run succeeded.'));

Schedule::command('backup:clean')->dailyAt('03:00')
    ->withoutOverlapping()
    ->onFailure(fn() => Log::critical('backup:clean failed.'));

// Maintenance
Schedule::command('order:cleanup-pending')->hourly()
    ->withoutOverlapping()
    ->onFailure(fn() => Log::critical('order:cleanup-pending failed.'));

Schedule::command('session:gc')->daily();

Schedule::command('log:rotate')->weekly()
    ->sundays()
    ->at('01:00')
    ->withoutOverlapping();

Schedule::command('report:generate-daily')->dailyAt('06:00')
    ->withoutOverlapping();

Schedule::command('notification:prune')->weekly()
    ->sundays()
    ->at('02:30');

Schedule::command('audit:archive')->monthly()
    ->withoutOverlapping();


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');



Schedule::command('queue:work --stop-when-empty')
    ->everyMinute()
    ->withoutOverlapping();



