<?php
require 'db_connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['id_usuario'])) {
        echo "Error: Usuario no autenticado.";
        exit();
    }

    $id_usuario = $_SESSION['id_usuario'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $modelo = $_POST['modelo'];
    $marca = $_POST['marca'];
    $matricula = $_POST['matricula'];
    $fecha_hora = $_POST['fecha_hora'];
    $servicio_id = $_POST['servicio_id'];

    $conn->beginTransaction();

    try {
        // Insertar datos en la tabla de personas si no existe
        $sql_person = "INSERT INTO personas (nombre, apellido) VALUES (:nombre, :apellido)";
        $stmt_person = $conn->prepare($sql_person);
        $stmt_person->bindParam(':nombre', $nombre);
        $stmt_person->bindParam(':apellido', $apellido);
        $stmt_person->execute();
        $persona_id = $conn->lastInsertId();

        // Insertar datos en la tabla de vehículos
        $sql_vehicle = "INSERT INTO vehiculos (modelo, marca, id_usuario) VALUES (:modelo, :marca, :id_usuario)";
        $stmt_vehicle = $conn->prepare($sql_vehicle);
        $stmt_vehicle->bindParam(':modelo', $modelo);
        $stmt_vehicle->bindParam(':marca', $marca);
        $stmt_vehicle->bindParam(':id_usuario', $persona_id);
        $stmt_vehicle->execute();
        $vehiculo_id = $conn->lastInsertId();

        // Insertar datos en la tabla de turnos
        $sql_turno = "INSERT INTO turnos (id_usuario, vehiculo_id, servicio_id, fecha_hora, estado) VALUES (:cliente_id, :vehiculo_id, :servicio_id, :fecha_hora, 'Nuevo')";
        $stmt_turno = $conn->prepare($sql_turno);
        $stmt_turno->bindParam(':id_usuario', $persona_id);
        $stmt_turno->bindParam(':vehiculo_id', $vehiculo_id);
        $stmt_turno->bindParam(':servicio_id', $servicio_id);
        $stmt_turno->bindParam(':fecha_hora', $fecha_hora);
        $stmt_turno->execute();

        $conn->commit();
        echo "Reserva realizada con éxito.";
    } catch (Exception $e) {
        $conn->rollBack();
        echo "Error: " . $e->getMessage();
    }

    $stmt_turno->close();
}
?>
