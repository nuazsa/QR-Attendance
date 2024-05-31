<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

require_once '../component/connection.php';
$pdo = connectToDatabase();


date_default_timezone_set('Asia/Jakarta');

// Fetch presence records for the class
$stmt = $pdo->prepare('SELECT * FROM kelas WHERE id = :id');
$stmt->bindParam(':id', $_GET['id']);
$stmt->execute();
$class = $stmt->fetch(PDO::FETCH_ASSOC);


// Fetch presence records for the class
$stmt = $pdo->prepare('SELECT * FROM presensi WHERE id_kelas = :id_kelas');
$stmt->bindParam(':id_kelas', $_GET['id']);
$stmt->execute();
$presence = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Fetch presence records for the class
$stmt = $pdo->prepare('SELECT MAX(pertemuan) AS pertemuan FROM presensi WHERE id_kelas = :id_kelas;');
$stmt->bindParam(':id_kelas', $_GET['id']);
$stmt->execute();
$maxpresence = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch user IDs from qrcodes table for the class
$stmt = $pdo->prepare('SELECT id_user FROM qrcodes WHERE id_kelas = :id_kelas');
$stmt->bindParam(':id_kelas', $_GET['id']);
$stmt->execute();
$user_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Initialize an array to hold user data
$users = [];

// Fetch user details from pengguna table based on the user IDs
if (!empty($user_ids)) {
    // Prepare an IN clause with placeholders for the user IDs
    $placeholders = implode(',', array_fill(0, count($user_ids), '?'));

    // Fetch user details
    $stmt = $pdo->prepare("SELECT * FROM pengguna WHERE id IN ($placeholders)");
    $stmt->execute($user_ids);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Loop through each user and fetch their presence records
foreach ($users as $index => $user) {
    $stmt = $pdo->prepare('SELECT * FROM presensi WHERE id_user = :id_user AND id_kelas = :id_kelas;');
    $stmt->bindParam(':id_user', $user['id']);
    $stmt->bindParam(':id_kelas', $_GET['id']);
    $stmt->execute();
    $userPresence = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Store presence records with the user index
    $presenceRecords[$index] = $userPresence;
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
            background-image: url('../component/svg/Frame2.svg');
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
            /* overflow: hidden; */
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
            color: green;
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
            /* Darker blue on hover */
        }

        button i {
            margin-right: 8px;
            /* Space between icon and text */
        }

        @media print {
            body {
                box-shadow: none;
            }

            .buttons {
                display: none;
                /* Hide buttons in print view */
            }

            a {
                display: none;
                /* Hide links in print view */
            }

            button {
                display: none;
                /* Hide buttons in print view */
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
                <button onclick="window.print()"><i class="fa-regular fa-file-pdf"></i> Print To PDF</button>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NISN / NIM</th>
                        <th>Student Name</th>
                        <?php for ($i = 0; $i < $maxpresence['pertemuan']; $i++) : ?>
                            <th>P.<?= $i + 1; ?></th>
                        <?php endfor ?>
                        <th>Percentage (%)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for ($i = 0; $i < count($users); $i++) : ?>
                        <tr>
                            <td><?= $i + 1; ?></td>
                            <td><?= $users[$i]['username']; ?></td>
                            <td><?= $users[$i]['name']; ?></td>
                            <?php

                            if (isset($maxpresence['pertemuan'])) {
                                // Initialize an array to store presence status
                                $presenceStatus = array_fill(0, $maxpresence['pertemuan'], '-');
                            }

                            // Populate the presence status based on actual records
                            foreach ($presenceRecords[$i] as $record) {
                                $presenceStatus[$record['pertemuan'] - 1] = 'Hadir';
                            }

                            // Output the presence status for each pertemuan
                            for ($j = 0; $j < $maxpresence['pertemuan']; $j++) : ?>
                                <td><?= $presenceStatus[$j]; ?></td>
                            <?php endfor; ?>
                            <td><?= (isset($maxpresence['pertemuan'])) ? number_format((count($presenceRecords[$i]) / $maxpresence['pertemuan']) * 100, 0) : '';?>%</td>
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