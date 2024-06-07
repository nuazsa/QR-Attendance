<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header('Location: ../index.php');
    exit();
}

require_once '../component/connection.php';
$pdo = connectToDatabase();

date_default_timezone_set('Asia/Jakarta');

$stmt = $pdo->prepare('SELECT * FROM detail_kelas WHERE id_pengguna = :user_id');
$stmt->bindParam(':user_id', $_SESSION['user_id']);
$stmt->execute();

$classes = [];
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $user) {
    $stmt = $pdo->prepare('SELECT * FROM kelas WHERE id_kelas = :class_id');
    $stmt->bindParam(':class_id', $user['id_kelas']);
    $stmt->execute();
    $classes[] = $stmt->fetch(PDO::FETCH_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Attendance - Daftar Kelas</title>

    <!-- Style CSS -->
    <link rel="shortcut icon" href="../component/svg/Frame1.svg" type="image/x-icon">
    <link rel="stylesheet" href="../component/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        body {
            background-image: url('../component/svg/Frame.svg');
        }

        section {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            padding: 30px;
        }

        .class {
            margin: 10px;
        }

        .class-card {
            display: flex;
            justify-content: space-around;
            background-color: white;
            border-radius: 20px 0 20px 0;
            box-shadow: 0 8px 10px rgba(0, 0, 0, 0.1);
            width: 250px;
            padding: 15px;
            text-align: center;
        }

        .class-name {
            background-color: skyblue;
            width: 40%;
            padding: 50px 0px;
        }

        .class-info {
            align-content: center;
        }

        .class-info p {
            margin: 15px 0;
        }


        .link-container {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-top: 10px;
            text-align: center;
        }

        a,
        button[name="logout"] {
            border: none;
            text-decoration: none;
            color: #007BFF;
            background-color: #fff;
            width: 100%;
            padding: 8px 0;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            box-shadow: 0 8px 10px rgba(0, 0, 0, 0.1);
        }

        a:hover,
        button[name="logout"]:hover {
            background-color: #007BFF;
            color: #fff;
        }

        form {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
        }

        button[name="logout"] {
            padding: 8px 20px;
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
        <?php if (!empty($classes)) : ?>
            <?php foreach ($classes as $class) : ?>
                <div class="class">
                    <div class="class-card">
                        <div class="class-name">
                            <h3><?= $class['pelajaran']; ?></h3>
                            <p><?= $class['ruangan']; ?></p>
                        </div>
                        <div class="class-info">
                            <p><?= date('H:i', strtotime($class['mulai'])); ?> - <?= date('H:i', strtotime($class['selesai'])); ?></p>
                            <p style="border: 1px solid; padding: 2px; border-radius: 8px"><?= $class['hari']; ?></p>
                            <p><?= date('d/M/Y', strtotime($class['tanggal'])); ?></p>
                        </div>
                    </div>
                    <div class="link-container">
                        <a href="scan.php?id=<?= $class['id_kelas']; ?>">Scan QR</a>
                        <a href="history.php?id=<?= $class['id_kelas']; ?>">Attendance Report</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p>Class Does't Exist</p>
        <?php endif; ?>
    </section>
    <section id="logout">
        <form action="" method="post">
            <button name="logout"><i class="fa-solid fa-power-off"></i> Logout</button>
        </form>
    </section>
</body>

</html>