<?php
require_once "koneksi.php";

// ==========================
// AMBIL FILTER
// ==========================
$bulan = $_GET['bulan'] ?? '';
$tahun = $_GET['tahun'] ?? '';


// ==========================
// TOTAL MASUK
// ==========================
$stmt = $pdo->query("
    SELECT SUM(nominal)
    FROM mini_cashflow
    WHERE jenis = 'masuk'
");

$total_masuk = $stmt->fetchColumn() ?? 0;


// ==========================
// TOTAL KELUAR
// ==========================
$stmt = $pdo->query("
    SELECT SUM(nominal)
    FROM mini_cashflow
    WHERE jenis = 'keluar'
");

$total_keluar = $stmt->fetchColumn() ?? 0;


// ==========================
// SALDO AKHIR
// ==========================
$saldo_akhir = $total_masuk - $total_keluar;


// ==========================
// FILTER RIWAYAT TRANSAKSI
// ==========================
$sql = "
    SELECT *
    FROM mini_cashflow
    WHERE 1=1
";

$params = [];


// Filter bulan
if ($bulan !== '') {
    $sql .= " AND MONTH(tanggal) = :bulan";
    $params[':bulan'] = $bulan;
}


// Filter tahun
if ($tahun !== '') {
    $sql .= " AND YEAR(tanggal) = :tahun";
    $params[':tahun'] = $tahun;
}


// Urutkan berdasarkan tanggal
$sql .= " ORDER BY tanggal DESC, id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$transaksi = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Mini Cashflow</title>

    <!-- ==========================
         BOOTSTRAP CSS
    =========================== -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>

<body class="bg-light">
    <!-- ==========================
         NAVBAR
    =========================== -->
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

    <!-- ==========================
         MAIN CONTENT
    =========================== -->

    <main class="container py-5">
        <!-- ==========================
             HEADER
        =========================== -->
        <div
            class="d-flex justify-content-between align-items-center mb-4"
        >
            <div>
            <h1 class="fw-bold mb-1 fs-3">
                Dashboard Kas
            </h1>
                <p class="text-muted mb-0">
                    Kelola pemasukan dan pengeluaran Anda
                </p>
            </div>

            <a
                href="tambah.php"
                class="btn btn-primary"
            >
                + Tambah Transaksi
            </a>
        </div>

        <!-- ==========================
             DASHBOARD CARD
        =========================== -->

        <div class="row g-4 mb-5">
            <!-- ==========================
                 TOTAL MASUK
            =========================== -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-muted mb-2">
                            Total Masuk
                        </p>
                        <h3 class="fw-bold text-success mb-0">
                            Rp
                            <?= number_format(
                                $total_masuk,
                                0,
                                ',',
                                '.'
                            ) ?>
                        </h3>
                    </div>
                </div>
            </div>

            <!-- ==========================
                 TOTAL KELUAR
            =========================== -->

            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-muted mb-2">
                            Total Keluar
                        </p>
                        <h3 class="fw-bold text-danger mb-0">
                            Rp
                            <?= number_format(
                                $total_keluar,
                                0,
                                ',',
                                '.'
                            ) ?>
                        </h3>
                    </div>
                </div>
            </div>

            <!-- ==========================
                 SALDO AKHIR
            =========================== -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-muted mb-2">
                            Saldo Akhir
                        </p>
                        <h3 class="fw-bold text-primary mb-0">
                            Rp
                            <?= number_format(
                                $saldo_akhir,
                                0,
                                ',',
                                '.'
                            ) ?>
                        </h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- ==========================
             CARD RIWAYAT
        =========================== -->

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <!-- Judul -->
                <div class="mb-4">
                    <h4 class="fw-bold mb-1">
                        Riwayat Transaksi
                    </h4>

                    <p class="text-muted mb-0">
                        Daftar pemasukan dan pengeluaran
                    </p>
                </div>

                <!-- ==========================
                     FILTER
                =========================== -->

                <form
                    method="GET"
                    class="row g-3 mb-4"
                >
                    <!-- ==========================
                         BULAN
                    =========================== -->

                    <div class="col-md-4">
                        <label
                            for="bulan"
                            class="form-label fw-semibold"
                        >
                            Bulan
                        </label>

                        <select
                            name="bulan"
                            id="bulan"
                            class="form-select"
                        >
                            <option value="">
                                Semua Bulan
                            </option>

                            <option
                                value="1"
                                <?= $bulan == '1' ? 'selected' : '' ?>
                            >
                                Januari
                            </option>

                            <option
                                value="2"
                                <?= $bulan == '2' ? 'selected' : '' ?>
                            >
                                Februari
                            </option>

                            <option
                                value="3"
                                <?= $bulan == '3' ? 'selected' : '' ?>
                            >
                                Maret
                            </option>

                            <option
                                value="4"
                                <?= $bulan == '4' ? 'selected' : '' ?>
                            >
                                April
                            </option>

                            <option
                                value="5"
                                <?= $bulan == '5' ? 'selected' : '' ?>
                            >
                                Mei
                            </option>

                            <option
                                value="6"
                                <?= $bulan == '6' ? 'selected' : '' ?>
                            >
                                Juni
                            </option>

                            <option
                                value="7"
                                <?= $bulan == '7' ? 'selected' : '' ?>
                            >
                                Juli
                            </option>

                            <option
                                value="8"
                                <?= $bulan == '8' ? 'selected' : '' ?>
                            >
                                Agustus
                            </option>

                            <option
                                value="9"
                                <?= $bulan == '9' ? 'selected' : '' ?>
                            >
                                September
                            </option>

                            <option
                                value="10"
                                <?= $bulan == '10' ? 'selected' : '' ?>
                            >
                                Oktober
                            </option>

                            <option
                                value="11"
                                <?= $bulan == '11' ? 'selected' : '' ?>
                            >
                                November
                            </option>

                            <option
                                value="12"
                                <?= $bulan == '12' ? 'selected' : '' ?>
                            >
                                Desember
                            </option>
                        </select>
                    </div>

                    <!-- ==========================
                         TAHUN
                    =========================== -->

                    <div class="col-md-4">
                        <label
                            for="tahun"
                            class="form-label fw-semibold"
                        >
                            Tahun
                        </label>

                        <input
                            type="number"
                            name="tahun"
                            id="tahun"
                            class="form-control"
                            placeholder="Contoh: 2026"
                            value="<?= htmlspecialchars($tahun) ?>"
                        >
                    </div>

                    <!-- ==========================
                         BUTTON FILTER
                    =========================== -->

                    <div
                        class="col-md-4 d-flex align-items-end gap-2"
                    >
                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            Filter
                        </button>

                        <a
                            href="index.php"
                            class="btn btn-outline-secondary"
                        >
                            Reset
                        </a>
                    </div>
                </form>

                <!-- ==========================
                     TABEL TRANSAKSI
                =========================== -->

                <div class="table-responsive">

                    <table
                        class="table table-hover align-middle"
                    >
                        <!-- HEADER TABLE -->

                        <thead class="table-light">
                            <tr>
                                <th>
                                    No
                                </th>
                                <th>
                                    Nama Transaksi
                                </th>
                                <th>
                                    Jenis
                                </th>
                                <th>
                                    Nominal
                                </th>
                                <th>
                                    Tanggal
                                </th>
                                <th class="text-center">
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                        <!-- BODY TABLE -->

                        <tbody>
                            <?php if (empty($transaksi)): ?>
                                <!-- Tidak ada data -->
                                <tr>
                                    <td
                                        colspan="6"
                                        class="text-center text-muted py-4"
                                    >
                                        Belum ada transaksi.
                                    </td>
                                </tr>

                            <?php else: ?>
                                <?php $no = 1; ?>

                                <?php foreach ($transaksi as $row): ?>
                                    <tr>
                                        <!-- NO -->
                                        <td>
                                            <?= $no++ ?>
                                        </td>

                                        <!-- NAMA TRANSAKSI -->

                                        <td class="fw-semibold">
                                            <?= htmlspecialchars(
                                                $row['nama_transaksi']
                                            ) ?>
                                        </td>

                                        <!-- JENIS -->
                                        <td>
                                            <?php
                                            if ($row['jenis'] === 'masuk'):
                                            ?>
                                                <span
                                                    class="badge bg-success"
                                                >
                                                    Masuk
                                                </span>
                                            <?php else: ?>
                                                <span
                                                    class="badge bg-danger"
                                                >
                                                    Keluar
                                                </span>

                                            <?php endif; ?>
                                        </td>

                                        <!-- NOMINAL -->
                                        <td>
                                            Rp
                                            <?= number_format(
                                                $row['nominal'],
                                                0,
                                                ',',
                                                '.'
                                            ) ?>
                                        </td>

                                        <!-- TANGGAL -->
                                        <td>
                                            <?= date(
                                                'd-m-Y',
                                                strtotime(
                                                    $row['tanggal']
                                                )
                                            ) ?>
                                        </td>

                                        <!-- AKSI -->
                                        <td class="text-center">
                                            <a
                                                href="hapus.php?id=<?= $row['id'] ?>"
                                                class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?')"
                                            >
                                                Hapus
                                            </a>
                                        </td>
                                    </tr>

                                <?php endforeach; ?>
                            <?php endif; ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- ==========================
         BOOTSTRAP JS
    =========================== -->

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    ></script>

</body>
</html>