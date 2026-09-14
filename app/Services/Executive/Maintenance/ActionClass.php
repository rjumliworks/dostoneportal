<?php

namespace App\Services\Executive\Maintenance;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ActionClass
{
    public function runBackup(): array
    {
        $exitCode = Artisan::call('database:backup');

        if ($exitCode !== 0) {
            return [
                'success' => false,
                'message' => trim(Artisan::output()) ?: 'Database backup failed.',
            ];
        }

        return [
            'success' => true,
            'message' => 'Database backup created successfully.',
        ];
    }

    public function deleteBackup(string $filename): array
    {
        $path = $this->resolveBackupPath($filename);

        if (!$path || !file_exists($path)) {
            return ['success' => false, 'message' => 'Backup file not found.'];
        }

        unlink($path);

        return ['success' => true, 'message' => 'Backup deleted.'];
    }

    public function downloadBackup(string $filename): BinaryFileResponse
    {
        $path = $this->resolveBackupPath($filename);

        abort_unless($path && file_exists($path), 404, 'Backup file not found.');

        return response()->download($path);
    }

    protected function resolveBackupPath(string $filename): ?string
    {
        $filename = basename($filename);

        if (!preg_match('/^[\w\-.]+\.sql\.gz$/', $filename)) {
            return null;
        }

        return storage_path('app/backups/' . $filename);
    }

    public function clearCache(): array
    {
        Artisan::call('optimize:clear');

        return [
            'success' => true,
            'message' => 'Application, config, route, and view caches cleared.',
            'output' => trim(Artisan::output()),
        ];
    }

    public function toggleMaintenanceMode(bool $enable): array
    {
        if ($enable) {
            $secret = Str::random(32);

            Artisan::call('down', ['--secret' => $secret]);

            return [
                'success' => true,
                'message' => 'Application is now in maintenance mode.',
                'maintenance_mode' => true,
                'bypass_url' => url('/' . $secret),
            ];
        }

        Artisan::call('up');

        return ['success' => true, 'message' => 'Application is back online.', 'maintenance_mode' => false];
    }
}
