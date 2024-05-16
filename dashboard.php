<?php
include("connect_db.php");

// Pobierz listę samochodów
$sql_get_cars = "SELECT * FROM cars";
$result_get_cars = mysqli_query($conn, $sql_get_cars);

// Pobierz listę rezerwacji
$sql_get_reservations = "SELECT * FROM reservations";
$result_get_reservations = mysqli_query($conn, $sql_get_reservations);

$sql_get_users = "SELECT * FROM customers";
$result_get_users = $conn->query($sql_get_users);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>

<body>
    <h1>Dashboard</h1>

    <a href="add_car.php">DODAJ NOWE AUTO</a>
    <br>
    <a href="view.php">ZOBACZ AUTA</a>

    <h2>Lista samochodów</h2>
    <table>
        <thead>
            <tr>
                <th>Car ID</th>
                <th>Model</th>
                <th>Price per Day</th>
                <th>Description</th>
                <th>Image</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row_car = mysqli_fetch_assoc($result_get_cars)) { ?>
                <tr>
                    <td><?php echo $row_car['car_id']; ?></td>
                    <td><?php echo $row_car['car_model']; ?></td>
                    <td><?php echo $row_car['price_per_day']; ?></td>
                    <td><?php echo $row_car['car_description']; ?></td>
                    <td><img src="<?php echo $row_car['car_image']; ?>" alt="Car Image" style="width: 100px;"></td>
                    <td><?php echo $row_car['is_reserved'] ? 'Reserved' : 'Available'; ?></td>
                    <td>
                        <?php if ($row_car['is_reserved']) { ?>
                            <form action="update_status.php" method="post">
                                <input type="hidden" name="car_id" value="<?php echo $row_car['car_id']; ?>">
                                <button type="submit" name="set_available">Set Available</button>
                            </form>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <h2>Lista Rezerwacji</h2>
    <ul>
        <?php
        if ($result_get_reservations->num_rows > 0) {
            while ($row = $result_get_reservations->fetch_assoc()) {
                $reservation_id = $row["reservation_id"];
                $car_id = $row["car_id"];
                $start_date = $row["start_date"];
                $end_date = $row["end_date"];
        ?>
                <li>
                    Rezerwacja ID: <?php echo $reservation_id; ?>, Samochód ID: <?php echo $car_id; ?>, Data rozpoczęcia: <?php echo $start_date; ?>, Data zakończenia: <?php echo $end_date; ?>
                    <form action="delete.php" method="post">
                        <input type="hidden" name="reservation_id" value="<?php echo $reservation_id; ?>">
                        <button type="submit" name="delete_reservation">Usuń</button>
                    </form>
                </li>
        <?php
            }
        } else {
            echo "Brak rezerwacji.";
        }
        ?>
    </ul>




    <h2>Lista Użytkowników</h2>
    <ul>
        <?php
        if ($result_get_users->num_rows > 0) {
            while ($row = $result_get_users->fetch_assoc()) {
                $user_id = $row["customer_id"];
                $first_name = $row["first_name"];
                $last_name = $row["last_name"];
                $phone_number = $row["phone_number"];
                $email = $row["email"];
        ?>
                <li>
                    Użytkownik ID: <?php echo $user_id; ?>, Imię: <?php echo $first_name; ?>, Nazwisko: <?php echo $last_name; ?>, Telefon: <?php echo $phone_number; ?>, Email: <?php echo $email; ?>
                    <form action="delete.php" method="post">
                        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                        <button type="submit" name="delete_user">Usuń</button>
                    </form>
                </li>
        <?php
            }
        } else {
            echo "Brak użytkowników.";
        }
        ?>
    </ul>



</body>

</html>