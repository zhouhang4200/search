<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;

class TestMovie2 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
//    protected $signature = 'spider_interval {cache_key}';
    protected $signature = 'test:movie2';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
//        $cacheKey = 'eastday.movie';
//        $cacheKey = $this->argument('cache_key');
//        $spider = Schedule::query()->where('identity', $cacheKey)->first();
//        $script = $spider->script;
//        eval($script);
//        return;

        $url = 'http://kan.eastday.com/web_data/index.json';

        $result = file_get_contents($url);

        $jsonData = json_decode($result, true);

        $cartoonData = array_slice($jsonData['cartoon'][0]['data'], 0, 6);

        $item = [];

        foreach ($cartoonData as $data) {
            $item[] = [
                'label' => 'eastday.cartoon',
                'href' => $data['url'],
                'title' => $data['title'],
                'image' => $data['img'],
                'desc' => $data['info'],
                'episode' => $data['pic_info'] ?? '',
            ];
        }

//        cache()->forever($cacheKey, json_encode($item));
//        $this->alert($cacheKey . '更新完成');

        dd($item);
    }
}
