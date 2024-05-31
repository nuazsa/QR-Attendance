<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

require_once '../component/connection.php';
$pdo = connectToDatabase();

date_default_timezone_set('Asia/Jakarta');

$stmt = $pdo->prepare('SELECT * FROM qrcodes WHERE id_kelas = :id_kelas AND id_user = :user_id');
$stmt->bindParam(':id_kelas', $_GET['id']);
$stmt->bindParam(':user_id', $_SESSION['user_id']);
$stmt->execute();

$qr = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare('SELECT * FROM kelas WHERE id = :id_kelas');
$stmt->bindParam(':id_kelas', $_GET['id']);
$stmt->execute();

$class = $stmt->fetch(PDO::FETCH_ASSOC);

$current_date = date('Y-m-d');
// var_dump($class['tanggal']);
if ($qr['status'] == 'success' && $class['tanggal'] == $current_date) {
    header('Location: success.php');
    exit;
} elseif (!isset($qr['qr_code']) || $class['tanggal'] != $current_date) {
    echo "<script>
            alert('Class hasnt started yet');
            window.location.href = 'index.php';
          </script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Attendance - Barcode</title>

    <!-- Style CSS -->
    <link rel="shortcut icon" href="../component/svg/Frame1.svg" type="image/x-icon">
    <link rel="stylesheet" href="../component/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- Script JS -->
    <script src="../component/js/date.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.4.4/qrcode.min.js"></script>

    <style>
        body {
            background-image: url('../component/svg/Wave-1.svg');
            background-size: 140%;
        }

        section {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            width: 100%;
        }

        section .row {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            margin-top: 30px;
        }

        .barcode {
            width: 400px;
            height: 400px;
            background-color: white;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        h1 {
            margin-bottom: 20px;
        }

        a {
            margin-top: 20px;
            text-decoration: none;
            color: #1089ff;
            font-weight: bold;
        }

        @media (max-width: 425px) {
            .barcode {
                width: 250px;
                height: 250px;
                background-color: silver;
            }
        }
    </style>
</head>

<body>
    <div id="qrcode"></div>
    <header>
        <div class="container">
            <h3><i class="fa-regular fa-user"></i><?= $_SESSION['name']; ?></h3>
            <div class="date">
                <p><i class="fa-regular fa-calendar"></i><?= date('l, d/M/Y'); ?></p>
            </div>
        </div>
    </header>
    <section>
        <div class="row">
            <h1>Scan For Presence</h1>
            <div class="barcode">
                <?php
                if (isset($qr)) {
                    require_once '../phpqrcode/qrlib.php';
                    QRcode::png($qr['qr_code'], 'barcode.png', QR_ECLEVEL_H, 20);
                }
                ?>
                <img src="barcode.png" alt="" width="100%">
            </div>
            <a href="index.php"><i class="fa-solid fa-house-chimney"></i> Back To Home</a>
        </div>
    </section>
</body>

</html>