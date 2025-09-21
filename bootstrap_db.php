<?php

// to get a ref in user.php of $lnk that was initialized in db.php
require_once __DIR__ . '/classes/db.php';
require_once __DIR__ . '/classes/user.php';

User::init($lnk)

?>
