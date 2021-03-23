<?php
$hostname = getenv('DB_HOST');
$username = getenv('DB_USER');
$password = getenv('DB_PASS');
$database = getenv('DB_NAME');

$link = mysqli_connect($hostname, $username, $password, $database) or die(mysqli_connect_error($link));

?>