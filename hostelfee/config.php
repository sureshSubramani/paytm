<?php

error_reporting(E_ALL ^ E_DEPRECATED);
//mysql_connect("localhost","mahendrg_mahendr","Kvuglbmp9e4;") or die("CONECTION ERROR");
//mysql_select_db("mahendrg_mahendra") or die("DATABASE ERROR");


$SERVER 	= "localhost";
$USERNAME 	= "mahendrg_mahendr";
$PASSWORD 	= "Kvuglbmp9e4;";
$DBNAME 	= "mahendra_org";

// Create connection
$con = new mysqli($SERVER, $USERNAME, $PASSWORD, $DBNAME);
mysqli_select_db($con,$DBNAME) or die("DATABASE ERROR");

?>


