<?php
include("connect_db.php");

// Funkcja usuwająca użytkownika
function deleteUser($user_id) {
    global $conn;

    $sql_delete_user = "DELETE FROM customers WHERE customer_id = ?";
    $stmt_delete_user = $conn->prepare($sql_delete_user);
    $stmt_delete_user->bind_param("i", $user_id);
    $stmt_delete_user->execute();
}

// Funkcja usuwająca rezerwację
function deleteReservation($reservation_id) {
    global $conn;

    $sql_delete_reservation = "DELETE FROM reservations WHERE reservation_id = ?";
    $stmt_delete_reservation = $conn->prepare($sql_delete_reservation);
    $stmt_delete_reservation->bind_param("i", $reservation_id);
    $stmt_delete_reservation->execute();
}

// Obsługa usuwania użytkownika
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id'])) {
    $user_id = $_POST["user_id"];
    deleteUser($user_id);
    header("Location: dashboard.php"); // Przekierowanie z powrotem do strony dashboard.php po usunięciu użytkownika
    exit();
}

// Obsługa usuwania rezerwacji
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reservation_id'])) {
    $reservation_id = $_POST["reservation_id"];
    deleteReservation($reservation_id);
    header("Location: dashboard.php"); // Przekierowanie z powrotem do strony dashboard.php po usunięciu rezerwacji
    exit();
}
?>
