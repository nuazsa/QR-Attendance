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
$stmt = $pdo->prepare('SELECT * FROM presensi WHERE id_user = :id_user');
$stmt->bindParam(':id_user', $_SESSION['user_id']);
$stmt->execute();
$presence = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch presence records for the class
$stmt = $pdo->prepare('SELECT MAX(pertemuan) AS pertemuan FROM presensi WHERE id_kelas = :id_kelas;');
$stmt->bindParam(':id_kelas', $_GET['id']);
$stmt->execute();
$maxpresence = $stmt->fetch(PDO::FETCH_ASSOC);

$presences = [];
for ($i = 0; $i < $maxpresence['pertemuan']; $i++) {
    if (isset($presence[$i]['pertemuan'])) {
        $presences[] = $presence[$i]['pertemuan'];
    }
}

$dates = [];
for ($i = 0; $i < $maxpresence['pertemuan']; $i++) {
    if (isset($presence[$i]['tanggal'])) {
        $dates[] = $presence[$i]['tanggal'];
    }
}

// var_dump($dates);
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
                    <?php for ($i = 0; $i < $maxpresence['pertemuan']; $i++) : ?>
                        <tr>
                            <td><?= $i + 1; ?></td>
                            <td><?= in_array($i + 1, $presences) ? 'hadir' : '-'; ?></td>
                            <td><?= in_array($i + 1, $presences) ? $dates[array_search($i + 1, $presences)] : '-'; ?></td>
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
    </section>
</body>

</html>