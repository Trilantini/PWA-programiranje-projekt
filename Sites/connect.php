<?php

header("Content-Type: text/html; charset=utf-8");

$servername = "localhost";
$username = "root";
$pass = "";
$dbname = "daniel_gluhak_pwa";

$dbc = mysqli_connect($servername,$username,$pass,$dbname) or die ("Error connecting to MYSQL".mysqli_connect_error());
