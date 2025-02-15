<?php
$host = 'svr';
$dbname = 'dbname';
$username = 'uname';
$password = 'paswd';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connessione fallita: " . $e->getMessage();
}
