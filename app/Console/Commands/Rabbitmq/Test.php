<?php

namespace App\Console\Commands\Rabbitmq;

use Illuminate\Console\Command;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbitmq-cs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rabbitmq-ceshi';

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
            $consumer = \Tests\Unit\Consumer\Test::getInstance();
            $consumer->basicConsume();
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
