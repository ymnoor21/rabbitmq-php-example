<?php
   require_once __DIR__ . '/vendor/autoload.php';
   require_once __DIR__ . '/config.inc.php';
   
   use PhpAmqpLib\Connection\AMQPConnection;

   $connection = new AMQPConnection(RABBITMQ_HOST, RABBITMQ_PORT, RABBITMQ_USER, RABBITMQ_PASS);
   $channel = $connection->channel();
   $channel->queue_declare('hello', false, false, false, false);

   echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";
   $callback = function($msg) {
      echo " [x] Received ", $msg->body, "\n";
   };

   $channel->basic_consume('hello', '', false, true, false, false, $callback);
   
   while(count($channel->callbacks)) {
      $channel->wait();
   }
   
   $channel->close();
   $connection->close();
