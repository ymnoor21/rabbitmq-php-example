<?php

   require_once __DIR__ . '/vendor/autoload.php';
   use PhpAmqpLib\Connection\AMQPConnection;
   use PhpAmqpLib\Message\AMQPMessage;

   $message = 'Yo Yamin, How you doin!';

   $connection = new AMQPConnection('localhost', 5672, 'root', 'foobar');
   $channel    = $connection->channel();
   $channel->queue_declare('hello', false, false, false, false);
   
   $msg = new AMQPMessage($message);
   $channel->basic_publish($msg, '', 'hello');
   
   echo " [x] Sent '$message'\n";
   
   $channel->close();
   $connection->close();