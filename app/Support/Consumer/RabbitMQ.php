<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2018/8/4
 * Time: 下午2:52
 */

namespace App\Support\Consumer;


use App\Core\Enums\ErrorCode;
use App\Exceptions\CodeException;
use App\Support\InstanceTrait;
use PhpAmqpLib\Connection\AMQPStreamConnection;

abstract class RabbitMQ
{
    use InstanceTrait;

    public $exchange;

    public $queue;

    public $tag;

    public $channel;

    protected static $_instances = [];

    /**
     * RabbitMQ constructor.
     * @throws CodeException
     * @throws \ReflectionException
     * @throws \xiaolin\Enum\Exception\EnumException
     */
    public function __construct()
    {
        if (!isset($this->exchange)) {
            throw new CodeException(ErrorCode::$ENUM_RABBIT_MQ_CONSUMER_EXCHANGE_NOT_EXIST);
        }

        if (!isset($this->queue)) {
            throw new CodeException(ErrorCode::$ENUM_RABBIT_MQ_CONSUMER_QUEUE_NOT_EXIST);
        }

        if (!isset($this->tag)) {
            throw new CodeException(ErrorCode::$ENUM_RABBIT_MQ_CONSUMER_TAG_NOT_EXIST);
        }

        $config = config('rabbitmq');
        $channel = new AMQPStreamConnection(
            $config['host'],
            $config['port'],
            $config['user'],
            $config['pass'],
            $config['vhost']
        );
        $channel = $channel->channel();

        $channel->queue_declare($this->queue, false, true, false, false);

        $channel->exchange_declare($this->exchange, 'direct', false, true, false);
        $channel->queue_bind($this->queue, $this->exchange);

        $this->channel = $channel;
    }

    public function basicConsume()
    {
        $this->channel->basic_consume($this->queue, $this->tag, false, false, false, false, function ($message) {
            try {
                $data = json_decode($message->body, true);
                $this->handle($data);
                $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
            } catch (\Exception $ex) {
                $msg = $ex->getMessage() . ' code:' . $ex->getCode() . ' in ' . $ex->getFile() . ' line ' . $ex->getLine() . PHP_EOL . $ex->getTraceAsString();
                dd($msg);
            }
        });

        // Loop as long as the channel has callbacks registered
        while (count($this->channel->callbacks)) {
            $this->channel->wait();
        }
    }

    abstract public function handle(array $data);

    public function close()
    {
        $this->channel->close();
    }

}