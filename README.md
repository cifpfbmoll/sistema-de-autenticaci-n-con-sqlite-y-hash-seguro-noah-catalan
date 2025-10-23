# Sistema de Login con PHP y SQLite

Proyecto de autenticación de usuarios con PHP puro y SQLite que hice para practicar seguridad en contraseñas y bases de datos.

## Lo que hice

### 1. Estructura del proyecto

Primero creé todos los archivos necesarios:

```
php-vanilla-login/
│
├─ database/
│  └─ usuarios.db          (se crea al ejecutar crear_tabla.php)
│
├─ index.php               (página principal con menú)
├─ registro.php            (formulario para registrarse)
├─ login.php               (formulario para iniciar sesión)
├─ conexion.php            (función para conectar con la BD)
├─ crear_tabla.php         (script que crea la base de datos)
└─ styles.css              (estilos del proyecto)
```

### 2. Configuración de la base de datos

Creé `conexion.php` con una función que usa PDO para conectarse a SQLite:
- No necesita MySQL ni servidor de BD
- Todo se guarda en un archivo `usuarios.db`
- Manejo de errores con try-catch

Luego hice `crear_tabla.php` que crea la tabla de usuarios con:
- id (autoincremental)
- usuario (único)
- password (el hash)

### 3. Sistema de registro

En `registro.php` implementé:
- Formulario HTML para usuario y contraseña
- Validación de campos vacíos
- `password_hash()` para encriptar contraseñas
- Detección de usuarios duplicados
- Mensajes de éxito o error

### 4. Sistema de login

En `login.php` agregué:
- Formulario de inicio de sesión
- Consulta preparada para buscar el usuario
- `password_verify()` para comparar contraseñas
- Mensajes personalizados de bienvenida o error

### 5. Diseño y estilos

Creé `styles.css` con:
- Tema oscuro moderno (gradientes púrpura/azul)
- Efecto glassmorphism en las tarjetas
- Formularios con focus effects
- Mensajes de éxito (verde) y error (rojo)
- Animaciones suaves en hover y entrada
- Responsive para móviles

### 6. Página principal

Hice `index.php` como landing page con:
- Icono de candado
- Enlaces a registro y login
- Diseño coherente con el resto

## Cómo lo levanté

**Paso 1:** Verifiqué que tenía PHP instalado
```bash
php --version
```
Me salió PHP 8.2.12, así que todo ok.

**Paso 2:** Creé la base de datos ejecutando:
```bash
php crear_tabla.php
```
Esto me creó la carpeta `database/` y el archivo `usuarios.db` con la tabla de usuarios.

**Paso 3:** Levanté el servidor de desarrollo de PHP:
```bash
php -S localhost:8000
```

**Paso 4:** Abrí el navegador en `http://localhost:8000/` y ya funcionaba todo.

## Tecnologías usadas

- **PHP 8.2** - Lenguaje del backend
- **SQLite** - Base de datos (no necesita servidor)
- **PDO** - Para conectar con la base de datos
- **password_hash() / password_verify()** - Funciones de PHP para encriptar contraseñas
- **CSS3** - Para los estilos

## Seguridad implementada

Las contraseñas no se guardan en texto plano. Uso `password_hash()` que genera un hash único cada vez:

```
Contraseña: miPassword123
Hash guardado: $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi
```

Al hacer login, `password_verify()` compara la contraseña ingresada con el hash guardado. Así aunque alguien acceda a la base de datos, no puede ver las contraseñas reales.

También uso consultas preparadas con PDO para evitar inyecciones SQL.

## Problemas que tuve

- Al principio los mensajes de error no se veían bien, así que agregué clases CSS para diferenciar éxitos de errores
- Había que validar que no se registren usuarios duplicados, lo resolví con un try-catch que detecta el error 23000
- Mejore la UX agregando placeholders y validación de campos vacíos


