<?php 
$server="localhost";
$user="";
$pass="";
$db="steamfinder";
$connect = @mysql_connect($server, $user, $pass) or die('<center><h1>Нет соединения с базой MySQL! :(</h1></center>');
mysql_select_db($db);
mysql_query("SET CHARSET utf8");
?>