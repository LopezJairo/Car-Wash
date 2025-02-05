<?php
// Configuración de la conexión a la base de datos
$servername = "localhost";
$username = "root"; // Cambia esto por tu usuario de MySQL
$password = ""; // Cambia esto por tu contraseña de MySQL
$dbname = "dbcarwash";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Recoger datos del formulario
$correo = $_POST['correo'];
$contrasena = $_POST['contrasena'];

// Consulta para verificar el usuario en la tabla "personas"
$sql = "SELECT * FROM personas WHERE correo = '$correo'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Usuario encontrado, verificar contraseña
    $row = $result->fetch_assoc();
    if (md5($contrasena) === $row['contrasena']) { // Comparar contraseñas encriptadas con MD5
        echo "<div class='message success'>Inicio de sesión exitoso. Bienvenido, " . $row['nombre'] . "!</div>";
        // Aquí puedes redirigir al usuario según su rol
        if ($row['rol'] === 'admin') {
            header("Location: ../Admin/index.html"); // Redirigir al panel de administrador
        } else {
            header("Location: ../User/index.html"); // Redirigir al panel de usuario
        }
        exit();
    } else {
        echo "<div class='message error'>Contraseña incorrecta.</div>";
    }
} else {
    echo "<div class='message error'>Usuario no encontrado.</div>";
}

// Cerrar conexión
$conn->close();
?>