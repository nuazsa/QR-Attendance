<?php
// save_qr_data.php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $scannedText = $_POST['scannedText'];

    // Database connection
    $host = 'localhost';
    $db = 'qrattend';
    $user = 'root';
    $pass = '';

    $conn = new mysqli($host, $user, $pass, $db);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO `presensi`(`uniq_qr`) VALUES (?)");
    $stmt->bind_param('s', $scannedText);

    if ($stmt->execute()) {
        header('Location: success.php');
        echo 'Success';
    } else {
        echo 'Error: ' . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo 'Invalid request method';
}
?>
