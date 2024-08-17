// backend/getRooms.php
<?php
header('Content-Type: application/json');
require 'db.php';  // Incluye la conexión a la base de datos

try {
    $stmt = $pdo->query('SELECT room_name FROM Rooms');
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($rooms);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
