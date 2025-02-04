<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->prepare("SELECT appointments.*, users.username, services.name AS service_name 
                       FROM appointments 
                       JOIN users ON appointments.user_id = users.id 
                       JOIN services ON appointments.service_id = services.id");
$stmt->execute();
$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>