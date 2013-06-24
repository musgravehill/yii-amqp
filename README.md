yii-amqp
========
AMQP extension wrapper to communicate with RabbitMQ server. Based on **videlalvaro/php-amqplib**

**Requirements: PHP 5.3** due to the use of `namespaces`.

#How to use#
Pull files to `/protected/components/AMQP/`
In congif file:
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
See `/demo` with examples.
In controllers (publisher):
```bash
   $exName = 'exFanOut';
   $qName = 'qF';
   //Yii::app()->amqp->exchangeDelete($exName); //you can delete exchanger if error occurred
   Yii::app()->amqp->declareExchange($exName, $type = 'fanout', $passive = false, $durable = true, $auto_delete = false);
   Yii::app()->amqp->declareQueue($qName, $passive = false, $durable = true, $exclusive = false, $auto_delete = false);
   Yii::app()->amqp->bindQueueExchanger($qName, $exName);
   Yii::app()->amqp->publish($message, $exName, $routeKey = '', $content_type = 'text/plain', $expiration = '', $message_id = '', $app_id = yii::app()->name);
   Yii::app()->amqp->closeConnection();
```
In clients (consumer) see `/demo/yii-consumer-*`
