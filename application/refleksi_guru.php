<?php
if ($_GET['act'] == '') {
    $d = mysql_fetch_array(mysql_query("SELECT * FROM rb_kelas WHERE kode_kelas='$_GET[id]'"));
    $m = mysql_fetch_array(mysql_query("SELECT * FROM rb_mata_pelajaran WHERE kode_pelajaran='$_GET[kd]'"));
    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Hasil Refleksi Guru $_GET[tahun]</b></h3>
                </div>
              <div class='box-body'>

              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <div class='col-md-12'>
                  <table class='table table-condensed table-bordered table-striped'>
                      <thead>
                      <tr>
                        <th>No</th>
                        <th>Nip</th>
                        <th>Nama Guru</th>";

    // Ambil data kesan dari tabel rb_rating untuk header kolom
    $rating_query = mysql_query("SELECT kesan FROM rb_rating ORDER BY id");
    $ratings = [];
    while ($rating = mysql_fetch_array($rating_query)) {
        $ratings[] = $rating['kesan']; // Simpan kesan untuk dipakai nanti
        echo "<th>" . $rating['kesan'] . "</th>";
    }
    echo "</tr>
                    </thead>
                    <tbody>";

    // Iterasi data guru
    $no = 1;
    $tampil = mysql_query("
        SELECT nip, nama_guru 
        FROM rb_guru 
        WHERE id_jenis_ptk NOT IN (6, 7) 
        ORDER BY nama_guru ASC
    ");

    while ($guru = mysql_fetch_array($tampil)) {
        echo "<tr>
                <td>$no</td>
                <td>$guru[nip]</td>
                <td>$guru[nama_guru]</td>";

        // Iterasi setiap kesan untuk menghitung jumlah
        foreach ($ratings as $kesan) {
            $count_query = mysql_query("
                SELECT COUNT(*) as jumlah 
                FROM rb_pertanyaan_penilaian_jawab 
                WHERE nip='$guru[nip]' AND jawaban='$kesan'
            ");
            $count = mysql_fetch_array($count_query)['jumlah'];
            echo "<td align='center'>$count</td>";
        }

        echo "</tr>";
        $no++;
    }

    echo "</tbody>
                  </table>
                </div>
              </div>
            </div>";
}
?>
