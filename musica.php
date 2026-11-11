<?php
/**
 * @title Módulo de música
 * @description Lógica para subir y borrar audios.
 * @author Karen Arumil Lima Uranga, Diego Sierra Legorreta
 * @date 2026-11-13
 */

session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$mensaje = '';
$tipo_alerta = '';

$host = 'localhost';
$dbname = 'portal_multimedia';
$username = 'usuario_multimedia';
$password = 'ibero2026';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

$directorio_subida = 'uploads/musica/';
if (!file_exists($directorio_subida)) {
    mkdir($directorio_subida, 0777, true);
}

if (isset($_GET['borrar'])) {
    $id_borrar = $_GET['borrar'];
    
    $stmt = $pdo->prepare("SELECT ruta_archivo FROM musica WHERE id_musica = ? AND id_usuario = ?");
    $stmt->execute([$id_borrar, $id_usuario]);
    $archivo = $stmt->fetch();
    
    if ($archivo) {
        if (file_exists($archivo['ruta_archivo'])) {
            unlink($archivo['ruta_archivo']);
        }
        $stmt_delete = $pdo->prepare("DELETE FROM musica WHERE id_musica = ? AND id_usuario = ?");
        $stmt_delete->execute([$id_borrar, $id_usuario]);
        $mensaje = "Canción eliminada correctamente.";
        $tipo_alerta = "success";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['subir_musica'])) {
    $titulo = trim($_POST['titulo']);
    $artista = trim($_POST['artista']);
    
    if (isset($_FILES['archivo_audio']) && $_FILES['archivo_audio']['error'] == 0) {
        $nombre_archivo = time() . '_' . basename($_FILES['archivo_audio']['name']);
        $ruta_final = $directorio_subida . $nombre_archivo;
        $tipo_archivo = strtolower(pathinfo($ruta_final, PATHINFO_EXTENSION));
        
        if (in_array($tipo_archivo, ['mp3', 'wav', 'ogg'])) {
            if (move_uploaded_file($_FILES['archivo_audio']['tmp_name'], $ruta_final)) {
                $stmt = $pdo->prepare("INSERT INTO musica (id_usuario, titulo, artista, ruta_archivo) VALUES (?, ?, ?, ?)");
                $stmt->execute([$id_usuario, $titulo, $artista, $ruta_final]);
                $mensaje = "Canción subida con éxito.";
                $tipo_alerta = "success";
            } else {
                $mensaje = "Error al guardar el archivo en el servidor.";
                $tipo_alerta = "error";
            }
        } else {
            $mensaje = "Formato no permitido. Solo se aceptan archivos MP3, WAV u OGG.";
            $tipo_alerta = "error";
        }
    }
}

