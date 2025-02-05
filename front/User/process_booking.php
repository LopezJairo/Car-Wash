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

// Verificar si los datos vienen por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar que los campos existen antes de usarlos
    $modelo = isset($_POST['modelo']) ? $_POST['modelo'] : null;
    $marca = isset($_POST['marca']) ? $_POST['marca'] : null;
    $matricula = isset($_POST['matricula']) ? $_POST['matricula'] : null;
    $tipo = isset($_POST['tipo']) ? $_POST['tipo'] : null;
    $fecha = isset($_POST['fecha']) ? $_POST['fecha'] : null;
    $id_servicio = isset($_POST['id_servicio']) ? $_POST['id_servicio'] : null;
    $id_usuario = isset($_POST['id_usuario']) ? $_POST['id_usuario'] : null; // Asegurarse de que se pase el ID del usuario

    // Verificar que los datos no estén vacíos
    if (!$modelo || !$marca || !$matricula || !$tipo || !$fecha || !$id_servicio || !$id_usuario) {
        die("Error: Faltan datos en el formulario.");
    }

    $conn->begin_transaction();

    // Insertar vehículo usando prepared statements
    $sql = "INSERT INTO vehiculos (modelo, marca, matricula, tipo, cliente_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $modelo, $marca, $matricula, $tipo, $id_usuario);

    if (!$stmt->execute()) {
        echo "Error al registrar vehículo: " . $stmt->error;
        $conn->rollback();
        exit();
    }

    // Obtener el ID del vehículo insertado
    $vehiculo_id = $stmt->insert_id;
    $stmt->close();

    // Insertar turno
    $sql_turno = "INSERT INTO turnos (cliente_id, vehiculo_id, servicio_id, fecha, estado) VALUES (?, ?, ?, ?, 'Nuevo')";
    $stmt_turno = $conn->prepare($sql_turno);
    $stmt_turno->bind_param("iiis", $id_usuario, $vehiculo_id, $id_servicio, $fecha);

    if (!$stmt_turno->execute()) {
        echo "Error al registrar turno: " . $stmt_turno->error;
        $conn->rollback();
        exit();
    }

    // Confirmar transacción
    $conn->commit();

    echo "Registro exitoso";
    header("Location: ../Login/Login.html"); // Redirigir después de registrar
    exit();
} else {
    echo "Método no permitido.";
}
?>
