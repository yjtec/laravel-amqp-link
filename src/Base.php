<?php


namespace Yjtec\LaravelAmqpLink;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Connection\AMQPSSLConnection;
use PhpAmqpLib\Channel\AMQPChannel;

class Base extends Config
{
    /**
     * @var AMQPStreamConnection
     */
    protected $connection;

    /**
     * @var AMQPChannel
     */
    protected $channel;

    /**
     * @var array
     */
    protected $queueInfo;

    /**
     * 创建消息队列连接
     */
    public function connect()
    {
        if ($this->getProperty('ssl_options')) {
            $this->connection = new AMQPSSLConnection(
                $this->getProperty('host'),
                $this->getProperty('port'),
                $this->getProperty('username'),
                $this->getProperty('password'),
                $this->getProperty('vhost'),
                $this->getProperty('ssl_options'),
                $this->getProperty('connect_options')
            );
        } else {
            $this->connection = new AMQPStreamConnection(
                $this->getProperty('host'),
                $this->getProperty('port'),
                $this->getProperty('username'),
                $this->getProperty('password'),
                $this->getProperty('vhost'),
                $this->getConnectOption('insist', false),
                $this->getConnectOption('login_method', 'AMQPLAIN'),
                $this->getConnectOption('login_response', null),
                $this->getConnectOption('locale', 3),
                $this->getConnectOption('connection_timeout', 3.0),
                $this->getConnectOption('read_write_timeout', 130),
                $this->getConnectOption('context', null),
                $this->getConnectOption('keepalive', false),
                $this->getConnectOption('heartbeat', 60),
                $this->getConnectOption('channel_rpc_timeout', 0.0),
                $this->getConnectOption('ssl_protocol', null)
            );
        }

        $this->channel = $this->connection->channel();
    }
    /**
     * 加载消息队列基础配置
     * @throws Exception\Configuration
     */
    public function setup()
    {
        //获取实例
        $this->connect();
        //获取需要实例的交换机
        $exchange = $this->getProperty('exchange');

        if (empty($exchange)) {
            throw new Exception\Configuration('Please check your settings, exchange is not defined.');
        }

        /*
            name: $exchange
            type: topic
            passive: false
            durable: true // the exchange will survive server restarts
            auto_delete: false //the exchange won't be deleted once the channel is closed.
        */
        //连接交换机
        $this->channel->exchange_declare(
            $exchange,
            $this->getProperty('exchange_type'),
            $this->getProperty('exchange_passive'),
            $this->getProperty('exchange_durable'),
            $this->getProperty('exchange_auto_delete'),
            $this->getProperty('exchange_internal'),
            $this->getProperty('exchange_nowait'),
            $this->getProperty('exchange_properties')
        );
        //获取需要实例的队列
        $queue = $this->getProperty('queue');

        if (!empty($queue) || $this->getProperty('queue_force_declare')) {
            /*
                name: $queue
                passive: false
                durable: true // the queue will survive server restarts
                exclusive: false // queue is deleted when connection closes
                auto_delete: false //the queue won't be deleted once the channel is closed.
                nowait: false // Doesn't wait on replies for certain things.
                parameters: array // Extra data, like high availability params
            */

            /** @var ['queue name', 'message count',] queueInfo */
            $this->queueInfo = $this->channel->queue_declare(
                $queue,
                $this->getProperty('queue_passive'),
                $this->getProperty('queue_durable'),
                $this->getProperty('queue_exclusive'),
                $this->getProperty('queue_auto_delete'),
                $this->getProperty('queue_nowait'),
                $this->getProperty('queue_properties')
            );

            $this->channel->queue_bind(
                $queue ?: $this->queueInfo[0],
                $exchange,
                $this->getProperty('routing')
            );
        }
        // clear at shutdown
        $this->connection->set_close_on_destruct(true);
    }
    /**
     * 返回频道实例
     * @return \PhpAmqpLib\Channel\AMQPChannel
     */
    public function getChannel()
    {
        return $this->channel;
    }
    /**
     * 返回连接实例
     * @return AMQPStreamConnection
     */
    public function getConnection()
    {
        return $this->connection;
    }
    /**
     * 获取队列消息计数
     * @return int
     */
    public function getQueueMessageCount()
    {
        if (is_array($this->queueInfo)) {
            return $this->queueInfo[1];
        }
        return 0;
    }
    /**
     * 关闭实例
     * @param AMQPChannel          $channel
     * @param AMQPStreamConnection $connection
     */
    public static function shutdown(AMQPChannel $channel, AMQPStreamConnection $connection)
    {
        $channel->close();
        $connection->close();
    }
}