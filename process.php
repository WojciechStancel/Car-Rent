<?php
include("connect_db.php");

function secureInput($conn, $data)
{
    return mysqli_real_escape_string($conn, $data);
}

if (isset($_POST["create"])) {
    $car_model = secureInput($conn, $_POST["car_model"]);
    $price_per_day = secureInput($conn, $_POST["price_per_day"]);
    $car_description = secureInput($conn, $_POST["car_description"]);

    // Obsługa przesyłania pliku
    $target_dir = "uploads/";
    $car_image = $target_dir . basename($_FILES["car_image"]["name"]);
    move_uploaded_file($_FILES["car_image"]["tmp_name"], $car_image);

    $sqlInsert = "INSERT INTO cars(car_model, price_per_day, car_description, car_image) VALUES (?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sqlInsert)) {
        $stmt->bind_param("siss", $car_model, $price_per_day, $car_description, $car_image);

        if ($stmt->execute()) {
            header('Location: dashboard.php');
            echo "Samochód dodano poprawnie!";
        } else {
            echo "Coś poszło nie tak...";
        }
        $stmt->close();
    } else {
        echo "Coś poszło nie tak...";
    }
}
?>
