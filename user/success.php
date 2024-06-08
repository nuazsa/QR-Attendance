<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

require_once '../component/connection.php';
$pdo = connectToDatabase();

date_default_timezone_set('Asia/Jakarta');

$current_date = date('Y-m-d');

$stmt = $pdo->prepare('SELECT * FROM presensi WHERE id_pengguna = :user_id AND tanggal = :tanggal');
$stmt->bindParam(':user_id', $_SESSION['user_id']);
$stmt->bindParam(':tanggal', $current_date);
$stmt->execute();

// if ($stmt->fetch(PDO::FETCH_ASSOC) == nulL) {
//    header('Location: index.php');
// }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Attendance - Success</title>

    <!-- Style CSS -->
    <link rel="shortcut icon" href="../component/svg/Frame1.svg" type="image/x-icon">
    <link rel="stylesheet" href="../component/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- Script JS -->
    <script src="../component/js/date.js"></script>

    <style>
        body {
            background-image: url('../component/svg/Frame.svg');
        }

        .content {
            display: block;
            max-width: 300px;
            margin: 100px auto;
            text-align: center;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0 8px 10px rgba(0, 0, 0, 0.1);
        }

        .icon {
            width: 100px;
            height: 100px;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icon-img {
            max-width: 100%;
            max-height: 100%;
            border-radius: 50%;
        }

        h1 {
            font-size: 24px;
            margin: 20px 0;
        }

        .content p {
            font-size: 16px;
            margin-bottom: 20px;
        }

        a {
            margin-top: 20px;
            text-decoration: none;
            color: #1089ff;
            font-weight: bold;
        }

        @media (max-width: 425px) {
            section .content {
                max-width: 250px;
            }
        }
    </style>
</head>

<body>
    <header>
        <div class="container">
            <h3><i class="fa-regular fa-user"></i><?= $_SESSION['name']; ?></h3>
            <div class="date">
                <p><i class="fa-regular fa-calendar"></i><?= date('l, d/M/Y'); ?></p>
            </div>
        </div>
    </header>
    <section>
        <div class="content">
            <div class="icon">
                <img src="../component/icon/icon.jpg" alt="Success Icon" class="icon-img">
            </div>
            <h1>Successful!!!</h1>
            <p>Thank you for your successful attendance.</p>
            <a href="index.php"><i class="fa-solid fa-house-chimney"></i> Back To Home</a>
        </div>
    </section>
</body>

</html>