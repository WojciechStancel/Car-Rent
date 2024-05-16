<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<form action="process.php" method="post" enctype="multipart/form-data">
    <div class="form-field">
        <input type="text" class="form-control" name="car_model" placeholder="Model samochodu" required>
    </div>
    <div class="form-field">
        <input type="number" class="form-control" name="price_per_day" placeholder="Cena za dobę" required>
    </div>
    <div class="form-field">
        <input type="file" class="form-control form-control-file" name="car_image" placeholder="Zdjęcie samochodu" required>
    </div>
    <div class="form-field">
        <textarea name="car_description" id="" class="form-control" placeholder="Opis samochodu" required></textarea>
    </div>
    <div class="form-field">
        <input type="submit" name="create" value="Dodaj Samochód" class="btn-go-back">
    </div>
</form>
</body>
</html>