<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "carway";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$success_message = $error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $car_name = $_POST['car_name'];
    $car_price = $_POST['car_price'];
    $image_name = $_FILES["car_image"]["name"];
    $image_tmp = $_FILES["car_image"]["tmp_name"];
    $upload_dir = "uploads/";

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    if (!empty($car_name) && !empty($car_price) && !empty($image_name)) {
        $target_file = $upload_dir . basename($image_name);

        if (move_uploaded_file($image_tmp, $target_file)) {
            $sql = "INSERT INTO car_inventory (car_name, car_price, car_image)
                    VALUES ('$car_name', '$car_price', '$target_file')";

            if ($conn->query($sql)) {
                $success_message = "Car added successfully!";
            } else {
                $error_message = "Database error: " . $conn->error;
            }
        } else {
            $error_message = "Failed to upload image.";
        }
    } else {
        $error_message = "All fields are required!";
    }
}

$cars = [];
$result = $conn->query("SELECT * FROM car_inventory ORDER BY id DESC");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cars[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Car Upload</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #1e1e1e;
            color: #fff;
            padding: 40px;
        }

        form {
            background: #2a2a2a;
            padding: 30px;
            border-radius: 10px;
            width: 400px;
            margin: 0 auto 30px auto;
            position: relative;
        }

        h2 {
            color: orange;
            text-align: center;
        }

        input[type="text"], input[type="number"], input[type="file"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 6px;
            background: #444;
            color: #fff;
        }

        button {
            background: orange;
            color: black;
            padding: 10px;
            width: 100%;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .message {
            margin: 15px 0;
            text-align: center;
            padding: 10px;
            border-radius: 5px;
        }

        .success { background: #2e7d32; }
        .error { background: #c62828; }

        table {
            width: 90%;
            margin: 0 auto;
            border-collapse: collapse;
            background: #2a2a2a;
        }

        th, td {
            padding: 12px;
            border: 1px solid #444;
            text-align: center;
        }

        th {
            background: orange;
            color: black;
        }

        img {
            width: 100px;
            height: auto;
        }

        .car-table-title {
            text-align: center;
            margin-bottom: 10px;
            color: orange;
            font-size: 1.5em;
        }

        .back-btn {
            position: absolute;
            top: 10px;
            left: 10px;
            background: transparent;
            border: 2px solid orange;
            color: orange;
            padding: 6px 12px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
        }

        .back-btn:hover {
            background: orange;
            color: black;
        }
    </style>
</head>
<body>

<form method="POST" enctype="multipart/form-data">
    <a href="index
    .html" class="back-btn">← Back</a>
    <h2>Upload Car Details</h2>

    <?php if (!empty($success_message)): ?>
        <div class="message success"><?php echo $success_message; ?></div>
    <?php endif; ?>

    <?php if (!empty($error_message)): ?>
        <div class="message error"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <input type="text" name="car_name" placeholder="Car Name" required>
    <input type="number" step="0.01" name="car_price" placeholder="Price (in ₹)" required>
    <input type="file" name="car_image" accept="image/*" required>
    <button type="submit">Upload Car</button>
</form>

<?php if (!empty($cars)): ?>
    <div class="car-table-title">Uploaded Cars</div>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Price (₹)</th>
            <th>Image</th>
        </tr>
        <?php foreach ($cars as $car): ?>
            <tr>
                <td><?php echo $car['id']; ?></td>
                <td><?php echo htmlspecialchars($car['car_name']); ?></td>
                <td><?php echo number_format($car['car_price'], 2); ?></td>
                <td><img src="<?php echo htmlspecialchars($car['car_image']); ?>" alt="Car Image"></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

</body>
</html>
