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
        $hash = password_hash($clave, PASSWORD_DEFAULT);
        
        try {
            $db = conectar();
            $stmt = $db->prepare("INSERT INTO usuarios (usuario, password) VALUES (?, ?)");
            if ($stmt->execute([$usuario, $hash])) {
                $mensaje = "¡Usuario registrado correctamente! Ya puedes iniciar sesión.";
                $tipo_mensaje = "success";
            }
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $mensaje = "El usuario ya existe. Por favor, elige otro nombre.";
            } else {
                $mensaje = "Error al registrar: " . $e->getMessage();
            }
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
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="logo">
            <div class="logo-icon">📝</div>
            <h1>Registro</h1>
            <p class="subtitle">Crea tu cuenta</p>
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
            
            <button type="submit">Registrar</button>
        </form>
        
        <div class="links">
            <a href="login.php">¿Ya tienes cuenta? Inicia sesión</a>
            <br><br>
            <a href="index.php">← Volver al inicio</a>
        </div>
    </div>
</body>
</html>
