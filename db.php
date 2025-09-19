<?php
// db.php
$DB_HOST = 'localhost';
$DB_USER = 'root';     // XAMPP por defecto
$DB_PASS = '';         // XAMPP por defecto
$DB_NAME = 'formulario_db';

$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($mysqli->connect_error) {
    die('Conexión fallida: ' . $mysqli->connect_error);
}
$mysqli->set_charset('utf8mb4');