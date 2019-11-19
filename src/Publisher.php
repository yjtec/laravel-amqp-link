<?php


namespace Yjtec\LaravelAmqpLink;

use Yjtec\LaravelAmqpLink\Exception\Configuration;

class Publisher extends Base
{

    /**
     * @param string  $routing
     * @param Message $message
     * @throws Exception\Configuration
     */
    public function publish($routing, $message)
    {
        $this->getChannel()->basic_publish($message, $this->getProperty('exchange'), $routing);
    }
}
