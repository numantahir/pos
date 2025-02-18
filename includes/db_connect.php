<?php
//Database Connection file. Update with your Database information once you create database from cpanel, or mysql.
	define ("DB_HOST", "localhost"); //Databse Host.
	define ("DB_USER", "bolansaltish_upos"); //Databse User.
	define ("DB_PASS", "ellight1"); //database password.
	define ("DB_NAME", "bolansaltish_pos"); //database Name.

$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if($db->connect_errno > 0){
    die('Unable to connect to database ['.$db->connect_error.']');
}
date_default_timezone_set("Asia/Karachi");