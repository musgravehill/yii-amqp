yii-amqp
========
AMQP extension wrapper to communicate with RabbitMQ server. Based on **videlalvaro/php-amqplib**

**Requirements: PHP 5.3** due to the use of `namespaces`.

#How to use#
Pull files to `/protected/components/AMQP/`

Yii congif file:
```bash
  'components' => array(
        'amqp' => array(
            'class' => 'application.components.AMQP.CAMQP',
            'host' => '127.0.0.1',
            'port' => '5672',
            'login'=>'quest',
            'password'=>'quest',
            'vhost'=>'/',
        ),
        ...
```
Yii controller (publisher) with fanout/direct/topic types: see `/demo/yii-publisher-*` examples.
```bash
    $message = 'myMessage';
    $exName = 'exTopic';
    $routingKey1 = 'server1.user.error';
    $routingKey2 = 'server1.pentest.error';
    $routingKey3 = 'server2.user.error';
    //Yii::app()->amqp->exchangeDelete($exName);
    Yii::app()->amqp->declareExchange($exName, $type = 'topic', $passive = false, $durable = true, $auto_delete = false);
    Yii::app()->amqp->publish_message($message, $exName, $routingKey1, $content_type = '',  $app_id = '');
    Yii::app()->amqp->publish_message($message, $exName, $routingKey2, $content_type = '',  $app_id = '');
    Yii::app()->amqp->publish_message($message, $exName, $routingKey3, $content_type = '', $app_id = '');
    Yii::app()->amqp->closeConnection();
```
Some clients (consumer,listener,executor with fanout/direct/topic types) see `/demo/yii-consumer-*` examples.