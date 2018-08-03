<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
}
