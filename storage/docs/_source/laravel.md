# 常用命令

##### [](#bviemt)1.$a为null也不会报错
    optional($a)->id
##### [](#bviemt)2.AppServiceProvider中分离artisan与laravel日志
    $app->configureMonologUsing(function($monolog) use ($app) {
        $filename = $app->runningInConsole() ? 'artisan' : 'laravel';
    
        $handler = new Monolog\Handler\RotatingFileHandler(
            storage_path("logs/{$filename}.log"), config('app.log_max_files', 5)
        );
       
        $handler->setFormatter(new Monolog\Formatter\LineFormatter(null, null, true, true));
       
        $monolog->pushHandler($handler)->pushProcessor(new Monolog\Processor\WebProcessor);;
    });
##### [](#bviemt)3.集合或数组遍历成两等份
    foreach (array_chunk($collect, ceil(count($collect)/2)) as $chunk_collect) {}
##### [](#bviemt)4.复制模型
    $user = App\User::find(1);
    $newUser = $user->replicate();
    $newUser->save();
##### [](#bviemt)5.消除导出的数字变为科学计数法
    $number."\t";
##### [](#bviemt)6.加载帮助函数之后需要执行命令
    composer dumpautoload
