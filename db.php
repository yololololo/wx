<?php
$host='localhost';
$dbname='my';
$user="root";
$password="root";
$dsn="mysql:host=$host;dbname=$dbname";
		try{
			 $pdo=new PDO($dsn,$user,$password,array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET NAMES 'utf8';"));
		}catch(PDOException $e){
			die("错误：".$e->getMessage());
		}
