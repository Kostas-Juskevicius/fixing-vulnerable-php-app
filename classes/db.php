<?php
    require __DIR__ . '/vendor/autoload.php';
    $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
    $dotenv->load();

    $lnk = mysqli_connect("127.0.0.1", $_ENV['db_username'], $_ENV['db_password'], 'cr') or die("Connection to the database failed");
?>
