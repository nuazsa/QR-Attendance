<?php
require_once '../component/connection.php';
$pdo = connectToDatabase();

// Get the JSON data from the request
$data = json_decode(file_get_contents('php://input'), true);

$scanContent = $data['content'];
$scanDate = $data['date'];

$current_date = date('Y-m-d');
$current_time = date('H:i');

// Prepare the SELECT query
$selectStmt = $pdo->prepare("SELECT * FROM qrcodes WHERE qr_code = :qr_code");
$selectStmt->bindParam(':qr_code', $scanContent);
$selectStmt->execute();

$qrcode = $selectStmt->fetch(PDO::FETCH_ASSOC);

// Prepare the SELECT query
$selectStmt = $pdo->prepare("SELECT MAX(pertemuan)AS pertemuan, tanggal FROM `presensi` WHERE id_kelas = :id_kelas GROUP BY tanggal");
$selectStmt->bindParam(':id_kelas', $qrcode['id_kelas']);
$selectStmt->execute();

$kelas = $selectStmt->fetch(PDO::FETCH_ASSOC);
// var_dump($kelas);

if ($kelas['tanggal'] == '2024-05-29') {
    $pertemuan = $kelas['pertemuan'];
} else {
    $pertemuan = $kelas['pertemuan'] + 1;
}


if ($qrcode) {
    // Prepare the INSERT query
    $insertStmt = $pdo->prepare("INSERT INTO presensi (id_kelas, id_user, pertemuan, uniq_qr, tanggal, jam) VALUES (:id_kelas, :id_user, :pertemuan, :uniq_qr, :tanggal, :jam)");
    $insertStmt->bindParam(':id_kelas', $qrcode['id_kelas']);
    $insertStmt->bindParam(':id_user', $qrcode['id_user']);
    $insertStmt->bindParam(':pertemuan', $pertemuan);
    $insertStmt->bindParam(':uniq_qr', $scanContent);
    $insertStmt->bindParam(':tanggal', $current_date);
    $insertStmt->bindParam(':jam', $current_time);

    if ($insertStmt->execute()) {
        // Prepare the UPDATE query
        $updateStmt = $pdo->prepare("UPDATE qrcodes SET qr_code = NULL, status = 'success' WHERE qr_code = :qr_code");
        $updateStmt->bindParam(':qr_code', $scanContent);

        if ($updateStmt->execute()) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to update QR code"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to insert presensi"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "QR code not found"]);
}
?>
