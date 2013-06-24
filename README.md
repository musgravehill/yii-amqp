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
   $exName = 'exFanOut';
   $qName = 'qF';
   //Yii::app()->amqp->exchangeDelete($exName); //you can delete exchanger if error occurred
   Yii::app()->amqp->declareExchange($exName, $type = 'fanout', $passive = false, $durable = true, $auto_delete = false);
   Yii::app()->amqp->declareQueue($qName, $passive = false, $durable = true, $exclusive = false, $auto_delete = false);
   Yii::app()->amqp->bindQueueExchanger($qName, $exName);
   Yii::app()->amqp->publish($message,$exName,$routeKey='',$content_type='',$expiration='',$message_id='',$app_id='');
   Yii::app()->amqp->closeConnection();
```
Some clients (consumer,listener,executor with fanout/direct/topic types) see `/demo/yii-consumer-*` examples.