<?php
return [

    /*
    |--------------------------------------------------------------------------
    | Define which configuration should be used
    |--------------------------------------------------------------------------
    */
    'use' => 'production',

    /*
    |--------------------------------------------------------------------------
    | AMQP properties separated by key
    |--------------------------------------------------------------------------
    */
    'properties' => [

        'production' => [
            'host'                  => env('AMQP_HOST', 'localhost'),//连接地址
            'port'                  => env('AMQP_PORT', 5672),//端口
            'username'              => env('AMQP_USERNAME', ''),//账号
            'password'              => env('AMQP_PASSWORD', ''),//密码
            'vhost'                 => env('AMQP_VHOST', '/'),//vhost
            'connect_options'       => [],
            'ssl_options'           => [],

            'exchange'              => env('AMQP_EXCHANGE', 'amq.topic'),//交换机
            'exchange_type'         => env('AMQP_EXCHANGE_TYPE', 'topic'),//交换机类型
            'exchange_passive'      => false,//是否被动模式
            'exchange_durable'      => true,//持久性
            'exchange_auto_delete'  => false,//是否自动删除
            'exchange_internal'     => false,//内部交换
            'exchange_nowait'       => false,//
            'exchange_properties'   => [],

            'queue_force_declare'   => false,
            'queue_passive'         => false,
            'queue_durable'         => true,
            'queue_exclusive'       => false,
            'queue_auto_delete'     => false,
            'queue_nowait'          => false,
            'queue_properties'      => ['x-ha-policy' => ['S', 'all']],

            'consumer_tag'          => '',
            'consumer_no_local'     => false,
            'consumer_no_ack'       => false,
            'consumer_exclusive'    => false,
            'consumer_nowait'       => false,
            'timeout'               => 0,
            'persistent'            => true,

            'qos'                   => false,
            'qos_prefetch_size'     => 0,
            'qos_prefetch_count'    => 1,
            'qos_a_global'          => false
        ],

    ],




];