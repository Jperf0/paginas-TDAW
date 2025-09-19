<?php
require __DIR__ . '/db.php';

$id     = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$nombre = trim($_POST['nombre'] ?? '');
$email  = trim($_POST['email'] ?? '');
$edad   = isset($_POST['edad']) ? (int)$_POST['edad'] : 0;

$errores = [];
if ($id <= 0) { $errores[] = 'ID inválido.'; }
if ($nombre === '' || mb_strlen($nombre) < 2) { $errores[] = 'Nombre inválido.'; }
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { $errores[] = 'Email inválido.'; }
if ($edad <= 0) { $errores[] = 'Edad debe ser positiva.'; }

if ($errores) {
  echo '<div class="err"><b>Corrige:</b><ul>';
  foreach ($errores as $e) echo '<li>'.htmlspecialchars($e, ENT_QUOTES, 'UTF-8').'</li>';
  echo '</ul></div>';
  echo '<p><a href="editar.php?id='.(int)$id.'">← Volver a editar</a></p>';
  exit;
}

$stmt = $mysqli->prepare('UPDATE usuarios SET nombre = ?, email = ?, edad = ? WHERE id = ?');
if (!$stmt) { die('Error en prepare: ' . $mysqli->error); }
$stmt->bind_param('ssii', $nombre, $email, $edad, $id);

if ($stmt->execute()) {
  header('Location: mostrar_datos.php');
  exit;
} else {
  echo 'Error al actualizar: ' . htmlspecialchars($stmt->error, ENT_QUOTES, 'UTF-8');
}