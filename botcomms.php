<?php
session_start();
//require_once 'pdo.php';
$HTTP_TOKEN = '1751648833:AAGwoBQhbU6TXvLcfGX-EP6Yvk6CYXyF9Vw';

$url='https://api.telegram.org/bot1751648833:AAGwoBQhbU6TXvLcfGX-EP6Yvk6CYXyF9Vw/getUpdates';
$site = file_get_contents($url);
echo($site);
echo("<br><br><br>");
var_dump(json_decode($site,true));

 ?>
