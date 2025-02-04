<?php
require_once 'db_connection.php';

// Aquí se procesan las acciones desde la pantalla de administrador

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos enviados por POST
    $accion = $_POST['accion'];
    $turno_id = $_POST['turno_id'];

    switch ($accion) {
        case 'ver':
            // Lógica para ver los detalles del turno
            $sql = "SELECT * FROM turnos WHERE id = :turno_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':turno_id', $turno_id);
            $stmt->execute();
            $detalles = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($detalles) {
                echo json_encode(['status' => 'success', 'details' => $detalles]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Turno no encontrado']);
            }
            break;
        case 'editar':
            // Lógica para editar el turno
            $nuevaFechaHora = $_POST['nuevaFechaHora'];
            $sql = "UPDATE turnos SET fecha_hora = :nuevaFechaHora WHERE id = :turno_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':nuevaFechaHora', $nuevaFechaHora);
            $stmt->bindParam(':turno_id', $turno_id);
            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Turno editado exitosamente']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error al editar el turno']);
            }
            break;
        case 'eliminar':
            // Lógica para eliminar el turno
            $sql = "DELETE FROM turnos WHERE id = :turno_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':turnoId', $turno_id);
            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Turno eliminado exitosamente']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error al eliminar el turno']);
            }
            break;
        default:
            echo json_encode(['status' => 'error', 'message' => 'Acción no válida']);
            break;
    }
}
?>
