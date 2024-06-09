<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

require_once '../component/connection.php';
$pdo = connectToDatabase();

date_default_timezone_set('Asia/Jakarta');

// Fetch class details
$stmt = $pdo->prepare('SELECT * FROM kelas WHERE id_kelas = :id');
$stmt->bindParam(':id', $_GET['id']);
$stmt->execute();
$class = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch the total number of meetings
$stmt = $pdo->prepare('SELECT MAX(pertemuan) AS total_pertemuan FROM presensi JOIN qrcodes ON presensi.id_qrcode = qrcodes.id_qrcode WHERE presensi.id_kelas = :id');
$stmt->bindParam(':id', $_GET['id']);
$stmt->execute();
$meeting = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch all presence records for the user in the class
$stmt = $pdo->prepare('SELECT * FROM presensi JOIN qrcodes ON presensi.id_qrcode = qrcodes.id_qrcode WHERE presensi.id_pengguna = :id_user AND presensi.id_kelas = :id_kelas');
$stmt->bindParam(':id_user', $_SESSION['user_id']);
$stmt->bindParam(':id_kelas', $_GET['id']);
$stmt->execute();
$presence = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Create an array to check presence for each meeting
$presenceArray = array();
foreach ($presence as $pres) {
    $presenceArray[$pres['pertemuan']] = $pres;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Attendance - History</title>

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

        section .container .label {
            display: flex;
            justify-content: space-between;
        }

        section h3 {
            margin: 0;
            margin-top: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0 8px 10px rgba(0, 0, 0, 0.1);
        }

        table,
        th,
        td {
            border: 1px solid #000;
        }

        th,
        td {
            padding: 10px;
            text-align: center;
        }

        .buttons {
            display: flex;
            justify-content: center;
            margin: 20px;
        }

        a {
            margin-top: 20px;
            text-decoration: none;
            color: #1089ff;
            font-weight: bold;
        }

        button {
            background-color: transparent;
            border: 1px solid;
            padding: 0px 10px;
            text-align: end;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 10px 2px;
            cursor: pointer;
            border-radius: 50px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: lightgreen;
        }

        button i {
            margin-right: 8px;
        }

        @media print {
            body {
                box-shadow: none;
            }

            .buttons {
                display: none;
            }

            a {
                display: none;
            }

            button {
                display: none;
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
        <div class="container">
            <div class="label">
                <h3>Attendance Report - <?= $class['pelajaran']; ?></h3>
                <button onclick="window.print()"><i class="fa-regular fa-file-pdf"></i> Print</button>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Lesson</th>
                        <th>Meeting</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for ($i = 0; $i < $meeting['total_pertemuan']; $i++) : ?>
                        <tr>
                            <td><?= $i + 1; ?></td>
                            <td><?= isset($presenceArray[$i + 1]) ? '✓' : '-'; ?></td>
                            <td><?= isset($presenceArray[$i + 1]) ? $presenceArray[$i + 1]['tanggal'] : '-'; ?></td>
                            <td><?= $class['pelajaran']; ?></td>
                            <td><?= $i + 1; ?></td>
                        </tr>
                    <?php endfor; ?>
                </tbody>
            </table>
        </div>
        </div>
        <div class="buttons">
            <a href="index.php"><i class="fa-solid fa-house-chimney"></i> Back To Home</a>
        </div>
    </section>
</body>

</html>
