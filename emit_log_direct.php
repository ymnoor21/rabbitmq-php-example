<?php
   require_once __DIR__ . '/vendor/autoload.php';
   require_once __DIR__ . '/config.inc.php';
   
   use PhpAmqpLib\Connection\AMQPConnection;
   use PhpAmqpLib\Message\AMQPMessage;

   $connection = new AMQPConnection(RABBITMQ_HOST, RABBITMQ_PORT, RABBITMQ_USER, RABBITMQ_PASS);
   $channel    = $connection->channel();

   $channel->exchange_declare('direct_logs', 'direct', false, false, false);

   $severity = $argv[1];
   if(empty($severity)) $severity = "info";

   $data = implode(' ', array_slice($argv, 2));
   if(empty($data)) $data = "Hello World!";

   $msg = new AMQPMessage($data);

   $channel->basic_publish($msg, 'direct_logs', $severity);

   echo " [x] Sent ",$severity,':',$data," \n";

   $channel->close();
   $connection->close();