<?php
// Membuat tabel rekap SRL
echo "Rekap Sumatif Ruang Lingkup";
echo '<table border="1">';
echo '<tr>';
echo '<th>ID</th>';
echo '<th>Nama</th>';
echo '<th>Tanggal</th>';
echo '<th>Status</th>';
echo '</tr>';

// Contoh data, ganti dengan data yang sesuai
$data = [
    ['id' => 1, 'nama' => 'John Doe', 'tanggal' => '2023-01-01', 'status' => 'Aktif'],
    ['id' => 2, 'nama' => 'Jane Smith', 'tanggal' => '2023-01-02', 'status' => 'Tidak Aktif'],
];

// Menampilkan data dalam tabel
foreach ($data as $row) {
    echo '<tr>';
    echo '<td>' . $row['id'] . '</td>';
    echo '<td>' . $row['nama'] . '</td>';
    echo '<td>' . $row['tanggal'] . '</td>';
    echo '<td>' . $row['status'] . '</td>';
    echo '</tr>';
}

echo '</table>';
?>