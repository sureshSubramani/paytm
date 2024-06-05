<?php

error_reporting(E_ALL ^ E_DEPRECATED);
//$con = mysqli_connect("localhost","root","") or die("CONECTION ERROR");
//mysqli_select_db($con,"mahendraorg") or die("DATABASE ERROR");

//mysql_connect("localhost","mahendrg_mahendr","Kvuglbmp9e4;") or die("CONECTION ERROR");
//mysql_select_db("mahendrg_mahendra") or die("DATABASE ERROR");


$SERVER 	= "localhost";
$USERNAME 	= "mahendrg_mahendr";
$PASSWORD 	= "Kvuglbmp9e4;";
$DBNAME 	= "mahendrg_mahendra";

// // Create connection
$con = new mysqli($SERVER, $USERNAME, $PASSWORD, $DBNAME);
mysqli_select_db($con,$DBNAME) or die("DATABASE ERROR"); 

?>


