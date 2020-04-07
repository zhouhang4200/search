<?php

use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Pagination\LengthAwarePaginator;

if (!function_exists('myLog')) {
    function myLog($fileName, $data = [])
    {
        if (php_sapi_name() == 'cli') {
            $fileName = $fileName . '-cli';
        }
        $log = new \Monolog\Logger($fileName);
        $log->pushHandler(new \Monolog\Handler\StreamHandler(storage_path() . '/logs/' . $fileName . '-' . date('Y-m-d') . '.log'));
        $log->addInfo($fileName, $data);
    }
}

if (!function_exists('orDefault')) {
    function orDefault($attribute, $default = '') {
        return isset($attribute) ?? $default;
    }
}

function isMobile($userAgent) {
    $userAgent = strtolower($userAgent);
    $clientkeywords = array(
        'nokia', 'sony', 'ericsson', 'mot', 'samsung', 'htc', 'sgh', 'lg', 'sharp', 'sie-'
    ,'philips', 'panasonic', 'alcatel', 'lenovo', 'iphone', 'ipod', 'blackberry', 'meizu',
        'android', 'netfront', 'symbian', 'ucweb', 'windowsce', 'palm', 'operamini',
        'operamobi', 'opera mobi', 'openwave', 'nexusone', 'cldc', 'midp', 'wap', 'mobile'
    );
    // 从HTTP_USER_AGENT中查找手机浏览器的关键字
    if(preg_match("/(".implode('|',$clientkeywords).")/i",$userAgent)&&strpos($userAgent,'ipad') === false) {
        return true;
    }
    return false;
}

