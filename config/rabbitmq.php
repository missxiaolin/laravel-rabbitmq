<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2018/8/3
 * Time: 下午5:53
 */

return [
    'host' => env('RABBITMQ_HOST', 'localhost'),
    'port' => env('RABBITMQ_PORT', 5672),
    'user' => env('RABBITMQ_USER', 'guest'),
    'pass' => env('RABBITMQ_PASS', 'guest'),
    'vhost' => env('RABBITMQ_VHOST', '/'),
    'amqpDebug' => env('RABBITMQ_AMQP_DEBUG', true),
];