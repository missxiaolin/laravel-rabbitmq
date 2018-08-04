<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2018/8/4
 * Time: 下午3:05
 */

namespace App\Support\Publisher;


use App\Core\Enums\ErrorCode;
use App\Exceptions\CodeException;
use App\Support\InstanceTrait;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

abstract class RabbitMQ
{
    use InstanceTrait;

    public $exchange;

    public $queue;

    public $channel;

    public $exchangeType = 'direct';

    /**
     * RabbitMQ constructor.
     * @throws CodeException
     * @throws \ReflectionException
     * @throws \xiaolin\Enum\Exception\EnumException
     */
    public function __construct()
    {
        if (!isset($this->exchange)) {
            throw new CodeException(ErrorCode::$ENUM_RABBIT_MQ_PUBLISHER_EXCHANGE_NOT_EXIST);
        }

        if (!isset($this->queue)) {
            throw new CodeException(ErrorCode::$ENUM_RABBIT_MQ_PUBLISHER_QUEUE_NOT_EXIST);
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

        $channel->exchange_declare($this->exchange, $this->exchangeType, false, true, false);
        $channel->queue_bind($this->queue, $this->exchange);

        $this->channel = $channel;
    }

    /**
     * @param array $data
     */
    public function basicPublish(array $data)
    {
        $body = json_encode($data);
        $message = new AMQPMessage($body, [
            'content_type' => 'text/plain',
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT,
        ]);

        return $this->channel->basic_publish($message, $this->exchange);
    }

    public function close()
    {
        $this->channel->close();
    }
}