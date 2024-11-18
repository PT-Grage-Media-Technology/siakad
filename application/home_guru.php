<?php 
// $tes = mysql_query("SELECT * FROM rb_jadwal_pelajaran WHERE nip='$_SESSION[id]'");
$tampil = mysql_query("SELECT a.*, e.nama_kelas, b.namamatapelajaran, b.kode_pelajaran, c.nama_guru, d.nama_ruangan 
                                   FROM rb_jadwal_pelajaran a 
                                   JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran
                                   JOIN rb_guru c ON a.nip=c.nip 
                                   JOIN rb_ruangan d ON a.kode_ruangan=d.kode_ruangan
                                   JOIN rb_kelas e ON a.kode_kelas=e.kode_kelas 
                                   WHERE a.nip='$_SESSION[id]' AND a.id_tahun_akademik=20162 
                                   ORDER BY a.hari DESC");
$no = 1;
while ($r = mysql_fetch_array($tampil)){
  echo"<p>$no - $r[namamatapelajaran] == $r[nip]</p>";
  // var_dump($r) ;
}

// echo $_SESSION['id'];
echo "SELECT * FROM rb_mata_pelajaran WHERE nip='$_SESSION[id]'";
?>