<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DatabaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a compressed mysqldump backup of the application database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $connection = config('database.default');
        $config = config("database.connections.{$connection}");

        $filename = 'db-' . now()->format('Y-m-d_H-i-s') . '.sql.gz';
        $directory = storage_path('app/backups');
        $path = $directory . DIRECTORY_SEPARATOR . $filename;

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $command = sprintf(
            'mysqldump -h%s -P%s -u%s -p%s %s | gzip > %s',
            escapeshellarg($config['host']),
            escapeshellarg($config['port']),
            escapeshellarg($config['username']),
            escapeshellarg($config['password']),
            escapeshellarg($config['database']),
            escapeshellarg($path)
        );

        exec($command . ' 2>&1', $output, $exitCode);

        if ($exitCode !== 0 || !file_exists($path) || filesize($path) === 0) {
            if (file_exists($path)) {
                unlink($path);
            }

            $this->error('Database backup failed: ' . implode("\n", $output));

            return self::FAILURE;
        }

        $this->info("Database backup created: {$filename}");

        return self::SUCCESS;
    }
}
