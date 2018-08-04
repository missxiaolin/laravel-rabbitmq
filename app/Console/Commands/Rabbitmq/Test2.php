<?php

namespace App\Console\Commands\Rabbitmq;

use Illuminate\Console\Command;

class Test2 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbitmq-cs1';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rabbitmq-ceshi2';

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
        try {
            $consumer = \Tests\Unit\Consumer\Test1::getInstance();
            $consumer->basicConsume();
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
