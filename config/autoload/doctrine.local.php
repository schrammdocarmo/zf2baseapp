<?php

/*return array(
  'doctrine' => array(
    'connection' => array(
      'orm_default' => array(
        'driverClass' =>'Doctrine\DBAL\Driver\PDOMySql\Driver',
        'params' => array(
          'host'     => 'localhost',
          'port'     => '3306',
          'user'     => 'YOUR_USERNAME',
          'password' => 'YOUR_PASSWORD',
          'dbname'   => 'YOUR_DATABASE',
	  'charset'  => 'utf8',
)))));*/

return array(
  'doctrine' => array(
    'connection' => array(
      'orm_default' => array(
        'driverClass' =>'Doctrine\DBAL\Driver\PDOSqlite\Driver',
        'params' => array(
          'path'     => __DIR__.'/../../data/sql/example_net.db',
)))));
