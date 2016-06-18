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
        
        Slack::to("#video")
            ->attach([
                'fallback' => "今天有 *{$todayUsers}* 个用户从主站前往登录或注册了帐号, *站点总共有 {$totalUsers} 名用户.*",
                'fields' => [
                    [
                        'title' => '今天新用户（从主站转入/新注册）',
                        'value' => $todayUsers . "名用户.",
                        'short' => true
                    ],
                    [
                        'title' => '教学视频网站一共拥有',
                        'value' => $totalUsers . "名用户."
                    ]
                ]
            ])
            ->send("[每日报告] 教学网站数据统计");
    }
}
