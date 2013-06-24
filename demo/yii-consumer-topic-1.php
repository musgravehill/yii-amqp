<?php 
include(__DIR__ . '/config.php');
use PhpAmqpLib\Connection\AMQPConnection;

$exchange = 'exTopic';
$queue = 'allErrorsFromAllServers'; //all errors from all servers
$consumer_tag = 'consumer1';
$routingKey = '#.#.error';

$conn = new AMQPConnection(HOST, PORT, USER, PASS, VHOST);
$ch = $conn->channel();

/*
    The following code is the same both in the consumer and the producer.
    In this way we are sure we always have a queue to consume from and an
        exchange where to publish messages.
*/

/*
    name: $queue
    passive: false
    durable: true // the queue will survive server restarts
    exclusive: false // the queue can be accessed in other channels
    auto_delete: false //the queue won't be deleted once the channel is closed.
*/
$ch->queue_declare($queue, false, true, false, false);

/*
    name: $exchange
    type: direct
    passive: false
    durable: true // the exchange will survive server restarts
    auto_delete: false //the exchange won't be deleted once the channel is closed.
*/

$ch->exchange_declare($exchange, 'topic', false, true, false);

$ch->queue_bind($queue, $exchange,$routingKey);

function process_message($msg)
{
    //echo 'timestamp='.$msg->timestamp.PHP_EOL;
    //echo 'app_id='.$msg->properties->app_id.PHP_EOL;
    echo PHP_EOL.PHP_EOL.'#.error='.$msg->body.PHP_EOL.PHP_EOL;
    
    //var_dump($msg);
    

    $msg->delivery_info['channel']->
        basic_ack($msg->delivery_info['delivery_tag']);

    // Send a message with the string "quit" to cancel the consumer.
    if ($msg->body === 'quit') {
        $msg->delivery_info['channel']->basic_cancel($msg->delivery_info['consumer_tag']);
    }
}

/*
    queue: Queue from where to get the messages
    consumer_tag: Consumer identifier
    no_local: Don't receive messages published by this consumer.
    no_ack: Tells the server if the consumer will acknowledge the messages.
    exclusive: Request exclusive consumer access, meaning only this consumer can access the queue
    nowait:
    callback: A PHP Callback
*/

$ch->basic_consume($queue, $consumer_tag, false, false, false, false, 'process_message');

function shutdown($ch, $conn)
{
    $ch->close();
    $conn->close();
}
register_shutdown_function('shutdown', $ch, $conn);

// Loop as long as the channel has callbacks registered
while (count($ch->callbacks)) {
    $ch->wait();
}