<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DownloadGame extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'download:game';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '下载加速器游戏图标';

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
        $data = file_get_contents('http://l3.lonlife.org/updateAndroid/games.json');
//dd($data);
        $data = preg_replace('/[\x00-\x1F\x80-\x9F]/u', '', trim($data));
        $arr = json_decode($data, true);
//        dd(json_last_error());
//        dd($arr);
        $insert = [];

        foreach($arr['result'] as $value) {
            $url = 'http://l3.lonlife.org';
            $source = file_get_contents($url . $value['icon_url']);

            if ($value['game_type'] == 1) {
                $path = '/games/china/';
            } elseif ($value['game_type'] == 2) {
                $path = '/games/foreign/';
            } else {
                continue;
            }

            if (!file_exists(public_path($path))) {
                mkdir(public_path($path), 0755, true);
            }

            $file = $path.time().mt_rand(1000, 9999).'.png';
            $res = file_put_contents(public_path($file), $source);

            if (!$res) {
                continue;
            }

            $insert[] = [
                'type' => $value['game_type'],
                'name' => $value['game_name'],
                'img' => $file
            ];
//            dd($insert);
        }

        DB::table('games')->insert($insert);

        $this->info('success');
    }
}
