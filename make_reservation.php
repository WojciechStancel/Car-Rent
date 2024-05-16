<?php
include("connect_db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $start_date = $_POST["start_date"];
    $end_date = $_POST["end_date"];
    $car_id = $_POST["car_id"];
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $phone_number = $_POST["phone_number"];
    $email = $_POST["email"];

    // Sprawdzenie, czy data końcowa jest późniejsza niż data rozpoczęcia
    if ($end_date <= $start_date) {
        echo "Data końcowa rezerwacji musi być późniejsza niż data rozpoczęcia.";
        exit;
    }

    // Sprawdzenie, czy klient istnieje w tabeli customers
    $sql_check_customer = "SELECT customer_id FROM customers WHERE email = ?";
    $stmt_check_customer = $conn->prepare($sql_check_customer);
    $stmt_check_customer->bind_param("s", $email);
    $stmt_check_customer->execute();
    $result_check_customer = $stmt_check_customer->get_result();

    if ($result_check_customer->num_rows > 0) {
        // Klient istnieje, pobierz jego ID
        $row_customer = $result_check_customer->fetch_assoc();
        $customer_id = $row_customer["customer_id"];
    } else {
        // Klient nie istnieje, dodaj go do tabeli customers
        $sql_add_customer = "INSERT INTO customers (first_name, last_name, phone_number, email) VALUES (?, ?, ?, ?)";
        $stmt_add_customer = $conn->prepare($sql_add_customer);
        $stmt_add_customer->bind_param("ssss", $first_name, $last_name, $phone_number, $email);
        $stmt_add_customer->execute();
        $customer_id = $stmt_add_customer->insert_id;
    }

    // Sprawdzenie, czy samochód jest zarezerwowany w wybranym terminie
    $sql_check_reservation = "SELECT * FROM reservations WHERE car_id = ? AND ((start_date <= ? AND end_date >= ?) OR (start_date <= ? AND end_date >= ?) OR (start_date >= ? AND end_date <= ?))";
    $stmt_check_reservation = $conn->prepare($sql_check_reservation);
    $stmt_check_reservation->bind_param("issssss", $car_id, $start_date, $start_date, $end_date, $end_date, $start_date, $end_date);
    $stmt_check_reservation->execute();
    $result_check_reservation = $stmt_check_reservation->get_result();

    if ($result_check_reservation->num_rows > 0) {
        // header('Location: view.php');
        echo "Samochód jest już zarezerwowany w wybranym terminie. Proszę wybrać inny termin.";
        exit;
    }


    // Obliczenie ceny rezerwacji
    $sql_get_price = "SELECT price_per_day FROM cars WHERE car_id = ?";
    $stmt_get_price = $conn->prepare($sql_get_price);
    $stmt_get_price->bind_param("i", $car_id);
    $stmt_get_price->execute();
    $result_get_price = $stmt_get_price->get_result();
    $row_price = $result_get_price->fetch_assoc();
    $price_per_day = $row_price['price_per_day'];
    $reservation_days = (strtotime($end_date) - strtotime($start_date)) / (60 * 60 * 24);
    $total_price = $price_per_day * $reservation_days;

    // Zarezerwowanie samochodu
    $sql_make_reservation = "INSERT INTO reservations (car_id, customer_id, start_date, end_date, total_price) VALUES (?, ?, ?, ?, ?)";
    $stmt_make_reservation = $conn->prepare($sql_make_reservation);
    $stmt_make_reservation->bind_param("iissd", $car_id, $customer_id, $start_date, $end_date, $total_price);

    if ($stmt_make_reservation->execute()) {
        // Oznaczenie samochodu jako zarezerwowany
        $sql_update_car_status = "UPDATE cars SET is_reserved = TRUE WHERE car_id = ?";
        $stmt_update_car = $conn->prepare($sql_update_car_status);
        $stmt_update_car->bind_param("i", $car_id);
        $stmt_update_car->execute();
        header('Location: view.php');
        echo "Samochód został pomyślnie zarezerwowany. Całkowita cena rezerwacji: $total_price USD.";
    } else {
        echo "Coś poszło nie tak...";
    }
}

// function updateReservationsAndAvailability()
// {
//     global $conn;

//     // Sprawdzenie terminów rezerwacji i ponowne udostępnienie samochodów
//     $current_date = date("Y-m-d H:i:s");

//     // Pobranie listy car_id z bieżących rezerwacji
//     $sql_get_reserved_cars = "SELECT DISTINCT car_id FROM reservations WHERE end_date < ?";
//     $stmt_get_reserved_cars = $conn->prepare($sql_get_reserved_cars);
//     $stmt_get_reserved_cars->bind_param("s", $current_date);
//     $stmt_get_reserved_cars->execute();
//     $result_get_reserved_cars = $stmt_get_reserved_cars->get_result();

//     $reserved_car_ids = [];
//     while ($row_reserved_car = $result_get_reserved_cars->fetch_assoc()) {
//         $reserved_car_ids[] = $row_reserved_car["car_id"];
//     }

//     // Aktualizacja flagi is_reserved na FALSE dla samochodów, których termin rezerwacji minął
//     $sql_update_reserved_car_status = "UPDATE cars SET is_reserved = FALSE WHERE car_id = ?";
//     $stmt_update_reserved_car = $conn->prepare($sql_update_reserved_car_status);

//     // Przejdź przez wszystkie samochody i sprawdź, czy są one aktualnie zarezerwowane
//     $sql_get_all_cars = "SELECT car_id FROM cars";
//     $result_all_cars = $conn->query($sql_get_all_cars);
//     if ($result_all_cars->num_rows > 0) {
//         while ($row_car = $result_all_cars->fetch_assoc()) {
//             $car_id = $row_car["car_id"];
//             // Jeśli car_id nie znajduje się na liście zarezerwowanych samochodów, ustaw jego dostępność na TRUE
//             if (!in_array($car_id, $reserved_car_ids)) {
//                 $stmt_update_reserved_car->bind_param("i", $car_id);
//                 $stmt_update_reserved_car->execute();
//             }
//         }
//     }
// }


// // Wywołanie funkcji co minutę
// while (true) {
//     updateReservationsAndAvailability();
//     sleep(60); // Poczekaj 60 sekund przed kolejnym wywołaniem funkcji
// }
