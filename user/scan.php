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
$current_date = date('Y-m-d');

$stmt = $pdo->prepare('SELECT * FROM qrcodes WHERE id_kelas = :id_kelas ORDER BY tanggal_pembuatan DESC');
$stmt->bindParam(':id_kelas', $_GET['id']);
$stmt->execute();

$qr = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare('SELECT * FROM presensi WHERE id_kelas = :id_kelas AND id_pengguna = :id_pengguna ORDER BY tanggal DESC');
$stmt->bindParam(':id_kelas', $_GET['id']);
$stmt->bindParam(':id_pengguna', $_SESSION['user_id']);
$stmt->execute();

$presence = $stmt->fetch(PDO::FETCH_ASSOC);


$qrCode = isset($qr['qr_code']) ? $qr['qr_code'] : null;
$classId = isset($_GET['id']) ? $_GET['id'] : null;

// var_dump($qr['qr_code']); exit;
if ($presence) {
    if ($presence['tanggal'] == $current_date) {
        header('Location: success.php?id='.$_GET['id']);
        exit;
    }
}

?>

<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Attendance - Daftar Kelas</title>

    <!-- Style CSS -->
    <link rel="shortcut icon" href="../component/svg/Frame1.svg" type="image/x-icon">
    <link rel="stylesheet" href="../component/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <script type="text/javascript" src="../component/js/jsPretty/jsqrscanner.nocache.js"></script>

    <style>
        body {
            background-image: url('../component/svg/Wave-1.svg');
            background-size: 140%;
        }

        section {
            display: flex;
            justify-content: center;
        }

        .qrscanner video {
            display: flex;
            justify-content: center;
            margin: 20px 0;
            max-width: 100%;
            max-height: 75%;
        }

        .row-element-set {
            display: flex;
            flex-direction: column;
        }

        .row {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            margin-top: 30px;
        }

        .row-element {
            justify-content: center;
            padding: .2em 0em;
        }

        .row-element-set-QRScanner {
            max-width: 30em;
            display: flex;
            flex-direction: column;
        }

        .form-field-caption {
            font-weight: bold;
        }

        .form-field-input {
            width: 100%;
        }

        .error_message {
            color: red;
            background-color: white;
            border: 1px solid red;
            padding: 4px;
            font-family: sans-serif
        }

        a {
            margin-top: 20px;
            text-decoration: none;
            color: #1089ff;
            font-weight: bold;
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
        <div class="row-element-set row-element-set-QRScanner">
            <!-- RECOMMENDED if your web app will not function without JavaScript enabled -->
            <noscript>
                <div class="row-element-set error_message">
                    Your web browser must have JavaScript enabled
                    in order for this application to display correctly.
                </div>
            </noscript>
            <script>
                if (location.protocol != 'https:') {
                    document.getElementById('secure-connection-message').style = 'display: block';
                }
            </script>

            <div class="row">
                <h1>Scan For Presence</h1>
                <i>Point the camera at the QR code.</i>
                <div class="qrscanner" id="scanner"></div>
                Scanned Information
                <input id="scannedTextMemo" class="textInput form-memo form-field-input textInput-readonly" readonly></input>
            </div>
    </section>
    <section>
        <a href="index.php"><i class="fa-solid fa-house-chimney"></i> Back To Home</a>
    </section>
    <script type="text/javascript">
        // Pass the QR code from PHP to JavaScript
        var serverQrCode = <?= json_encode($qrCode); ?>;
        var classId = <?= json_encode($classId); ?>;


        function onQRCodeScanned(scannedText) {
            var scannedTextMemo = document.getElementById("scannedTextMemo");
            if (scannedTextMemo) {
                scannedTextMemo.value = scannedText;
            }
            var scannedTextMemoHist = document.getElementById("scannedTextMemoHist");
            if (scannedTextMemoHist) {
                scannedTextMemoHist.value = scannedTextMemoHist.value + '\n' + scannedText;
            }

            // Send the scanned text to the server
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "save_qr_data.php?id="+classId, true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    if (scannedText === serverQrCode) {
                        alert('Presence added successfully');
                        window.location.href = "success.php?id="+classId;
                    } else {
                        alert('Presence failed to add');
                        console.error(xhr.responseText); // Handle error response
                    }
                }
            };
            xhr.send("scannedText=" + encodeURIComponent(scannedText));
        }

        function provideVideo() {
            var n = navigator;

            if (n.mediaDevices && n.mediaDevices.getUserMedia) {
                return n.mediaDevices.getUserMedia({
                    video: {
                        facingMode: "environment"
                    },
                    audio: false
                });
            }

            return Promise.reject('Your browser does not support getUserMedia');
        }

        function provideVideoQQ() {
            return navigator.mediaDevices.enumerateDevices()
                .then(function(devices) {
                    var exCameras = [];
                    devices.forEach(function(device) {
                        if (device.kind === 'videoinput') {
                            exCameras.push(device.deviceId)
                        }
                    });

                    return Promise.resolve(exCameras);
                }).then(function(ids) {
                    if (ids.length === 0) {
                        return Promise.reject('Could not find a webcam');
                    }

                    return navigator.mediaDevices.getUserMedia({
                        video: {
                            'optional': [{
                                'sourceId': ids.length === 1 ? ids[0] : ids[1] //this way QQ browser opens the rear camera
                            }]
                        }
                    });
                });
        }

        //this function will be called when JsQRScanner is ready to use
        function JsQRScannerReady() {
            //create a new scanner passing to it a callback function that will be invoked when
            //the scanner successfully scan a QR code
            var jbScanner = new JsQRScanner(onQRCodeScanned);
            //var jbScanner = new JsQRScanner(onQRCodeScanned, provideVideo);
            //reduce the size of analyzed image to increase performance on mobile devices
            jbScanner.setSnapImageMaxSize(300);
            var scannerParentElement = document.getElementById("scanner");
            if (scannerParentElement) {
                //append the jbScanner to an existing DOM element
                jbScanner.appendTo(scannerParentElement);
            }
        }
    </script>
</body>

</html>