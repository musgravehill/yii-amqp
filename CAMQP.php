<?php

/**
 * AMQP extension wrapper to communicate with RabbitMQ server  
 * @version 1
 */
class CAMQP extends CApplicationComponent {

    public $host = '';
    public $port = '';
    public $vhost = '';
    public $login = '';
    public $password = '';
    private $_connect = null;
    private $_channel = null;

    public function getHost() {
        return $this->host;
    }

    public function setHost($host) {
        $this->host = $host;
    }

    public function getPort() {
        return $this->port;
    }

    public function setPort($port) {
        $this->port = $port;
    }

    public function getVhost() {
        return $this->vhost;
    }

    public function setVhost($vhost) {
        $this->vhost = $vhost;
    }

    public function getLogin() {
        return $this->login;
    }

    public function setLogin($login) {
        $this->login = $login;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function init() {
        parent::init();       
        Yii::setPathOfAlias('PhpAmqpLib', Yii::getPathOfAlias('application.components.AMQP.PhpAmqpLib'));
        $this->_connect = new PhpAmqpLib\Connection\AMQPConnection($this->host, $this->port, $this->login, $this->password, $this->vhost);
        $this->_channel = $this->_connect->channel();
    }

    /*    name: $exchange
      type: direct
      passive: false
      durable: true // the exchange will survive server restarts
      auto_delete: false //the exchange won't be deleted once the channel is closed.
     */

    public function declareExchange($name, $type = 'fanout', $passive = false, $durable = true, $auto_delete = false) {

        return $this->_channel->exchange_declare($name, $type, $passive, $durable, $auto_delete);
    }

    /*
      name: $queue
      passive: false
      durable: true // the queue will survive server restarts
      exclusive: false // the queue can be accessed in other channels
      auto_delete: false //the queue won't be deleted once the channel is closed.
     */

    public function declareQueue($name, $passive = false, $durable = true, $exclusive = false, $auto_delete = false) {
        return $this->_channel->queue_declare($name, $passive, $durable, $exclusive, $auto_delete);
    }

    public function bindQueueExchanger($queueName, $exchangeName, $routingKey = '') {
        $this->_channel->queue_bind($queueName, $exchangeName, $routingKey);
    }

    public function publish_message($message, $exchangeName, $routingKey = '', $content_type = 'text/plain', $app_id = '') {
        $toSend = new PhpAmqpLib\Message\AMQPMessage($message, array(
            'content_type' => $content_type,
            'content_encoding' => 'utf-8',
            'app_id' => $app_id,
            'delivery_mode' => 2));
        $this->_channel->basic_publish($toSend, $exchangeName, $routingKey);

        //$msg = $this->_channel->basic_get('q1');
        //var_dump($msg);
    }

    public function closeConnection() {
        $this->_channel->close();
        $this->_connect->close();
    }

    public function exchangeDelete($name) {
        $this->_channel->exchange_delete($name);
    }

}

//echo 'div.well.span12>select[name=User[role]]#userRole>option[value=$]*5';
