<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestMovie extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:movie';

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
        $html = \QL\QueryList::get("https://kan.2345.com")->getHtml();
        $html = mb_convert_encoding($html, 'UTF-8', 'GB2312');
        $dom = \QL\QueryList::html($html);
        $item = [];

//        $item[] = [
//            'label' => '2345movie',
//            'href' => normalize_http_scheme(add_querystring_var($dom->find('.v_picTxt.leftPicCon.hotDrama.pic380_276:eq(1) a')->attrs('href')->first(), 'lm002196-0027', '')),
//            'title' => $dom->find('.v_picTxt.leftPicCon.hotDrama.pic380_276:eq(1) img')->attrs('title')->first() ?? $response['dom']->find('.v_picTxt.leftPicCon.hotDrama.pic380_276 img')->attrs('alt')->first(),
//            'image' => $dom->find('.v_picTxt.leftPicCon.hotDrama.pic380_276:eq(1) img')->attrs('data-src')->first() ?? normalize_http_scheme($response['dom']->find('.v_picTxt.leftPicCon.hotDrama.pic380_276 img')->attrs('src')->first()),
//            'desc' => $dom->find('.v_picTxt.leftPicCon.hotDrama.pic380_276:eq(1) .sDes')->texts()->first(),
//            'episode' => $dom->find('.v_picTxt.leftPicCon.hotDrama.pic380_276:eq(1) .pRightBottom em')->texts()->first()
//        ];


        $dom->find('.v_picConBox.otherHotDrama.height332:eq(1) li')->map(function ($node) use (&$item) {
            $data = [
                'label' => '2345movie',
                'href' => normalize_http_scheme(add_querystring_var($node->find('a')->attrs('href')->first(), 'lm002196-0027', '')),
                'title' => $node->find('img')->attrs('title')->first() ?? $node->find('img')->attrs('alt')->first(),
                'image' => $node->find('img')->attrs('data-src')->first() ?? normalize_http_scheme($node->find('img')->attrs('src')->first()),
                'desc' => $node->find('.sDes')->texts()->first(),
                'episode' => $node->find('.pRightBottom em')->texts()->first(),
            ];
            $item[] = $data;
        });

//        cache()->forever($cacheKey, json_encode($item));
//        $this->alert($cacheKey . '更新完成');
    }
}
