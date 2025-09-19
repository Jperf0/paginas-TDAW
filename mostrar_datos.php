<?php
require __DIR__ . '/db.php';

// --- Paginación ---
$perPage = 5;                              // cantidad por página
$page    = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset  = ($page - 1) * $perPage;

// Total de registros para calcular páginas
$total = 0;
$resCount = $mysqli->query('SELECT COUNT(*) AS c FROM usuarios');
if ($resCount && $rc = $resCount->fetch_assoc()) { $total = (int)$rc['c']; }
$pages = max(1, (int)ceil($total / $perPage));

// Consulta paginada y ordenada
$stmt = $mysqli->prepare('SELECT id, nombre, email, edad FROM usuarios ORDER BY id DESC LIMIT ?, ?');
$stmt->bind_param('ii', $offset, $perPage);
$stmt->execute();
$res = $stmt->get_result();
if ($res === false) { die('Error en SELECT: ' . $mysqli->error); }
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Usuarios Registrados</title>
  <link rel="stylesheet" href="styles.css">
  <style>
    /* Estilos mínimos de respaldo por si no carga styles.css */
    body{font-family: system-ui, Arial, sans-serif; max-width:900px; margin:40px auto}
    table{border-collapse: collapse; width:100%}
    th,td{border:1px solid #ddd; padding:8px; text-align:left}
    th{background:#f5f5f5}
    a{color:#0b5bd3; text-decoration:none}
    .pager{margin-top:16px; display:flex; gap:12px; align-items:center}
  </style>
</head>
<body>
  <h1>Usuarios Registrados</h1>
  <?php if ($res->num_rows > 0): ?>
    <table>
      <thead>
        <tr><th>ID</th><th>Nombre</th><th>Email</th><th>Edad</th><th>Acciones</th></tr>
      </thead>
      <tbody>
      <?php while($row = $res->fetch_assoc()): ?>
        <tr>
          <td><?= (int)$row['id'] ?></td>
          <td><?= htmlspecialchars($row['nombre'], ENT_QUOTES, 'UTF-8') ?></td>
          <td><?= htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8') ?></td>
          <td><?= (int)$row['edad'] ?></td>
          <td>
            <a href="editar.php?id=<?= (int)$row['id'] ?>">Editar</a> |
            <a href="eliminar.php?id=<?= (int)$row['id'] ?>" onclick="return confirm('¿Seguro?')">Eliminar</a>
          </td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>

    <div class="pager">
      <?php if ($page > 1): ?>
        <a href="?page=<?= $page-1 ?>">← Anterior</a>
      <?php endif; ?>
      <span>Página <?= $page ?> de <?= $pages ?></span>
      <?php if ($page < $pages): ?>
        <a href="?page=<?= $page+1 ?>">Siguiente →</a>
      <?php endif; ?>
    </div>
  <?php else: ?>
    <p>No hay usuarios registrados.</p>
  <?php endif; ?>
  <p style="margin-top:16px"><a href="formulario.html">← Volver al formulario</a></p>
</body>
</html>
<?php
$res->free();
$stmt->close();
$mysqli->close();