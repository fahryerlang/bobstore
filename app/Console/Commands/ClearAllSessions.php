<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ClearAllSessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'session:clear-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all user sessions from the application';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $driver = config('session.driver');

        switch ($driver) {
            case 'file':
                $path = config('session.files');
                $files = File::glob($path . '/*');
                foreach ($files as $file) {
                    if (File::isFile($file)) {
                        File::delete($file);
                    }
                }
                break;

            case 'database':
                DB::table(config('session.table', 'sessions'))->truncate();
                break;

            default:
                $this->error("Session driver '{$driver}' is not supported for clearing.");
                return 1;
        }

        $this->info('All sessions have been cleared successfully!');
        return 0;
    }
}
