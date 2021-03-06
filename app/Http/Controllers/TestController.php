<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function test(Request $request)
    {
        try {
            $music_artist = $request->input('music_artist', '品冠');
            $music_name = $request->input('music_name');
            $music_id = $this->getMusicId($music_artist, $music_name); // 音乐id

            $data = '{"ids":"['.$music_id.']","br":128000,"csrf_token":""}';
            $method = 'aes-128-cbc';
            $key = '0CoJUm6Qyw8W8jud'; // 第一次的key，固定
            $iv = '0102030405060708';
            $second_key = 'a8LWv2uAtXjzSfkQ'; // 第二次的key

            // 需要传递的第一个参数
            $param = openssl_encrypt(openssl_encrypt($data, $method, $key, 0, $iv), $method, $second_key, 0, $iv);

            // 需要传递的第二个参数, 是第二次的key的rsa加密后的结果
            $request_key = '2d48fd9fb8e58bc9c1f14a7bda1b8e49a3520a67a2300a1f73766caee29f2411c5350bceb15ed196ca963d6a6d0b61f3734f0a0f4a172ad853f16dd06018bc5ca8fb640eaa8decd1cd41f66e166cea7a3023bd63960e656ec97751cfc7ce08d943928e9db9b35400ff3d138bda1ab511a06fbee75585191cabe0e6e63f7350d6';

            // 发送请求
            $client = new Client(['timeout' => 2000]);
            $response = $client->request('POST', 'https://music.163.com/weapi/song/enhance/player/url?csrf_token=', [
                'headers' => [
                    'Referer' => 'https://music.163.com/',
                    'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.75 Safari/537.36',
                ],
                'form_params' => [
                    'params' => $param,
                    'encSecKey' => $request_key,
                ]
            ]);

            $content = $response->getBody()->getContents();

            $json_data = json_decode($content, true);

            // 成功之后获取音乐播放地址
            if ($json_data['code'] == 200) {
                $music_url = $json_data['data'][0]['url'];

                return $music_url;
            }
        } catch (\Exception $e) {
            dd('get_music_url_error:'.$e->getMessage());
        }
    }

    /**
     * 获取音乐Id
     *
     * @param string $music_artist
     * @param string $music_name
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getMusicId($music_artist = '品冠', $music_name = '我不愿让你一个人')
    {
        try {
            $data = '{"s":"'.$music_name.'","limit":"8","csrf_token":""}';
            $method = 'aes-128-cbc';
            $key = '0CoJUm6Qyw8W8jud'; // 第一次的key，固定
            $iv = '0102030405060708';
            $second_key = 'a8LWv2uAtXjzSfkQ'; // 第二次的key

            // 需要传递的第一个参数
            $param = openssl_encrypt(openssl_encrypt($data, $method, $key, 0, $iv), $method, $second_key, 0, $iv);

            // 需要传递的第二个参数, 是第二次的key的rsa加密后的结果
            $request_key = '2d48fd9fb8e58bc9c1f14a7bda1b8e49a3520a67a2300a1f73766caee29f2411c5350bceb15ed196ca963d6a6d0b61f3734f0a0f4a172ad853f16dd06018bc5ca8fb640eaa8decd1cd41f66e166cea7a3023bd63960e656ec97751cfc7ce08d943928e9db9b35400ff3d138bda1ab511a06fbee75585191cabe0e6e63f7350d6';

            // 发送请求
            $client = new Client(['timeout' => 2000]);
            $response = $client->request('POST', 'https://music.163.com/weapi/search/suggest/web?csrf_token=', [
                'headers' => [
                    'Referer' => 'https://music.163.com/',
                    'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.75 Safari/537.36',
                ],
                'form_params' => [
                    'params' => $param,
                    'encSecKey' => $request_key,
                ]
            ]);

            $content = $response->getBody()->getContents();

            $json_data = json_decode($content, true);

            // 成功之后获取音乐播放地址
            if ($json_data['code'] == 200) {
                foreach($json_data['result']['songs'] as $song) {
                    if ($song['artists'][0]['name'] == $music_artist) {
                        $music_id = $song['id'];
                        return $music_id;
                    }
                }
            }
        } catch (\Exception $e) {
            dd('get_music_id_error:'.$e->getMessage());
        }
    }
}
