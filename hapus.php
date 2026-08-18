<?php

require_once "koneksi.php";

// Ambil ID dari URL
$id = $_GET['id'] ?? '';

// Validasi ID
if ($id === '' || !is_numeric($id)) {
    die('ID transaksi tidak valid.');
}

// Hapus data berdasarkan ID
$sql = "DELETE FROM mini_cashflow WHERE id = :id";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    ':id' => $id
]);

// Kembali ke dashboard
header('Location: index.php');
exit;