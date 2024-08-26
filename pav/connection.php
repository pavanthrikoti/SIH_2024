<?php
// Retrieve and sanitize form inputs
$full_name = isset($_POST['full_name']) ? $_POST['full_name'] : '';
$age = isset($_POST['age']) ? intval($_POST['age']) : 0;
$gender = isset($_POST['gender']) ? $_POST['gender'] : '';
$address = isset($_POST['address']) ? $_POST['address'] : '';
$booking_date = isset($_POST['booking_date']) ? $_POST['booking_date'] : '';
$room_type = isset($_POST['room_type']) ? $_POST['room_type'] : '';
$days = isset($_POST['days']) ? intval($_POST['days']) : 0;
$image = isset($_FILES['image']) ? $_FILES['image']['name'] : '';
$payment_method = isset($_POST['payment_method']) ? $_POST['payment_method'] : '';
$account_number = isset($_POST['account_number']) ? $_POST['account_number'] : '';

// Image upload handling
if ($image) {
    $target_dir = "uploads/";
    
    // Check if uploads directory exists, if not, create it
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        die("Image upload failed.");
    }
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'hotel_booking');
if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

// Prepare the SQL statement
$sql = "INSERT INTO registration (full_name, age, gender, address, booking_date, room_type, days, image, payment_method, account_number) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}

// Bind parameters
$stmt->bind_param("sissssisis", $full_name, $age, $gender, $address, $booking_date, $room_type, $days, $image, $payment_method, $account_number);

// Execute the statement
if ($stmt->execute()) {
    // Redirect to thank you page
    header("Location: thankyou.html");
    exit(); // Make sure to exit after the redirect
} else {
    echo "Error: " . $stmt->error;
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
