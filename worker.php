<?php
   require_once __DIR__ . '/vendor/autoload.php';
   require_once __DIR__ . '/config.inc.php';
   
   use PhpAmqpLib\Connection\AMQPConnection;
   use PhpAmqpLib\Message\AMQPMessage;

   $connection = new AMQPConnection(RABBITMQ_HOST, RABBITMQ_PORT, RABBITMQ_USER, RABBITMQ_PASS);
   $channel    = $connection->channel();

   $channel->queue_declare('task_queue', false, true, false, false);

   echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";

   $callback = function($msg){
      echo " [x] Received ", $msg->body, "\n";
      sleep(substr_count($msg->body, '.'));
      echo " [x] Done", "\n";
      $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
   };

   $channel->basic_qos(null, 1, null);
   $channel->basic_consume('task_queue', '', false, false, false, false, $callback);

   while(count($channel->callbacks)) {
      $channel->wait();
   }

   $channel->close();
   $connection->close();