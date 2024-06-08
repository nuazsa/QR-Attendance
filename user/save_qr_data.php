<?php
// save_qr_data.php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $scannedText = $_POST['scannedText'];
    $classId = $_GET['id'];

    require_once '../component/connection.php';
    $pdo = connectToDatabase();

    date_default_timezone_set('Asia/Jakarta');
    $current_date = date('Y-m-d');
    $current_time = date('H:i');

    // Mengambil QR code terbaru dari kelas yang sesuai
    $stmt = $pdo->prepare('SELECT * FROM qrcodes WHERE id_kelas = :id_kelas ORDER BY tanggal_pembuatan DESC LIMIT 1');
    $stmt->bindParam(':id_kelas', $classId);
    $stmt->execute();
    
    $qr_real = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($qr_real && $qr_real['qr_code'] == $scannedText) {
        // Memasukkan data ke tabel presensi
        $stmt = $pdo->prepare("INSERT INTO presensi (id_kelas, id_pengguna, id_qrcode, tanggal, jam) VALUES (:id_kelas, :id_pengguna, :id_qrcode, :tanggal, :jam)");
        $stmt->bindParam(':id_kelas', $classId);
        $stmt->bindParam(':id_pengguna', $_SESSION['user_id']);
        $stmt->bindParam(':id_qrcode', $qr_real['id_qrcode']);
        $stmt->bindParam(':tanggal', $current_date);
        $stmt->bindParam(':jam', $current_time);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Presence added successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to insert data']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid QR code']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>