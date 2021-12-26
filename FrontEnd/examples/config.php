<?php

// session_start();

// $host = "localhost";
// $user = "root"
// $password = "";
// $dbname = "ad_evaluator";

// $con = mysqli_connect($host,$user,$password,$dbname);

// if(!$con){
//     die("Connection Failed: ".mysqli_connect_error());

// }

define('DB_SERVER','localhost');
define('DB_USER','root');
define('DB_PASS' ,'');
define('DB_NAME', 'ad_evaluator');
$con = mysqli_connect(DB_SERVER,DB_USER,DB_PASS,DB_NAME);

// Check connection
if (mysqli_connect_errno())
{
echo "Failed to connect to MySQL: " . mysqli_connect_error();
 }



?>