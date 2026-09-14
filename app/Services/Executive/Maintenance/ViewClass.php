<?php

namespace App\Services\Executive\Maintenance;

use FilesystemIterator;
use Illuminate\Support\Facades\DB;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class ViewClass
{
    public function systemInfo(): array
    {
        $connection = config('database.default');

        return [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? php_sapi_name(),
            'os' => PHP_OS_FAMILY . ' (' . php_uname('r') . ')',
            'environment' => app()->environment(),
            'debug_mode' => (bool) config('app.debug'),
            'maintenance_mode' => app()->isDownForMaintenance(),
            'timezone' => config('app.timezone'),
            'server_time' => now()->toDateTimeString(),
            'memory_limit' => ini_get('memory_limit'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
            'max_execution_time' => ini_get('max_execution_time') . 's',
            'database_driver' => $connection,
            'database_name' => config("database.connections.{$connection}.database"),
            'database_version' => $this->databaseVersion(),
            'cache_driver' => config('cache.default'),
            'queue_connection' => config('queue.default'),
            'session_driver' => config('session.driver'),
            'filesystem_disk' => config('filesystems.default'),
        ];
    }

    protected function databaseVersion(): ?string
    {
        try {
            return DB::selectOne('select version() as version')->version ?? null;
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function storageInfo(): array
    {
        $base = base_path();
        $total = @disk_total_space($base) ?: 0;
        $free = @disk_free_space($base) ?: 0;
        $used = $total - $free;

        $directories = collect([
            'Logs' => storage_path('logs'),
            'Framework Cache' => storage_path('framework'),
            'App Storage' => storage_path('app'),
            'Backups' => storage_path('app/backups'),
            'Public Uploads' => public_path('storage'),
            'Build Assets' => public_path('build'),
        ])->map(function ($path, $label) {
            return [
                'label' => $label,
                'path' => $path,
                'exists' => is_dir($path),
                'size' => is_dir($path) ? $this->directorySize($path) : 0,
            ];
        })->values();

        return [
            'disk' => [
                'total' => $total,
                'free' => $free,
                'used' => $used,
                'percent_used' => $total > 0 ? round(($used / $total) * 100, 1) : 0,
            ],
            'directories' => $directories,
        ];
    }

    protected function directorySize(string $path): int
    {
        $size = 0;

        try {
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS),
                RecursiveIteratorIterator::CATCH_GET_CHILD
            );

            foreach ($iterator as $file) {
                if ($file->isFile()) {
                    $size += $file->getSize();
                }
            }
        } catch (\Throwable $e) {
            return $size;
        }

        return $size;
    }

    public function backups(): array
    {
        $path = storage_path('app/backups');

        if (!is_dir($path)) {
            return [];
        }

        return collect(glob($path . '/*.sql.gz'))
            ->map(function ($file) {
                return [
                    'name' => basename($file),
                    'size' => filesize($file),
                    'created_at' => date('Y-m-d H:i:s', filemtime($file)),
                    'timestamp' => filemtime($file),
                ];
            })
            ->sortByDesc('timestamp')
            ->values()
            ->all();
    }

    public function scheduledTasks(): array
    {
        return [
            ['command' => 'dtr:finalize', 'schedule' => 'Weekdays at 20:00'],
            ['command' => 'signatory:update', 'schedule' => 'Weekdays at 23:00'],
            ['command' => 'shift:rotate-guards', 'schedule' => 'Weekly, Monday 00:05'],
        ];
    }
}
