<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wypożyczalnia Samochodów</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <header>
        <div class="hero-img">
            <h1>Car Rent</h1>
            <div class="search-car">
                <div class="search-car-boxes">
                    <!-- <label for="search_car">Szukaj samochodu:</label> -->
                    <input type="text" id="search_car" name="search_car" onkeyup="filterCars()" placeholder="Wyszukaj samochód..."><br>
                </div>
            </div>
        </div>
    </header>

    <div class="car-container">
        <?php
        // Tu pobierzesz dane o samochodach z bazy danych i wyświetlisz każdy samochód w pętli
        // Poniżej jest przykładowy kod PHP, który wyświetla samochody z bazy danych
        include("connect_db.php");

        // Dodatkowy warunek SQL do filtrowania samochodów po nazwie
        $filter_condition = "";
        if (isset($_GET['search_car']) && !empty($_GET['search_car'])) {
            $search_car = $_GET['search_car'];
            $filter_condition = " AND car_model LIKE '%$search_car%'";
        }

        // Zapytanie SQL z dodanym warunkiem filtrowania
        $sql = "SELECT * FROM cars WHERE is_reserved = 0 $filter_condition";
        $result = mysqli_query($conn, $sql);

        while ($row = mysqli_fetch_assoc($result)) {
        ?>
            <div class="car">
                <h2><?php echo $row['car_model']; ?></h2>
                <img src="<?php echo $row['car_image']; ?>" alt="Car Image">
                <!-- <p>Car ID: <?php echo $row['car_id']; ?></p> -->
                <p>Cena za dobę: $<?php echo $row['price_per_day']; ?></p>
                <p><?php echo $row['car_description']; ?></p>
                <!-- Przycisk "Zarezerwuj" z dodanym atrybutem data-car-id -->
                <button class="reserve-button" data-car-id="<?php echo $row['car_id']; ?>">Zarezerwuj</button>

                <!-- Formularz rezerwacji -->
                <form class="reservation-form" action="make_reservation.php" method="post">
                    <input type="hidden" name="car_id" value="<?php echo $row['car_id']; ?>">
                    <label for="start_date">Data rozpoczęcia:</label>
                    <input type="date" name="start_date" min="<?php echo date('Y-m-d'); ?>" required><br>
                    <label for="end_date">Data zakończenia:</label>
                    <input type="date" name="end_date" min="<?php echo date('Y-m-d'); ?>" required><br>
                    <label for="first_name">Imię:</label>
                    <input type="text" name="first_name" required><br>
                    <label for="last_name">Nazwisko:</label>
                    <input type="text" name="last_name" required><br>
                    <label for="phone_number">Numer telefonu:</label>
                    <input type="text" name="phone_number" required><br>
                    <label for="email">Adres e-mail:</label>
                    <input type="email" name="email" required><br>
                    <button type="submit">Zarezerwuj</button>
                </form>

            </div>




        <?php
        }
        ?>
    </div>

    <h3>Checkout</h3>
    <form action="checkout.php">
        <p>T-shirt</p>
        <p><strong>PLN 20.00</strong></p>

        <button>Zapłać</button>
    </form>

    <script>
        // Funkcja JavaScript do filtrowania samochodów po nazwie
        function filterCars() {
            var input, filter, cars, car, h2, i, txtValue;
            input = document.getElementById("search_car");
            filter = input.value.toUpperCase();
            cars = document.getElementsByClassName("car");
            for (i = 0; i < cars.length; i++) {
                car = cars[i];
                h2 = car.getElementsByTagName("h2")[0];
                txtValue = h2.textContent || h2.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    car.style.display = "";
                } else {
                    car.style.display = "none";
                }
            }
        }
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var reserveButtons = document.querySelectorAll(".reserve-button");

            for (var i = 0; i < reserveButtons.length; i++) {
                reserveButtons[i].addEventListener("click", function() {
                    var reservationForm = this.nextElementSibling;

                    if (reservationForm && reservationForm.classList.contains("reservation-form")) {
                        reservationForm.style.display = "block";
                    }
                });
            }
        });
    </script>

</body>

</html>