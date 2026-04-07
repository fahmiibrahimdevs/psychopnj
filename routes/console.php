<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('storage:migrate-r2 {--dry-run : Preview files without uploading} {--delete-local : Delete local files after successful upload}', function () {
    $sourceDisk = 'public_local';
    $targetDisk = 'public';

    $files = Storage::disk($sourceDisk)->allFiles();

    if (empty($files)) {
        $this->warn('No files found in storage/app/public.');
        return;
    }

    $this->info('Found '.count($files).' files in local public storage.');

    $uploaded = 0;
    $skipped = 0;
    $failed = 0;
    $deleted = 0;

    foreach ($files as $file) {
        if ($this->option('dry-run')) {
            $this->line('[DRY-RUN] '.$file);
            continue;
        }

        try {
            if (Storage::disk($targetDisk)->exists($file)) {
                $skipped++;
                $this->line('[SKIP] '.$file.' (already exists)');
                continue;
            }

            $stream = Storage::disk($sourceDisk)->readStream($file);
            if ($stream === false) {
                $failed++;
                $this->error('[FAIL] '.$file.' (cannot read stream)');
                continue;
            }

            $result = Storage::disk($targetDisk)->writeStream($file, $stream, [
                'visibility' => 'public',
            ]);

            if (is_resource($stream)) {
                fclose($stream);
            }

            if (! $result) {
                $failed++;
                $this->error('[FAIL] '.$file.' (upload failed)');
                continue;
            }

            $uploaded++;
            $this->info('[OK] '.$file);

            if ($this->option('delete-local')) {
                if (Storage::disk($sourceDisk)->delete($file)) {
                    $deleted++;
                }
            }
        } catch (\Throwable $e) {
            $failed++;
            $this->error('[FAIL] '.$file.' ('.$e->getMessage().')');
        }
    }

    $this->newLine();
    $this->info('Migration summary:');
    $this->line('- Uploaded: '.$uploaded);
    $this->line('- Skipped: '.$skipped);
    $this->line('- Failed: '.$failed);

    if ($this->option('delete-local')) {
        $this->line('- Deleted local files: '.$deleted);
    }
})->purpose('Migrate files from storage/app/public to Cloudflare R2');
