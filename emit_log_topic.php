<?php
   require_once __DIR__ . '/vendor/autoload.php';
   require_once __DIR__ . '/config.inc.php';
   
   use PhpAmqpLib\Connection\AMQPConnection;
   use PhpAmqpLib\Message\AMQPMessage;

   $connection = new AMQPConnection(RABBITMQ_HOST, RABBITMQ_PORT, RABBITMQ_USER, RABBITMQ_PASS);
   $channel    = $connection->channel();

   $channel->exchange_declare('topic_logs', 'topic', false, false, false);

   $routing_key = $argv[1];

   if(empty($routing_key)) $routing_key = "anonymous.info";
   
   $data = implode(' ', array_slice($argv, 2));
   
   if(empty($data)) $data = "Hello World!";

   $msg  = new AMQPMessage($data);

   $channel->basic_publish($msg, 'topic_logs', $routing_key);

   echo " [x] Sent ",$routing_key,':',$data," \n";

   $channel->close();
   $connection->close();