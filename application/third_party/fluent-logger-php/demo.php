<?php

function fluent_logger(array $logger_data) {
    require_once dirname(__DIR__) .'/fluent-logger-php/src/Fluent/Autoloader.php';
    Fluent\Autoloader::register();
    $logger = Fluent\Logger\FluentLogger::open("localhost","24224");
    $logger->post('waijiao.access', $logger_data);
}

$logger_data = array(
    'uid' => 'nginx_uid2',
    'userid' => 'userid',
    'host' => 'www.91waijiao.com',
    'business' => 'buy', ## buy
    'status' => 1, # success 1 failure 0
    'money' => 10
);

fluent_logger($logger_data); 
