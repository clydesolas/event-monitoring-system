<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Backup\BackupDestination\BackupDestination;
use Spatie\Backup\Tasks\Backup\BackupJobFactory;

class BackupController extends Controller
{
    public function createBackup()
    {
        // Create a backup job
        $backupJob = BackupJobFactory::createFromArray(config('backup.backup'));

        // Start the backup process
        $backupJob->run();

        // Get the path to the backup file
        $backupFile = $backupJob->getBackupDestination()->getFile();

        // Return the backup file path or any other response as needed
        return response()->json(['backup_path' => $backupFile]);
    }
}