$stmt = $pdo->prepare("SELECT * FROM musica WHERE id_usuario = ? ORDER BY fecha_subida DESC");
$stmt->execute([$id_usuario]);
$canciones = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Música - Portal Multimedia</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap');
        
        body { font-family: 'Roboto', Arial, sans-serif; background-color: #f9f9f9; color: #0f0f0f; margin: 0; padding: 0; }
        
        .navbar { background-color: #ffffff; position: sticky; top: 0; z-index: 100; border-bottom: 1px solid #e5e5e5; }
        .navbar-content { max-width: 1000px; margin: 0 auto; padding: 12px 20px; display: flex; justify-content: space-between; align-items: center; }
        .navbar h1 { font-size: 20px; font-weight: 700; margin: 0; letter-spacing: -0.5px; display: flex; align-items: center; gap: 8px; }
        .user-controls { font-size: 14px; color: #606060; }
        .user-controls a { text-decoration: none; color: #065fd4; font-weight: 500; margin-left: 10px; }
        
        .container { max-width: 1000px; margin: 24px auto; padding: 0 20px; }
        
        .chips-nav { display: flex; gap: 12px; margin-bottom: 24px; border-bottom: 1px solid #e5e5e5; padding-bottom: 16px; }
        .chips-nav a { text-decoration: none; background-color: #f2f2f2; color: #0f0f0f; padding: 8px 16px; border-radius: 8px; font-size: 14px; font-weight: 500; transition: background 0.2s; }
        .chips-nav a:hover { background-color: #e5e5e5; }
        .chips-nav a.active { background-color: #0f0f0f; color: #ffffff; }

        .card { background: #ffffff; border-radius: 12px; padding: 24px; border: 1px solid #e5e5e5; margin-bottom: 24px; }
        .card h2 { margin-top: 0; font-size: 18px; margin-bottom: 20px; font-weight: 600; }

        .upload-form { display: flex; gap: 16px; align-items: flex-end; flex-wrap: wrap; margin-bottom: 8px; }
        .form-group { flex: 1; min-width: 200px; }
        .form-group label { display: block; font-size: 14px; color: #606060; margin-bottom: 6px; }
        input[type="text"], input[type="file"] { width: 100%; padding: 10px 12px; border: 1px solid #ccc; border-radius: 8px; box-sizing: border-box; font-family: inherit; font-size: 14px; height: 40px; }
        input:focus { outline: none; border-color: #065fd4; }

        .btn { color: #ffffff; border: none; padding: 0 20px; border-radius: 18px; cursor: pointer; font-weight: 500; font-size: 14px; font-family: inherit; width: 100%; height: 40px; }
        .btn-primary { background-color: #065fd4; }
        .btn-primary:hover { background-color: #0056b3; }
        
        .btn-delete { color: #cc0000; text-decoration: none; font-size: 14px; font-weight: 500; }
        .btn-delete:hover { text-decoration: underline; }

        .song-item { display: flex; align-items: center; justify-content: space-between; padding: 16px 0; border-bottom: 1px solid #eee; flex-wrap: wrap; gap: 16px; }
        .song-item:last-child { border-bottom: none; padding-bottom: 0; }
        .song-info { flex: 1; min-width: 200px; }
        .song-title { font-size: 16px; font-weight: 500; margin: 0 0 4px 0; }
        .song-artist { font-size: 14px; color: #606060; margin: 0; }
        audio { height: 40px; outline: none; }

        .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 24px; font-size: 14px; font-weight: 500; }
        .alert-error { background-color: #fce8e6; color: #c5221f; }
        .alert-success { background-color: #e6f4ea; color: #137333; }
    </style>
</head>
<body>

<div class="navbar">
    <div class="navbar-content">
        <h1>Portal Multimedia</h1>
        <div class="user-controls">
            Hola, <b><?php echo htmlspecialchars($_SESSION['nombre_completo']); ?></b>
            <a href="login.php?logout=1">Cerrar sesión</a>
        </div>
    </div>
</div>

<div class="container">
    <div class="chips-nav">
        <a href="index.php">Inicio</a>
        <a href="musica.php" class="active">Música</a>
        <a href="fotos.php">Fotos</a>
        <a href="videos.php">Videos</a>
    </div>

    <?php if (!empty($mensaje)): ?>
        <div class="alert <?php echo $tipo_alerta === 'success' ? 'alert-success' : 'alert-error'; ?>">
            <?php echo $mensaje; ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <h2>Subir canción</h2>
        <form method="POST" action="" enctype="multipart/form-data" class="upload-form">
            <div class="form-group">
                <label>Título:</label>
                <input type="text" name="titulo" required>
            </div>
            <div class="form-group">
                <label>Artista:</label>
                <input type="text" name="artista">
            </div>
            <div class="form-group">
                <label>Archivo (MP3, WAV):</label>
                <input type="file" name="archivo_audio" accept="audio/*" required>
            </div>
            <div class="form-group" style="flex: 0;">
                <button type="submit" name="subir_musica" class="btn btn-primary" style="white-space: nowrap;">Subir archivo</button>
            </div>
        </form>
    </div>

    <div class="card">
        <h2>Mi biblioteca de música</h2>
        <?php if (count($canciones) > 0): ?>
            <?php foreach ($canciones as $cancion): ?>
                <div class="song-item">
                    <div class="song-info">
                        <p class="song-title"><?php echo htmlspecialchars($cancion['titulo']); ?></p>
                        <p class="song-artist"><?php echo htmlspecialchars($cancion['artista']); ?></p>
                    </div>
                    <div>
                        <audio controls>
                            <source src="<?php echo htmlspecialchars($cancion['ruta_archivo']); ?>" type="audio/mpeg">
                            Tu navegador no soporta el reproductor de audio.
                        </audio>
                    </div>
                    <div>
                        <a href="?borrar=<?php echo $cancion['id_musica']; ?>" class="btn-delete" onclick="return confirm('¿Seguro que deseas eliminar esta canción?');">Eliminar</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="color: #606060; font-size: 14px;">No has subido ninguna canción.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>