<?php
$host = 'lab.isit100.fe.it';
$dbname = 'info5n2425_imran';
$username = 'info5n2425';
$password = 'Zavaj=87]';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connessione fallita: " . $e->getMessage();
}
