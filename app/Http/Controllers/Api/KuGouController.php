<?php

namespace App\Http\Controllers\Api;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KuGouController extends Controller
{
    /**
     * 获取音乐地址
     *
     * @param Request $request
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function url(Request $request)
    {
        try {
            $music_artist = $request->input('music_artist');
            $music_name = $request->input('music_name');

            $music = $this->getMusicId($music_artist, $music_name); // 音乐id

            if (! is_array($music)) {
                return response()->json([
                    'code' => 10002,
                    'message' => 'music is not found'
                ]);
            }
            $music_display_url = 'https://www.kugou.com/song/#hash='.$music['music_hash'].'&album_id='.$music['music_id'];

            // 获取播放地址
            $search_play_url = 'https://wwwapiretry.kugou.com/yy/index.php?r=play/getdata&dfid=0PrIKE2gPpqh0YkidN4DPvnG&mid=92a3db97937883404957cd342fa99821&platid=4&hash='.$music['music_hash'].'&album_id='.$music['music_id'];
            // 发送请求
            $client = new Client(['timeout' => 2000]);
            $response = $client->request('get', $search_play_url, [
                'headers' => [
                    'Referer' => 'https://www.kugou.com/song/',
                    'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.75 Safari/537.36',
                ],
            ]);

            $content = $response->getBody()->getContents();
            $json_data = json_decode($content, true);

            // 成功之后获取音乐播放地址
            if ($json_data['err_code'] == 0) {
                $music_url = $json_data['data']['play_url'];

                // 下载歌曲
//                $data = file_get_contents($music_url);
//                file_put_contents(public_path('music/').$music_name.'.mp3', $data);

                return response()->json([
                    'code' => 0,
                    'message' => 'success',
                    'data' => [
                        'music_display_url' => $music_display_url,
                        'music_play_url' => $music_url,
                    ]
                ]);
            }

            return response()->json([
                'code' => 10000,
                'message' => 'music url is not found'
            ]);
        } catch (\Exception $e) {
            myLog('get_music_url_server_error', [$e->getMessage()]);
            return response()->json([
                'code' => 10001,
                'message' => 'get_music_url_server_error:'
            ]);
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
    public function getMusicId($music_artist, $music_name)
    {
        try {
            $url = 'https://songsearch.kugou.com/song_search_v2?clientver&filter=2&iscorrection=1&keyword='.$music_name.'&page=1&pagesize=5&platform=WebFilter&privilege_filter=0&tag=em&userid=-1';

            // 发送请求
            $client = new Client(['timeout' => 2000]);
            $response = $client->request('GET', $url, [
                'headers' => [
                    'Referer' => 'https://www.kugou.com/yy/html/search.html',
                    'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.75 Safari/537.36',
                ],
            ]);

            $content = $response->getBody()->getContents();

            $json_data = json_decode($content, true);

            // 成功之后获取音乐播放地址
            if ($json_data['error_code'] == 0) {
                foreach($json_data['data']['lists'] as $song) {
                    if ($song['SingerName'] == $music_artist) {
                        $music_id['music_id'] = $song['AlbumID'];
                        $music_id['music_hash'] = $song['FileHash'];

                        return $music_id;
                    }
                }
            }
            myLog('get_music_id_info', ['music id is not found']);

            return 'music id is not found';
        } catch (\Exception $e) {
            myLog('get_music_id_server_error', [$e->getMessage()]);
            return 'local server error';
        }
    }
}
