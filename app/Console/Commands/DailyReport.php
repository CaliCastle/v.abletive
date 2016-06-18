<?php

namespace App\Console\Commands;

use Slack;
use App\User;
use Illuminate\Console\Command;

class DailyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily report for statistics';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $todayUsers = User::justRegistered()->count();
        $totalUsers = User::count();
        
        Slack::to("@cali")
            ->attach([
                'fallback' => "Today we have {$todayUsers} users signed up, *out of {$totalUsers} users.*",
                'fields' => [
                    [
                        'title' => 'New registers or sign ups today',
                        'value' => $todayUsers . " users."
                    ],
                    [
                        'title' => 'Total users we got',
                        'value' => $totalUsers . " users."
                    ]
                ]
            ])
            ->send("Daily Report");
    }
}
