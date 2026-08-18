<?php
require_once "koneksi.php";

$errors = [];

$nama_transaksi = '';
$jenis = '';
$nominal = '';
$tanggal = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nama_transaksi = trim($_POST['nama_transaksi'] ?? '');
    $jenis = $_POST['jenis'] ?? '';
    $nominal = $_POST['nominal'] ?? '';
    $tanggal = $_POST['tanggal'] ?? '';

    // Validasi nama transaksi
    if ($nama_transaksi === '') {
        $errors[] = 'Nama transaksi wajib diisi.';
    }

    // Validasi jenis
    if ($jenis === '') {
        $errors[] = 'Jenis transaksi wajib dipilih.';
    } elseif (!in_array($jenis, ['masuk', 'keluar'])) {
        $errors[] = 'Jenis transaksi tidak valid.';
    }

    // Validasi nominal
    if ($nominal === '' || !is_numeric($nominal) || $nominal <= 0) {
        $errors[] = 'Nominal wajib diisi dan harus lebih dari 0.';
    }

    // Validasi tanggal
    if ($tanggal === '') {
        $errors[] = 'Tanggal wajib diisi.';
    }

    // Jika tidak ada error, simpan ke database
    if (empty($errors)) {

        $sql = "
            INSERT INTO mini_cashflow
            (nama_transaksi, jenis, nominal, tanggal)
            VALUES
            (:nama_transaksi, :jenis, :nominal, :tanggal)
        ";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':nama_transaksi' => $nama_transaksi,
            ':jenis' => $jenis,
            ':nominal' => $nominal,
            ':tanggal' => $tanggal
        ]);

        header('Location: index.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >
    <title>Tambah Transaksi</title>
    <!-- Bootstrap CSS -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>

<body class="bg-light">
    <!-- Navbar -->
    <nav class="navbar navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a
                href="index.php"
                class="navbar-brand fw-bold"
            >
                Mini Cashflow
            </a>
        </div>
    </nav>

    <!-- Content -->
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <!-- Judul -->
                <div class="mb-4">
                    <h1 class="fw-bold mb-1 fs-3">
                        Tambah Transaksi
                    </h1>
                    <p class="text-muted mb-0">
                        Tambahkan pemasukan atau pengeluaran baru.
                    </p>
                </div>

                <!-- Error -->
                <?php if (!empty($errors)): ?>
                    <div
                        class="alert alert-danger"
                        role="alert"
                    >
                        <strong>
                            Terjadi kesalahan:
                        </strong>

                        <ul class="mb-0 mt-2">
                            <?php foreach ($errors as $error): ?>
                               <li>
                                    <?= htmlspecialchars($error) ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <!-- Form Card -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <form method="POST">
                            <!-- Nama Transaksi -->
                            <div class="mb-3">
                                <label
                                    for="nama_transaksi"
                                    class="form-label fw-semibold"
                                >
                                    Nama Transaksi
                                </label>

                                <input
                                    type="text"
                                    id="nama_transaksi"
                                    name="nama_transaksi"
                                    class="form-control"
                                    placeholder="Contoh: Gaji, Makan, Transportasi"
                                    value="<?= htmlspecialchars($nama_transaksi) ?>"
                                >
                            </div>

                            <!-- Jenis -->
                            <div class="mb-3">
                                <label
                                    for="jenis"
                                    class="form-label fw-semibold"
                                >
                                    Jenis Transaksi
                                </label>

                                <select
                                    id="jenis"
                                    name="jenis"
                                    class="form-select"
                                >

                                    <option value="">
                                        -- Pilih Jenis --
                                    </option>

                                    <option
                                        value="masuk"
                                        <?= $jenis === 'masuk' ? 'selected' : '' ?>
                                    >
                                        Pemasukan
                                    </option>

                                    <option
                                        value="keluar"
                                        <?= $jenis === 'keluar' ? 'selected' : '' ?>
                                    >
                                        Pengeluaran
                                    </option>
                                </select>
                            </div>

                            <!-- Nominal -->
                            <div class="mb-3">
                                <label
                                    for="nominal"
                                    class="form-label fw-semibold"
                                >
                                    Nominal
                                </label>

                                <div class="input-group">
                                    <span class="input-group-text">
                                        Rp
                                    </span>

                                    <input
                                        type="number"
                                        id="nominal"
                                        name="nominal"
                                        class="form-control"
                                        min="1"
                                        placeholder="Contoh: 50000"
                                        value="<?= htmlspecialchars($nominal) ?>"
                                    >

                                </div>
                            </div>

                            <!-- Tanggal -->
                            <div class="mb-4">
                                <label
                                    for="tanggal"
                                    class="form-label fw-semibold"
                                >
                                    Tanggal
                                </label>

                                <input
                                    type="date"
                                    id="tanggal"
                                    name="tanggal"
                                    class="form-control"
                                    value="<?= htmlspecialchars($tanggal) ?>"
                                >
                            </div>

                            <!-- Tombol -->
                            <div class="d-flex gap-2">
                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                >
                                    Simpan Transaksi
                                </button>
                                <a
                                    href="index.php"
                                    class="btn btn-outline-secondary"
                                >
                                    Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    ></script>

</body>
</html>