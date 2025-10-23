[![Review Assignment Due Date](https://classroom.github.com/assets/deadline-readme-button-22041afd0340ce965d47ae6ef1cefeee28c7c493a6346c4f15d667ab976d596c.svg)](https://classroom.github.com/a/ihy5NjAu)
Este **proyecto PHP puro con SQLite** implementa registro y login usando `password_hash()` y `password_verify()`.
***

# Proyecto: Sistema de Autenticación con SQLite y Hash Seguro

## Resumen
Este proyecto demuestra cómo crear usuarios y validar contraseñas usando `password_hash()` y `password_verify()` con **SQLite** y **PDO**.
El objetivo es almacenar contraseñas de manera segura y autenticarlas sin errores de comparación directa.

***

## Estructura del proyecto
```
/proyecto-login/
│
├─ database/
│  └─ usuarios.db
│
├─ conexion.php
├─ registro.php
├─ login.php
├─ crear_tabla.php
└─ README.md
```

***

## 1. Base de datos: `crear_tabla.php`
Este script crea la base SQLite y la tabla `usuarios`.

```php
<?php
try {
    $db = new PDO("sqlite:database/usuarios.db");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $db->exec("CREATE TABLE IF NOT EXISTS usuarios (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        usuario TEXT UNIQUE,
        password TEXT NOT NULL
    )");

    echo "Base de datos y tabla creadas correctamente.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
```

***

## 2. Conexión: `conexion.php`
Conecta con la base SQLite mediante **PDO**.

```php
<?php
function conectar() {
    try {
        $db = new PDO("sqlite:database/usuarios.db");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    } catch (PDOException $e) {
        die("Error de conexión: " . $e->getMessage());
    }
}
?>
```

***

## 3. Registro de usuario: `registro.php`

```php
<?php
include('conexion.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = $_POST["usuario"];
    $clave = $_POST["clave"];
    $hash = password_hash($clave, PASSWORD_DEFAULT);

    $db = conectar();
    $stmt = $db->prepare("INSERT INTO usuarios (usuario, password) VALUES (?, ?)");
    if ($stmt->execute([$usuario, $hash])) {
        echo "Usuario registrado correctamente.";
    } else {
        echo "Error al registrar.";
    }
}
?>

<form method="POST">
  Usuario: <input type="text" name="usuario" required><br>
  Contraseña: <input type="password" name="clave" required><br>
  <button type="submit">Registrar</button>
</form>
```

***

## 4. Inicio de sesión: `login.php`

```php
<?php
include('conexion.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = $_POST["usuario"];
    $clave = $_POST["clave"];

    $db = conectar();
    $stmt = $db->prepare("SELECT password FROM usuarios WHERE usuario = ?");
    $stmt->execute([$usuario]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && password_verify($clave, $row["password"])) {
        echo "Inicio de sesión correcto.";
    } else {
        echo "Usuario o contraseña incorrectos.";
    }
}
?>

<form method="POST">
  Usuario: <input type="text" name="usuario" required><br>
  Contraseña: <input type="password" name="clave" required><br>
  <button type="submit">Ingresar</button>
</form>
```

***

## 5. Documentación técnica (README.md)

### Esquema de trabajo
- **Registro:**  
  - La contraseña se procesa con `password_hash()`.  
  - El hash resultante se guarda directamente en la base de datos SQLite.

- **Login:**  
  - Se obtiene el hash del usuario desde la base.  
  - `password_verify()` compara internamente la contraseña ingresada con el hash almacenado.

### Aspectos clave de seguridad
- `password_hash()` crea un *salt* aleatorio y lo incluye en el hash.
- `password_verify()` extrae ese *salt* y verifica sin necesidad de almacenar datos adicionales.[4][5]
- Los hashes cambian cada vez, incluso con contraseñas iguales, garantizando unicidad y resistencia ante ataques.

### Dependencias y configuración
- PHP 8.0 o superior con extensión `pdo_sqlite` habilitada.
- Carpeta `database/` debe existir y ser escribible (CHMOD 775).

### Ejecución
1. Crea la base con `crear_tabla.php` (ejecutar una sola vez).  
2. Registra usuarios en `registro.php`.  
3. Inicia sesión desde `login.php`.

### Resultados esperados
- Cada usuario se almacena con una contraseña encriptada.  
- El login valida correctamente sin errores de comparación.  
- La base `usuarios.db` contendrá entradas en texto no legible.



Este proyecto puede ejecutarse o bien en cualquier entorno local (XAMPP, Laragon, VSCode + PHP Server) o bien completamente autocontenido, sin necesidad de servidor MySQL.


