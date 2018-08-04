<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2018/8/4
 * Time: 下午3:25
 */

namespace Tests\Unit\Consumer;

use App\Support\Consumer\RabbitMQ;
use Illuminate\Support\Facades\Redis;

class Test1 extends RabbitMQ
{
    public $exchange = 'laravel-exchange';

    public $queue = 'laravel-queue2';

    public $tag = 'laravel-consumer';

    public $logger = true;

    public function handle(array $data)
    {
        Redis::set('rabbit:consumer:success', $data['success']);
        Redis::set('rabbit:consumer:version', $data['data']['version']);
        Redis::set('rabbit:consumer:uniqid', $data['data']['uniqid']);

        Redis::incr('rabbit:consumer:incr');
    }
}