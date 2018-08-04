<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Redis;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Unit\Publisher\Test;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $this->assertTrue(true);
    }

    public function testRabbitmq()
    {
        $config = config('rabbitmq');
        $this->assertEquals($config['host'], env('RABBITMQ_HOST'));
        $this->assertEquals($config['port'], env('RABBITMQ_PORT'));
        $this->assertEquals($config['pass'], env('RABBITMQ_PASS'));
    }

    public function testPublishCase()
    {
        $publisher = Test::getInstance();
        $uniqid = uniqid();
        Redis::del('rabbit:consumer:incr');

        $publisher->basicPublish([
            'success' => true,
            'data' => [
                'version' => '1.1',
                'uniqid' => $uniqid,
            ],
        ]);

        sleep(1);

        $this->assertEquals(true, Redis::get('rabbit:consumer:success'));
        $this->assertEquals('1.1', Redis::get('rabbit:consumer:version'));
        $this->assertEquals($uniqid, Redis::get('rabbit:consumer:uniqid'));
        $this->assertEquals(2, Redis::get('rabbit:consumer:incr'));
    }
}
