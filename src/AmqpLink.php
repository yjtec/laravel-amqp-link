<?php


namespace Yjtec\LaravelAmqpLink;

use Closure;

class AmqpLink
{
    /**
     * 消息插入交换机
     * @param string $routing
     * @param mixed  $message
     * @param array  $properties
     */
    public function publish($routing, $message, array $properties = [])
    {
        $properties['routing'] = $routing;

        /* @var Publisher $publisher */
        $publisher = app()->make('Yjtec\LaravelAmqpLink\Publisher');

        $publisher
            ->mergeProperties($properties)
            ->setup();

        if (is_string($message)) {
            $message = new Message($message, ['content_type' => 'text/plain', 'delivery_mode' => 2]);
        }
        $publisher->publish($routing, $message);
        Base::shutdown($publisher->getChannel(), $publisher->getConnection());
    }
    /**
     * @param string  $queue
     * @param Closure $callback
     * @param array   $properties
     * @throws Exception\Configuration
     */
    public function consume($queue, Closure $callback, $key = [], $properties = [])
    {
        $properties['queue'] = $queue;

        /* @var Consumer $consumer */
        $consumer = app()->make('Yjtec\LaravelAmqpLink\Consumer');
        $consumer
            ->mergeProperties($properties)
            ->setup();

        $consumer->consume($queue, $callback, $key);
        Base::shutdown($consumer->getChannel(), $consumer->getConnection());
    }

    /**
     * @param string $body
     * @param array  $properties
     * @return \Yjtec\LaravelAmqpLink\Message
     */
    public function message($body, $properties = [])
    {
        return new Message($body, $properties);
    }
}