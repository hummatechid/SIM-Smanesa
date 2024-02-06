<?php

namespace App\Console\Commands;

use App\Models\GeneralSetting;
use Illuminate\Console\Command;

class MakeSettingTime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:make-setting-time';

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
        $data = [
            "date" => now(),
            "time_start" => "07:05",
            "time_end" => "14:55",
        ];

        $settings = GeneralSetting::whereDate("date",now());
        if(!$settings){
            GeneralSetting::create($data);
        }
    }
}
