<?php
include("connect_db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['set_available'])) {
    $car_id = $_POST["car_id"];

    // Aktualizacja statusu samochodu na dostępny
    $sql_update_status = "UPDATE cars SET is_reserved = FALSE WHERE car_id = ?";
    $stmt_update_status = $conn->prepare($sql_update_status);
    $stmt_update_status->bind_param("i", $car_id);

    if ($stmt_update_status->execute()) {
        echo "Status samochodu został pomyślnie zmieniony na dostępny.";
    } else {
        echo "Coś poszło nie tak...";
    }
} else {
    echo "Nieprawidłowe żądanie.";
}
