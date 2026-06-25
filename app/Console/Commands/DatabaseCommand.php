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
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filename = 'db-' . now()->format('Y-m-d_H-i-s') . '.sql.gz';
        $path = storage_path('app/backups/' . $filename);

        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        $command = sprintf(
            'mysqldump -h%s -u%s -p%s %s | gzip > %s',
            env('DB_HOST'),
            env('DB_USERNAME'),
            env('DB_PASSWORD'),
            env('DB_DATABASE'),
            $path
        );

        exec($command);

        // Upload to Google Drive
        Storage::disk('google')->put(
            $filename,
            fopen($path, 'r')
        );

        // Delete local copy
        unlink($path);

        $this->info('Database backup uploaded successfully.');
    }
}
