<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Console\Command;

class MakeAttendences extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:make-attendences';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make attendences per daily';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $students = Student::all();

        $attendance = Attendance::whereDate("created_at",today())->count();

        if($attendance == 0 ){
            foreach($students as $student){
                Attendance::create([
                    "student_id" => $student->id,
                    "status" => "alpha"
                ]);
            }
        }
    }
}
