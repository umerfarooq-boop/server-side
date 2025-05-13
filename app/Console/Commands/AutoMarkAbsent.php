<?php

namespace App\Console\Commands;

use App\Models\Attendence;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;

class AutoMarkAbsent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:auto-mark-absent';
    protected $description = 'Automatically mark attendance as Absent after 1 hours if not already marked';

    /**
     * The console command description.
     *
     * @var string
     */

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now('Asia/Karachi');

        $attendances = Attendence::whereDate('start_time', $today)
        ->where('start_time', '<=', $now->subHour()) 
        ->whereNull('attendance_status')
        ->get();
    

        foreach ($attendances as $attendance) {
            $attendance->attendance_status = 'A';
            $attendance->save();
            $this->info("Marked attendance ID {$attendance->id} as Absent.");
        }

        $this->info('Auto-marking complete.');
    }
}
