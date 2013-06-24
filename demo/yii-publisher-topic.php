<?php

$message = 'myMessage';

//topic
$exName = 'exTopic';
$routingKey1 = 'server1.user.error';
$routingKey2 = 'server1.pentest.error';
$routingKey3 = 'server2.user.error';
//Yii::app()->amqp->exchangeDelete($exName);
Yii::app()->amqp->declareExchange($exName, $type = 'topic', $passive = false, $durable = true, $auto_delete = false);

Yii::app()->amqp->publish_message($message . '(server1.user.error)', $exName, $routingKey1, $content_type = 'text/plain',  $app_id = yii::app()->name);
Yii::app()->amqp->publish_message($message . '(server1.pentest.error)', $exName, $routingKey2, $content_type = 'text/plain',  $app_id = yii::app()->name);
Yii::app()->amqp->publish_message($message . '(server2.user.error)', $exName, $routingKey3, $content_type = 'text/plain', $app_id = yii::app()->name);

Yii::app()->amqp->closeConnection();

$this->render('resizer');

