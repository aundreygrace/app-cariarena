<?php

$servername = getenv('DB_HOST');
$username   = getenv('DB_USERNAME');
$password   = getenv('DB_PASSWORD');
$dbname     = getenv('DB_DATABASE');

$conn = new mysqli($servername, $username, $password, $dbname);

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Koneksi database gagal: " . $conn->connect_error]));
}

// Set header untuk response JSON
header('Content-Type: application/json');

// Ambil data dari form
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$phone = $_POST['phone'] ?? '';
$address = $_POST['address'] ?? '';
$role = $_POST['role'] ?? 'user';

// Debug: Lihat data yang diterima
error_log("Data received: " . print_r($_POST, true));

// Validasi data
if (empty($name) || empty($email) || empty($password) || empty($phone) || empty($address)) {
    echo json_encode(["success" => false, "message" => "Semua field harus diisi."]);
    exit;
}

// Validasi email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["success" => false, "message" => "Format email tidak valid."]);
    exit;
}

// Validasi panjang password
if (strlen($password) < 8) {
    echo json_encode(["success" => false, "message" => "Password harus minimal 8 karakter."]);
    exit;
}

// Cek apakah email sudah terdaftar
$checkEmail = $conn->prepare("SELECT id FROM users WHERE email = ?");
if (!$checkEmail) {
    echo json_encode(["success" => false, "message" => "Error prepare statement: " . $conn->error]);
    exit;
}

$checkEmail->bind_param("s", $email);
$checkEmail->execute();
$checkEmail->store_result();

if ($checkEmail->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "Email sudah terdaftar."]);
    $checkEmail->close();
    $conn->close();
    exit;
}
$checkEmail->close();

// Hash password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insert data ke database
$stmt = $conn->prepare("INSERT INTO users (name, email, password, phone, address, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())");
if (!$stmt) {
    echo json_encode(["success" => false, "message" => "Error prepare statement: " . $conn->error]);
    exit;
}

$stmt->bind_param("sssss", $name, $email, $hashedPassword, $phone, $address);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Pendaftaran berhasil! Silakan login."]);
} else {
    echo json_encode(["success" => false, "message" => "Terjadi kesalahan: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>