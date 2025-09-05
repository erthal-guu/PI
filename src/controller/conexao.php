<?php

$server ="localhost";
$user = "root";
$password = "1234";
$database = "selfmed_db";

$connection =new mysqli($server,$user,$password,$database);

if ($connection->connect_error){
    die ('Erro de conexão'.$connection->connect_error);
}
?>