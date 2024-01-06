<?php

$dbhost = "";
$dbuser = "";
$dbpass = "";
$dbname = "";

if(!$con = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname))
{

	die("failed to connect!");
}