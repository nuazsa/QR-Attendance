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
$current_time = date('H:i');

$stmt = $pdo->prepare('SELECT * FROM qrcodes WHERE id_kelas = :id_kelas ORDER BY tanggal_pembuatan DESC');
$stmt->bindParam(':id_kelas', $_GET['id']);
$stmt->execute();

$qr = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare('SELECT * FROM kelas WHERE id_kelas = :id_kelas');
$stmt->bindParam(':id_kelas', $_GET['id']);
$stmt->execute();

$class = $stmt->fetch(PDO::FETCH_ASSOC);

if ($class['tanggal'] != $current_date || !($class['mulai'] <= $current_time && $class['selesai'] >= $current_time)) {
    echo "<script>
            alert('Class hasnt started yet');
            window.location.href = 'index.php';
          </script>";
    exit;
}

$qrcode = null;
if (isset($qr)) {
    if ($qr['tanggal_pembuatan'] == '2024-06-08') {
        $qrcode = $qr['qr_code'];
    } else {
        $current_date = '2024-06-08';
        $pertemuan = $qr['pertemuan'] + 1;
        $qrcode = $_GET['id'].'-'.$pertemuan.'-'.uniqid();
        
        $stmt = $pdo->prepare('INSERT INTO `qrcodes`(`id_kelas`, `qr_code`, `pertemuan`, `tanggal_pembuatan`) VALUES (:id_kelas, :qrcode, :pertemuan, :tanggal)');
        $stmt->bindParam(':id_kelas', $_GET['id']);
        $stmt->bindParam(':qrcode', $qrcode);
        $stmt->bindParam(':pertemuan', $pertemuan);
        $stmt->bindParam(':tanggal', $current_date);
        $stmt->execute();
    }
} else {
    $qrcode = $_GET['id'].'-1-'.uniqid();
    
    $stmt = $pdo->prepare('INSERT INTO `qrcodes`(`id_kelas`, `qr_code`, `pertemuan`, `tanggal_pembuatan`) VALUES (:id_kelas, :qrcode, 1, :tanggal)');
    $stmt->bindParam(':id_kelas', $_GET['id']);
    $stmt->bindParam(':qrcode', $qrcode);
    $stmt->bindParam(':tanggal', $current_date);
    $stmt->execute();
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
                    require_once '../phpqrcode/qrlib.php';
                    QRcode::png($qrcode, 'barcode.png', QR_ECLEVEL_H, 20);
                ?>
                <img src="barcode.png" alt="" width="100%">
            </div>
            <a href="index.php"><i class="fa-solid fa-house-chimney"></i> Back To Home</a>
        </div>
    </section>
</body>

</html>