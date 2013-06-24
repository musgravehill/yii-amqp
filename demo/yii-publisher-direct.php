<?php

$message = 'myMessage';

//direct
$exName = 'exDirect';
$qName1 = 'qDrouteKey1';
$qName2 = 'qDrouteKey2';
$qName3 = 'qDrouteKey3';
//Yii::app()->amqp->exchangeDelete($exName);
Yii::app()->amqp->declareExchange($exName, $type = 'direct', $passive = false, $durable = true, $auto_delete = false);

Yii::app()->amqp->declareQueue($qName1, $passive = false, $durable = true, $exclusive = false, $auto_delete = false);
Yii::app()->amqp->bindQueueExchanger($qName1, $exName, $routingKey = $qName1);
Yii::app()->amqp->publish_message($message . '=1', $exName, $routingKey = $qName1, $content_type = 'text/plain', $app_id = yii::app()->name);

Yii::app()->amqp->declareQueue($qName2, $passive = false, $durable = true, $exclusive = false, $auto_delete = false);
Yii::app()->amqp->bindQueueExchanger($qName2, $exName, $routingKey = $qName2);
Yii::app()->amqp->publish_message($message . '=2', $exName, $routingKey = $qName2, $content_type = 'text/plain', $app_id = yii::app()->name);

Yii::app()->amqp->declareQueue($qName3, $passive = false, $durable = true, $exclusive = false, $auto_delete = false);
Yii::app()->amqp->bindQueueExchanger($qName3, $exName, $routingKey = $qName3);
Yii::app()->amqp->publish_message($message . '=3', $exName, $routingKey = $qName3, $content_type = 'text/plain', $app_id = yii::app()->name);

Yii::app()->amqp->closeConnection();

