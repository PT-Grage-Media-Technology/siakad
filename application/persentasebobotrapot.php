<?php
echo"<div class='col-xs-12'>
<div class='box'>
  <div class='box-header'>
    <h3 class='box-title'>Data Bobot Nilai Raport </h3>";
   if ($_SESSION['level'] != 'kepala') { 
      echo"<a class='pull-right btn btn-primary btn-sm' href='index.php?view=nilai'>Tambahkan Data</a> ";
    } 
  echo"</div>
  <div class='box-body'>";

if (isset($_POST['update'])) {
  foreach ($_POST['bobot'] as $id => $bobot) {
    // Escape input untuk mencegah SQL Injection
    $id_safe = mysql_real_escape_string($id);
    $bobot_safe = mysql_real_escape_string($bobot);

    // Update hanya nilai bobot yang dikirimkan
    mysql_query("UPDATE rb_bobot_raport SET bobot='$bobot_safe' WHERE id='$id_safe'");
  }
  echo "<div class='alert alert-success'>Data berhasil diperbarui!</div>";
}

$tampil = mysql_query("SELECT * FROM rb_bobot_raport");

// Form untuk menampilkan semua data
echo "<form method='POST' action=''>";
echo "<table style='border-collapse: collapse; width: auto;'>";

while ($kriteriaNilai = mysql_fetch_array($tampil)) {
  echo "
    <tr>
        <td style='text-align: left; vertical-align: middle; padding: 3px;'>
            {$kriteriaNilai['jenis_nilai']} =
        </td>
        <td style='text-align: center; vertical-align: middle; padding: 3px;'>
            <input name='bobot[{$kriteriaNilai['id']}]' type='number' value='{$kriteriaNilai['bobot']}'
                   style='width: 50px; text-align: center; margin: 0;'>
        </td>
        <td style='text-align: left; vertical-align: middle; padding: 3px;'>%</td>
    </tr>";
}

echo "</table>";
echo "<button type='submit' name='update' class='pull-right btn btn-primary btn-sm mt-2'>Update Semua</button>";
echo "</form>
      </div>
      </div>
      </div>
";
?>