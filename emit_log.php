<?php
   require_once __DIR__ . '/vendor/autoload.php';
   require_once __DIR__ . '/config.inc.php';
   
   use PhpAmqpLib\Connection\AMQPConnection;
   use PhpAmqpLib\Message\AMQPMessage;

   $connection = new AMQPConnection(RABBITMQ_HOST, RABBITMQ_PORT, RABBITMQ_USER, RABBITMQ_PASS);
   $channel = $connection->channel();

   $channel->exchange_declare('logs', 'fanout', false, false, false);

   $data = implode(' ', array_slice($argv, 1));
   
   if(empty($data)) $data = "info: Hello World!";
   
   $msg  = new AMQPMessage($data);

   $channel->basic_publish($msg, 'logs');

   echo " [x] Sent ", $data, "\n";

   $channel->close();
   $connection->close();