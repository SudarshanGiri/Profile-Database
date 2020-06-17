<?php
try{
$servername = 'localhost';
$username = 'root';
$password ='';
$dbname='misc';

$pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

}catch(PDOException $e){
    echo 'Error: ' .$e->getMessage();
}
