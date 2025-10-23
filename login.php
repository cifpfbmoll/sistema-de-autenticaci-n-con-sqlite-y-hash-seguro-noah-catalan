<?php
include('conexion.php');

$mensaje = "";
$tipo_mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = trim($_POST["usuario"]);
    $clave = $_POST["clave"];
    
    if (empty($usuario) || empty($clave)) {
        $mensaje = "Todos los campos son obligatorios.";
        $tipo_mensaje = "error";
    } else {
        try {
            $db = conectar();
            $stmt = $db->prepare("SELECT password FROM usuarios WHERE usuario = ?");
            $stmt->execute([$usuario]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($row && password_verify($clave, $row["password"])) {
                $mensaje = "¡Bienvenido, " . htmlspecialchars($usuario) . "! Inicio de sesión exitoso.";
                $tipo_mensaje = "success";
            } else {
                $mensaje = "Usuario o contraseña incorrectos.";
                $tipo_mensaje = "error";
            }
        } catch (PDOException $e) {
            $mensaje = "Error al iniciar sesión: " . $e->getMessage();
            $tipo_mensaje = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="logo">
            <div class="logo-icon">🔑</div>
            <h1>Iniciar Sesión</h1>
            <p class="subtitle">Accede a tu cuenta</p>
        </div>
        
        <?php if ($mensaje): ?>
            <div class="message <?php echo $tipo_mensaje; ?>">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="usuario">Usuario</label>
                <input type="text" id="usuario" name="usuario" placeholder="Ingresa tu usuario" required>
            </div>
            
            <div class="form-group">
                <label for="clave">Contraseña</label>
                <input type="password" id="clave" name="clave" placeholder="Ingresa tu contraseña" required>
            </div>
            
            <button type="submit">Ingresar</button>
        </form>
        
        <div class="links">
            <a href="registro.php">¿No tienes cuenta? Regístrate</a>
            <br><br>
            <a href="index.php">← Volver al inicio</a>
        </div>
    </div>
</body>
</html>
