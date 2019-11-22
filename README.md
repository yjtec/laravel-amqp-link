## 发布消息

### 用路由键推送消息

```php
    AmqpLink::publish('routing-key', 'message');
```

### 用路由键推送消息并创建队列

```php	
    AmqpLink::publish('routing-key', 'message' , ['queue' => 'queue-name']);
```

### 带有路由键和覆盖属性的推送消息

```php	
    AmqpLink::publish('routing-key', 'message' , ['exchange' => 'amq.direct']);
```
## 消费信息

### 消费消息，确认并在没有消息时停止

```php
AmqpLink::consume('queue-name', function ($message, $resolver) {
    		
   var_dump($message->body);

   $resolver->acknowledge($message);

   $resolver->stopWhenProcessed();
        
});
```

### 永远消耗消息

```php
AmqpLink::consume('queue-name', function ($message, $resolver) {
    		
   var_dump($message->body);

   $resolver->acknowledge($message);
        
});
```

### 自定义监听路由

```php
AmqpLink::consume('queue-name', function ($message, $resolver) {
        
   var_dump($message->body);

   $resolver->acknowledge($message);
        
}, ['*', 'test.*']);
```

### 使用自定义设置来消费消息

```php
AmqpLink::consume('queue-name', function ($message, $resolver) {
    		
   var_dump($message->body);

   $resolver->acknowledge($message);
      
}, [], [
	'timeout' => 2,
	'vhost'   => 'vhost3'
]);
```
## 扇出示例

### 发布消息

```php
\AmqpLink::publish('', 'message' , [
    'exchange_type' => 'fanout',
    'exchange' => 'amq.fanout',
]);
```

### 消费信息

```php
\AmqpLink::consume('', function ($message, $resolver) {
    var_dump($message->body);
    $resolver->acknowledge($message);
}, [
    'exchange' => 'amq.fanout',
    'exchange_type' => 'fanout',
    'queue_force_declare' => true,
    'queue_exclusive' => true,
    'persistent' => true // required if you want to listen forever
]);
```