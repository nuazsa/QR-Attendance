<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}


require_once '../component/connection.php';
$pdo = connectToDatabase();

// // Memeriksa apakah kelas sedang berlangsung
// if ($class['tanggal'] == $current_date && ($class['mulai'] <= $current_time && $class['selesai'] >= $current_time)) {
//     // Kelas sedang berlangsung, lanjutkan proses
// } else {
//     // Kelas belum dimulai atau sudah selesai
//     echo "<script>
//             alert('Class hasnt started yet or has already finished.');
//             window.location.href = 'index.php';
//           </script>";
//     exit();
// }

$stmt = $pdo->prepare('SELECT * FROM kelas WHERE id = :id');
$stmt->bindParam(':id', $_GET['id']);
$stmt->execute();

$class = $stmt->fetch(PDO::FETCH_ASSOC);

// Mengambil tanggal dan waktu saat ini
$current_date = date('Y-m-d');
$current_time = date('H:i');

if ($class['tanggal'] == '2024-06-03' && ($class['mulai'] <= '19:01' && $class['selesai'] >= '19:01')) {
    $stmt = $pdo->prepare('SELECT id_user FROM qrcodes WHERE id_kelas = :id_kelas');
    $stmt->bindParam(':id_kelas', $_GET['id']);
    $stmt->execute();

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($rows as $row) {
        $qr_code = uniqid(); // Membuat qr_code unik

        $stmt = $pdo->prepare("UPDATE qrcodes SET qr_code = :qr_code, status = 'active' WHERE id_kelas = :id_kelas AND id_user = :id_user");
        $stmt->bindParam(':qr_code', $qr_code);
        $stmt->bindParam(':id_kelas', $_GET['id']);
        $stmt->bindParam(':id_user', $row['id_user']);
        $stmt->execute();
    }
} else {
    echo "<script>
            alert('class hasnt started yet');
            window.location.href = 'index.php';
          </script>";
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>QR Attendance - Scan QR</title>

    <!-- Style CSS -->
    <link rel="shortcut icon" href="../component/svg/Frame1.svg" type="image/x-icon">
    <link rel="stylesheet" href="../component/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- Script JS -->
    <script src="../component/js/date.js"></script>    
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/webrtc-adapter/3.3.3/adapter.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.1.10/vue.min.js"></script>
    <script type="text/javascript" src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>

    <style>
        body,
        html {
            background-image: url('../component/svg/Wave-1.svg');
            background-size: 140%;
            overflow-y: hidden;
            padding: 0;
            margin: 0;
            font-family: 'Helvetica Neue', 'Calibri', Arial, sans-serif;
            height: 100%;
        }

        #app {
            display: flex;
            align-items: stretch;
            justify-content: stretch;
            height: 91%;
        }

        .sidebar {
            background: #fff;
            box-shadow: 0 8px 10px rgba(0, 0, 0, 0.1);
            min-width: 250px;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            overflow: auto;
        }

        .sidebar h2 {
            font-weight: normal;
            font-size: 1.0rem;
            background: #607d8b;
            color: #fff;
            padding: 10px;
            margin: 0;
        }

        .sidebar ul {
            margin: 0;
            padding: 0;
            list-style-type: none;
        }

        .sidebar li {
            line-height: 175%;
            white-space: nowrap;
            overflow: hidden;
            text-wrap: none;
            text-overflow: ellipsis;
        }

        .cameras ul {
            padding: 15px 20px;
        }

        .cameras .active {
            font-weight: bold;
            color: #009900;
        }

        .cameras a {
            color: #555;
            text-decoration: none;
            cursor: pointer;
        }

        .cameras a:hover {
            text-decoration: underline;
        }

        .scans li {
            padding: 10px 20px;
            border-bottom: 1px solid #ccc;
        }

        .scans-enter-active {
            transition: background 3s;
        }

        .scans-enter {
            background: yellow;
        }

        .empty {
            font-style: italic;
        }

        .preview-container {
            flex-direction: column;
            align-items: center;
            justify-content: center;
            display: flex;
            width: 100%;
            overflow: hidden;
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
    <div id="app">
        <div class="sidebar">
            <section class="cameras">
                <h2>Cameras</h2>
                <ul>
                    <li v-if="cameras.length === 0" class="empty">No cameras found</li>
                    <li v-for="camera in cameras">
                        <span v-if="camera.id == activeCameraId" :title="formatName(camera.name)" class="active">{{ formatName(camera.name) }}</span>
                        <span v-if="camera.id != activeCameraId" :title="formatName(camera.name)">
                            <a @click.stop="selectCamera(camera)">{{ formatName(camera.name) }}</a>
                        </span>
                    </li>
                </ul>
            </section>
            <section class="scans">
                <h2>Scans</h2>
                <ul v-if="scans.length === 0">
                    <li class="empty">No scans yet</li>
                </ul>
                <transition-group name="scans" tag="ul">
                    <li v-for="scan in scans" :key="scan.date" :title="scan.content">{{ scan.content }}</li>
                </transition-group>
            </section>
        </div>
        <div class="preview-container">
            <video id="preview"></video>
        </div>
    </div>
    <script type="text/javascript" src="app.js"></script>
</body>

</html>