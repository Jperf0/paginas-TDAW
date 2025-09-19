<?php
require __DIR__ . '/db.php';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
  $stmt = $mysqli->prepare('DELETE FROM usuarios WHERE id = ?');
  if ($stmt) {
    $stmt->bind_param('i', $id);
    $stmt->execute();
  }
}
header('Location: mostrar_datos.php');