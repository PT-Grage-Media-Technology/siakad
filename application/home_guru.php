<?php
// Ambil tahun akademik terbaru (id_tahun_akademik paling besar)
$latest_year = mysql_fetch_array(mysql_query("SELECT id_tahun_akademik FROM rb_tahun_akademik ORDER BY id_tahun_akademik DESC LIMIT 1"));

// Jika tidak ada tahun akademik dipilih, set default ke tahun terbaru
$tahun_dipilih = isset($_GET['tahun']) ? $_GET['tahun'] : $latest_year['id_tahun_akademik'];

// Ambil data jadwal mengajar
$query = "SELECT a.*, e.nama_kelas, b.namamatapelajaran, b.kode_pelajaran, c.nama_guru, d.nama_ruangan 
          FROM rb_jadwal_pelajaran a 
          JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran
          JOIN rb_guru c ON a.nip=c.nip 
          JOIN rb_ruangan d ON a.kode_ruangan=d.kode_ruangan
          JOIN rb_kelas e ON a.kode_kelas=e.kode_kelas 
          WHERE a.nip='$_SESSION[id]' AND a.id_tahun_akademik='$tahun_dipilih' 
          ORDER BY a.hari DESC";

$tampil = mysql_query($query);

// Tampilkan form pemilihan tahun akademik
echo "<form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>";
echo "<select name='tahun' style='padding:4px' onchange='this.form.submit()'>";
echo "<option value=''>- Pilih Tahun Akademik -</option>";
$tahun = mysql_query("SELECT * FROM rb_tahun_akademik");
while ($k = mysql_fetch_array($tahun)) {
    $selected = ($tahun_dipilih == $k['id_tahun_akademik']) ? 'selected' : '';
    echo "<option value='$k[id_tahun_akademik]' $selected>$k[nama_tahun]</option>";
}
echo "</select>";
echo "</form>";

// Tampilkan tabel jadwal mengajar
echo "<table id='example1' class='table table-bordered table-striped'>";
echo "<thead>
        <tr>
            <th style='width:20px'>No</th>
            <th>Kode Pelajaran</th>
            <th>Jadwal Pelajaran</th>
            <th>Kelas</th>
            <th>Guru</th>
            <th>Hari</th>
            <th>Mulai</th>
            <th>Selesai</th>
            <th>Ruangan</th>
            <th>Semester</th>
        </tr>
      </thead>
      <tbody>";

$no = 1;
while ($r = mysql_fetch_array($tampil)) {
    echo "<tr>
            <td>$no</td>
            <td>$r[kode_pelajaran]</td>
            <td>$r[namamatapelajaran]</td>
            <td>$r[nama_kelas]</td>
            <td>$r[nama_guru]</td>
            <td>$r[hari]</td>
            <td>$r[jam_mulai]</td>
            <td>$r[jam_selesai]</td>
            <td>$r[nama_ruangan]</td>
            <td>$r[id_tahun_akademik]</td>
          </tr>";
    $no++;
}

echo "</tbody></table>";
?>