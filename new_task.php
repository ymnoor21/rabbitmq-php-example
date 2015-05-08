<?php
   require_once __DIR__ . '/vendor/autoload.php';
   require_once __DIR__ . '/config.inc.php';
   
   use PhpAmqpLib\Connection\AMQPConnection;
   use PhpAmqpLib\Message\AMQPMessage;

   $connection = new AMQPConnection(RABBITMQ_HOST, RABBITMQ_PORT, RABBITMQ_USER, RABBITMQ_PASS);
   $channel = $connection->channel();

   $channel->queue_declare('task_queue', false, true, false, false);

   $data = implode(' ', array_slice($argv, 1));
   
   if(empty($data)) $data = "Hello World!";
   
   $msg = new AMQPMessage($data,
                        array('delivery_mode' => 2) # make message persistent
                     );

   $channel->basic_publish($msg, '', 'task_queue');

   echo " [x] Sent ", $data, "\n";

   $channel->close();
   $connection->close();