if (!function_exists('failed')) {
    function optional_cache(string $key, array $presets = [], bool $decode = true) :Collection
    {
        try {
            if (cache()->has($key)) {
                if ($decode) {
                    return collect(json_decode(cache()->get($key), true));
                } else {
                    return collect(cache()->get($key));
                }
            } else {
                throw new \Exception('cache is not found');
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error($e->getMessage());
            if (count($presets) > 0) {
                $presetCollect = [];
                foreach ($presets as $preset) {
                    $presetCollect[$preset] = collect();
                }
                return collect($presetCollect);
            }
        }
        return collect();
    }
}

if (!function_exists('add_querystring_var')) {
    function add_querystring_var($url, $key, $value)
    {
        $url = preg_replace('/(.*)(?|&)' . $key . '=[^&]+?(&)(.*)/i', '$1$2$4', $url . '&');
        $url = substr($url, 0, -1);
        if (strpos($url, '?') === false) {
            return $value ? ($url . '?' . $key . '=' . $value) : ($url . '?' . $key);
        } else {
            return $value ? ($url . '&' . $key . '=' . $value) : ($url . '&' . $key);
        }
    }
}

if (!function_exists('normalize_http_scheme')) {
    function normalize_http_scheme($url)
    {
        $parseUrl = parse_url($url);
        if (!isset($parseUrl['host'])) {
            return $url;
        }
        $parseUrl['scheme'] = 'https';
        $parseUrl['path'] = isset($parseUrl['path']) ? $parseUrl['path'] : '';
        $parseUrl['query'] = isset($parseUrl['query']) ? $parseUrl['query'] : '';
        if ($parseUrl['query']) {
            return $parseUrl['scheme'] . '://' . $parseUrl['host'] . $parseUrl['path'] . '?' . $parseUrl['query'];
        }
        return $parseUrl['scheme'] . '://' . $parseUrl['host'] . $parseUrl['path'] . $parseUrl['query'];
    }
}

if (! function_exists('stored')) {
    /**
     * 创建资源成功后响应
     *
     * Date: 21/03/2018
     * @author George
     * @param $data
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    function stored($data, $message = '创建成功') {
        return respond($data, $message);
    }
}

if (! function_exists('updated')) {
    /**
     * 更新资源成功后响应
     *
     * Date: 21/03/2018
     * @author George
     * @param $data
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    function updated($data, $message = '更新成功') {
        return respond($data, $message);
    }
}

if (! function_exists('deleted')) {
    /**
     * 删除资源成功后响应
     *
     * Date: 21/03/2018
     * @author George
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    function deleted($message = '删除成功') {
        return message($message, Response::HTTP_OK);
    }
}

if (! function_exists('accepted')) {
    /**
     * 请求已被放入任务队列响应
     *
     * Date: 21/03/2018
     * @author George
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    function accepted($message = '请求已接受，等待处理') {
        return message($message, Response::HTTP_ACCEPTED);
    }
}

if (! function_exists('notFound')) {
    /**
     * 未找到资源响应
     *
     * Date: 21/03/2018
     * @author George
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    function notFound($message = '您访问的资源不存在') {
        return message($message, Response::HTTP_NOT_FOUND);
    }
}

if (! function_exists('internalError')) {
    /**
     * 服务器端位置错误响应
     *
     * Date: 21/03/2018
     * @author George
     * @param $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    function internalError($message = '未知错误导致请求失败', $code = Response::HTTP_INTERNAL_SERVER_ERROR) {
        return message($message, $code);
    }
}

if (! function_exists('failed')) {
    /**
     * 错误的请求响应
     *
     * Date: 21/03/2018
     * @author George
     * @param $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    function failed($message, $code = Response::HTTP_BAD_REQUEST) {
        return message($message, $code);
    }
}

if (! function_exists('success')) {
    /**
     * 成功响应
     *
     * Date: 21/03/2018
     * @author George
     * @param $date
     * @return \Illuminate\Http\JsonResponse
     */
    function success($date) {
        return respond($date);
    }
}

if (! function_exists('download')) {
    function download($resource, $filename) {
        return response()->streamDownload(function () use ($resource) {
            $bufferString = '';
            if (is_resource($resource)) {
                while (!feof($resource)) {
                    $bufferString .= fgets($resource);
                }
                fclose($resource);
            } else {
                $bufferString = $resource;
            }
            echo $bufferString;
        }, $filename);
    }
}

if (! function_exists('message')) {
    /**
     * 消息响应
     *
     * Date: 21/03/2018
     * @author George
     * @param $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    function message($message, $code = Response::HTTP_OK) {
        return respond([], $message, $code);
    }
}

if (! function_exists('respond')) {
    /**
     * 生成响应体
     *
     * Date: 21/03/2018
     * @author George
     * @param array $data
     * @param string $message
     * @param int $code
     * @param array $header
     * @return \Illuminate\Http\JsonResponse
     */
    function respond($data = [], $message = '请求成功', $code = Response::HTTP_OK, array $header = []) {
        if ($data instanceof LengthAwarePaginator) {
            return response()->json([
                'resultCode' => $code,
                'resultMessage' => $message,
                'data' => $data->items(),
                'current_page' => $data->currentPage(),
                'from' => $data->firstItem(),
                'per_page' => $data->perPage(),
                'to' => $data->lastItem(),
                'last_page' => $data->lastPage(),
                'total' => $data->total(),
            ], $code, $header);
        }
        return response()->json([
            'resultCode' => $code,
            'resultMessage' => $message,
            'data' => $data ? $data : []
        ], $code, $header);
    }
}


if (! function_exists('json_validate')) {
    /**
     * @param $str
     * @return bool
     */
    function json_validate($str) {
        if (is_string($str)) {
            @json_decode($str);
            return (json_last_error() === JSON_ERROR_NONE);
        }
        return false;
    }
}

if (! function_exists('array_multi_merge')) {
    /**
     * @return array
     */
    function array_multi_merge()
    {
        $arr = func_get_args();
        $merged = array();
        while ($arr) {
            $array = array_shift($arr);
            if (!$array) { continue; }
            foreach ($array as $key => $value){
                if (is_string($key)) {
                    if (is_array($value) && array_key_exists($key, $merged)
                        && is_array($merged[$key])) {
                        $merged[$key] = call_user_func_array('array_multi_merge', [$merged[$key], $value]);
                    } else {
                        $merged[$key] = $value;
                    }
                } else {
                    $merged[] = $value;
                }
            }
        }
        return $merged;
    }
}

if (! function_exists('merge_external_config')) {

    function merge_external_config($externalConfig, $host, $pid)
    {
        if (Str::contains($pid, "_")) {
            $pidArr = explode("_", $pid);
            return array_multi_merge($externalConfig["common"], $externalConfig["diy"][$host]['matrix'] ?? [], $externalConfig["diy"][$host][$pidArr[0]] ?? [], $externalConfig["diy"][$host][$pid] ?? []);
        }

        return array_multi_merge($externalConfig["common"], $externalConfig["diy"][$host]['matrix'] ?? [], $externalConfig["diy"][$host][$pid] ?? []);
    }
}

if (! function_exists('transform_online')) {

    function transform_online($online = 0)
    {
        switch (true) {
            case $online > 1000:
                $online = bcdiv($online * 10, 10000, 1) . '万';
                break;
            case $online > 100 && $online < 1000:
                $online = bcdiv($online * 100, 10000, 1) . '万';
                break;
            case $online < 100:
                $online = bcdiv($online * 1000, 10000, 1) . '万';
                break;
        }
        return $online;
    }
}
