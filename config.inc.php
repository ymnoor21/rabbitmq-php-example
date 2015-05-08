<?php
   /**
    * @author: Yamin Noor
    * @purpose: Configuration for RabbitMQ Php Example Scripts
    * @date: May 08, 2015
   */

   //please visit this page (https://www.rabbitmq.com/man/rabbitmqctl.1.man.html) to get 
   //instruction for manually setup a new user and permission;
   //default user access should be deleted in the production mode;

   //Change it to match your RabbitMQ server; 
   //Please remember that you cannot use localhost when using a remote server.
   //So change it accordingly to match your host name
   define('RABBITMQ_HOST', 'localhost');

   //default RabbitMQ port no
   define('RABBITMQ_PORT', 5672);

   //default user (guest) created by RabbitMQ when installing;
   //you should always setup a new user after RabbitMQ finishes installing
   define('RABBITMQ_USER', 'guest');

   //default pass (guest). Please change your password when creating a new RabbitMQ user
   define('RABBITMQ_PASS', 'guest');