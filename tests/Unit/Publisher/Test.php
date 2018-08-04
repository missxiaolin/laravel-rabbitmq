<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2018/8/4
 * Time: 下午3:26
 */

namespace Tests\Unit\Publisher;


use App\Support\Publisher\RabbitMQ;

class Test extends RabbitMQ
{
    public $exchange = 'laravel-exchange';

    public $queue = 'laravel-queue';
}
