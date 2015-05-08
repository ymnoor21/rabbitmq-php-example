<?php
   require_once __DIR__ . '/vendor/autoload.php';
   require_once __DIR__ . '/config.inc.php';
   
   use PhpAmqpLib\Connection\AMQPConnection;
   use PhpAmqpLib\Message\AMQPMessage;

   $connection = new AMQPConnection(RABBITMQ_HOST, RABBITMQ_PORT, RABBITMQ_USER, RABBITMQ_PASS);

   $channel    = $connection->channel();
   $channel->queue_declare('hello', false, false, false, false);

   $message = 'Yo Yamin, How you doin!';
   
   $msg = new AMQPMessage($message);
   $channel->basic_publish($msg, '', 'hello');
   
   echo " [x] Sent '$message'\n";
   
   $channel->close();
   $connection->close();