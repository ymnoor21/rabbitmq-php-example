<?php
   require_once __DIR__ . '/vendor/autoload.php';
   require_once __DIR__ . '/config.inc.php';
   
   use PhpAmqpLib\Connection\AMQPConnection;

   $connection = new AMQPConnection(RABBITMQ_HOST, RABBITMQ_PORT, RABBITMQ_USER, RABBITMQ_PASS);
   $channel    = $connection->channel();

   $channel->exchange_declare('direct_logs', 'direct', false, false, false);

   list($queue_name, ,) = $channel->queue_declare("", false, false, true, false);

   $severities = array_slice($argv, 1);
   if(empty($severities )) {
      file_put_contents('php://stderr', "Usage: $argv[0] [info] [warning] [error]\n");
      exit(1);
   }

   foreach($severities as $severity) {
      $channel->queue_bind($queue_name, 'direct_logs', $severity);
   }

   echo ' [*] Waiting for logs. To exit press CTRL+C', "\n";

   $callback = function($msg){
      echo ' [x] ',$msg->delivery_info['routing_key'], ':', $msg->body, "\n";
   };

   $channel->basic_consume($queue_name, '', false, true, false, false, $callback);

   while(count($channel->callbacks)) {
      $channel->wait();
   }

   $channel->close();
   $connection->close();