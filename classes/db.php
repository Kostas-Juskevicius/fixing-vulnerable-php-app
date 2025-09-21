<?php
    require_once __DIR__ . '/../bootstrap_env.php';

    $lnk = mysqli_connect("127.0.0.1", $_ENV['db_username'], $_ENV['db_password'], 'cr') or die("Connection to the database failed");
?>
