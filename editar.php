<?php
require __DIR__ . '/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) { die('ID inválido'); }

$stmt = $mysqli->prepare('SELECT nombre, email, edad FROM usuarios WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
if (!$row = $res->fetch_assoc()) { die('Registro no encontrado'); }
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar usuario</title>
  <link rel="stylesheet" href="estilos.css">
</head>
<body>
  <h1>Editar usuario #<?= (int)$id ?></h1>
  <form action="actualizar.php" method="POST">
    <input type="hidden" name="id" value="<?= (int)$id ?>">
    <label>Nombre</label>
    <input type="text" name="nombre" required minlength="2" maxlength="100"
           value="<?= htmlspecialchars($row['nombre'], ENT_QUOTES, 'UTF-8') ?>">
    <br><br>

    <label>Email</label>
    <input type="email" name="email" required maxlength="100"
           value="<?= htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8') ?>">
    <br><br>

    <label>Edad</label>
    <input type="number" name="edad" required min="1" max="120"
           value="<?= (int)$row['edad'] ?>">
    <br><br>

    <button type="submit">Guardar cambios</button>
  </form>
  <p style="margin-top:12px"><a href="mostrar_datos.php">← Volver</a></p>
</body>
</html>