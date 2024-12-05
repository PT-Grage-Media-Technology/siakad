<?php
// ... existing code ...
if ($_GET['act'] == '') {
  $d = mysql_fetch_array(mysql_query("SELECT * FROM rb_kelas WHERE kode_kelas='$_GET[id]'"));
  $m = mysql_fetch_array(mysql_query("SELECT * FROM rb_mata_pelajaran WHERE kode_pelajaran='$_GET[kd]'"));
  
  // ... existing code ...
  
  $rating_query = mysql_query("SELECT * FROM rb_rating ORDER BY id");
  while ($rating = mysql_fetch_array($rating_query)) {
    echo "<th>" . $rating["kesan"] . $rating["id"] . "</th>";
    $ratingArray[] = $rating['id'];
  }
  
  // ... existing code ...

  $no = 1;
  $tampil = mysql_query("
        SELECT g.nip, g.nama_guru, p.jawaban 
        FROM rb_guru g 
        INNER JOIN rb_pertanyaan_penilaian_jawab p ON g.nip = p.nip 
        WHERE g.id_jenis_ptk NOT IN (6, 7) 
        GROUP BY g.nip 
        ORDER BY g.nama_guru ASC
    ");

  while ($r = mysql_fetch_array($tampil)) {
      // Mengambil nilai rating berdasarkan kesan
      $ratingValues = []; // Reset array untuk setiap guru
      foreach ($ratingArray as $ratingId) {
          $ratingValue = mysql_fetch_array(mysql_query("SELECT jawaban FROM rb_pertanyaan_penilaian_jawab WHERE nip='$r[nip]' AND id_rating='$ratingId'"));
          $ratingValues[] = $ratingValue['jawaban'] ?? ''; // Menggunakan null coalescing untuk menghindari error
      }
      echo "<tr>
                  <td>$no</td>
                  <td>$r[nip]</td>
                  <td>$r[nama_guru]</td>";
      foreach ($ratingValues as $value) {
          echo "<td>$value</td>"; // Menampilkan nilai rating
      }
      echo "</tr>";
      $no++;
  }

  // ... existing code ...
}