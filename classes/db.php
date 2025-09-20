<?php
    require __DIR__ '/vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
    $dotenv->load();

    $lnk = mysql_connect("127.0.0.1", $_ENV['db_username'], $_ENV['db_password']);
    $db = mysql_select_db('cr', $lnk);
?>
