<?php
$host = 'localhost';
$db = 'test_db';
$user = 'root';
$pass = '';
// connexion pdo
$pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
