<?php


namespace Yjtec\LaravelAmqpLink\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class AmqpLink
 * @see \Yjtec\LaravelAmqpLink\AmqpLink
 */
class AmqpLink extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'AmqpLink';
    }
}