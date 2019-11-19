<?php


namespace Yjtec\LaravelAmqpLink;

use Illuminate\Config\Repository;

abstract class Config
{
    const REPOSITORY_KEY = 'amqp_link';

    /**
     * @var array
     */
    protected $properties = [];

    /**
     * 传入配置驱动
     *
     * @param Repository $config
     */
    public function __construct(Repository $config)
    {
        $this->extractProperties($config);
    }

    /**
     * @param Repository $config
     */
    protected function extractProperties(Repository $config)
    {
        //检查配置文件是否存在
        if ($config->has(self::REPOSITORY_KEY)) {
            //获取所有配置
            $data = $config->get(self::REPOSITORY_KEY);
            //加载默认配置信息
            $this->properties = $data['properties'][$data['use']];
        }
    }

    /**
     * 合并动态传入的配置信息
     * @param array $properties
     * @return $this
     */
    public function mergeProperties(array $properties)
    {
        $this->properties = array_merge($this->properties, $properties);
        return $this;
    }

    /**
     * 获取配置信息
     * @return array
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * 获取配置参数
     * @param string $key
     * @return mixed
     */
    public function getProperty($key)
    {
        return array_key_exists($key, $this->properties) ? $this->properties[$key] : null;
    }

    /**
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getConnectOption($key, $default = null)
    {
        $options = $this->getProperty('connect_options');

        if (!is_array($options)) {
            return $default;
        }

        return array_key_exists($key, $options) ? $options[$key] : $default;
    }

    /**
     * @return mixed
     */
    abstract public function setup();
}