<?php
session_start(); // Memulai session

// Jika tahun_terpilih kosong, berarti pertama kali load halaman, maka ambil data terakhir
if (empty($_GET['tahun'])) {
    $data_terakhir = mysql_fetch_array(mysql_query("SELECT * FROM rb_tahun_akademik ORDER BY id_tahun_akademik DESC LIMIT 1"));
    $tahun_terpilih = $data_terakhir['id_tahun_akademik'];  // Ambil ID tahun terakhir
} else {
    $data_terakhir = mysql_fetch_array(mysql_query("SELECT * FROM rb_tahun_akademik WHERE id_tahun_akademik = '" . $_GET['tahun'] . "'"));
    $tahun_terpilih = $data_terakhir['id_tahun_akademik'];  // Ambil ID tahun terakhir
}

echo "<div class='col-xs-12 col-md-12'>  
<div class='box'>
  <div class='box-header'>
    <h3 class='box-title'>Laporan Sumatif Harian : <b>$nama</b></h3>
    <form id='year-form' style='margin-right:5px; margin-top:0px' class='form-inline float-right' action='' method='GET'>
      <input type='hidden' name='view' value='raport'>
      <input type='hidden' name='act' value='detailsiswa'>
      <select name='tahun' class='form-control mb-2 mr-sm-2' onchange='document.getElementById(\"year-form\").submit();'>
        <option value=''>- Pilih Tahun Akademik -</option>";
$tahun = mysql_query("SELECT * FROM rb_tahun_akademik");
while ($k = mysql_fetch_array($tahun)) {
    if ($tahun_terpilih == $k['id_tahun_akademik']) {
        echo "<option value='$k[id_tahun_akademik]' selected>$k[nama_tahun]</option>";
    } else {
        // Else langsung memilih data terakhir
        $selected = ($id_terakhir == $k['id_tahun_akademik']) ? "selected" : "";
        echo "<option value='$k[id_tahun_akademik]' $selected>$k[nama_tahun]</option>";
    }
}

echo "</select>
    </form>
  </div>
  <div class='box-body'>
    <b class='semester'>CAPAIAN KOMPETENSI</b>
    <div class='table-responsive'>
      <table class='table table-bordered table-striped'>
        <tr>
          <th style='border:1px solid #ffffff; background-color:lightblue' width='40px' rowspan='2'>No</th>
          <th style='border:1px solid #ffffff; background-color:lightblue' width='300px' rowspan='2'><center>Mata Pelajaran</center></th>
          <th style='border:1px solid #ffffff; background-color:lightblue' rowspan='2'><center>KKM</center></th>
          <th style='border:1px solid #ffffff; background-color:lightblue' colspan='4' style='text-align:center'><center>Nilai</center></th>
        </tr>";

  echo $_SESSION[id];
  echo $_SESSION[kode_kelas];
// Ambil data mata pelajaran berdasarkan kondisi tertentu
$mapel = mysql_fetch_array(mysql_query("SELECT * FROM rb_jadwal_pelajaran a 
                                        JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran 
                                        WHERE a.kode_kelas='$_SESSION[kode_kelas]' 
                                        AND a.id_tahun_akademik='$tahun_terpilih' 
                                        AND b.id_kelompok_mata_pelajaran='$k[id_kelompok_mata_pelajaran]' 
                                        AND b.kode_kurikulum='$kurikulum[kode_kurikulum]'"));

// Cek apakah query untuk mapel berhasil dan data ditemukan
if ($mapel) {
    var_dump($mapel);
    // Query untuk mengambil tanggal absensi yang diurutkan secara menurun (tanggal terbaru di atas)
    $query_pertemuan = mysql_query("
        SELECT DISTINCT tanggal 
        FROM rb_absensi_siswa 
        WHERE kodejdwl = '$mapel[kodejdwl]' 
        ORDER BY tanggal ASC
    ");
    
    // Hitung jumlah pertemuan (tanggal unik)
    $jumlah_pertemuan = mysql_num_rows($query_pertemuan);
    
    // Menampilkan header dengan jumlah pertemuan
    echo "<tr>";
    // Loop untuk membuat header dengan angka dinamis
    for ($i = 1; $i <= $jumlah_pertemuan; $i++) {
        echo "<th style='border:1px solid #ffffff; background-color:lightblue' colspan='1'><center>$i</center></th>";
    }
    echo "</tr>";
} else {
    // Jika tidak ada data mata pelajaran, tampilkan pesan atau set nilai default
    echo "<tr><td colspan='100%' style='text-align:center; color:red;'>Data mata pelajaran tidak ditemukan</td></tr>";
}


// $kdjdwl = mysql_fetch_array(mysql_query("SELECT * FROM  rb_jadwal_pelajaran a 
//                         JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran 
//                           where a.kode_kelas='$_SESSION[kode_kelas]' 
//                             AND a.id_tahun_akademik='$tahun_terpilih' 
//                               AND b.id_kelompok_mata_pelajaran='$k[id_kelompok_mata_pelajaran]'
//                                 AND b.kode_kurikulum='$kurikulum[kode_kurikulum]'"));
// $total = mysql_num_rows(mysql_query("SELECT * FROM `rb_absensi_siswa` WHERE kodejdwl='$kdjdwl[kodejdwl]' GROUP BY tanggal"));
// var_dump($total)
// echo "<tr>";
// $pertemuan = 1; // Variabel untuk nomor pertemuan
// while ($pertemuan <= $total) {
//     echo "<th style='border:1px solid #ffffff; background-color:lightblue' colspan='1'><center>$pertemuan</center></th>";
//     $pertemuan++;
// }
// echo "</tr>";

if ($tahun_terpilih == '') {
    echo "<tr><td colspan=7><center style='padding:60px; color:red'>Silahkan Memilih Tahun akademik Terlebih dahulu...</center></td></tr>";
}
$kelompok = mysql_query("SELECT * FROM rb_kelompok_mata_pelajaran");
while ($k = mysql_fetch_array($kelompok)) {
    echo "<tr>
                <td style='border:1px solid #e3e3e3' colspan='2'><b>$k[nama_kelompok_mata_pelajaran]</b></td>
                <td style='border:1px solid #e3e3e3' colspan='5'></td>
              </tr>";
    $mapel = mysql_query("SELECT * FROM  rb_jadwal_pelajaran a 
                                    JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran 
                                      where a.kode_kelas='$_SESSION[kode_kelas]' 
                                        AND a.id_tahun_akademik='$tahun_terpilih' 
                                          AND b.id_kelompok_mata_pelajaran='$k[id_kelompok_mata_pelajaran]'
                                            AND b.kode_kurikulum='$kurikulum[kode_kurikulum]'");

    $no = 1;
    while ($m = mysql_fetch_array($mapel)) {
        $rapn = mysql_fetch_array(mysql_query("SELECT sum((nilai1+nilai2+nilai3+nilai4+nilai5)/5)/count(nisn) as raport FROM rb_nilai_pengetahuan where kodejdwl='$m[kodejdwl]' AND nisn='$iden[nisn]'"));
        $cekpredikat = mysql_num_rows(mysql_query("SELECT * FROM rb_predikat where kode_kelas='$_SESSION[kelas]'"));
        if ($cekpredikat >= 1) {
            $grade3 = mysql_fetch_array(mysql_query("SELECT * FROM `rb_predikat` where (" . number_format($rapn['raport']) . " >=nilai_a) AND (" . number_format($rapn['raport']) . " <= nilai_b) AND kode_kelas='$_SESSION[kelas]'"));
        } else {
            $grade3 = mysql_fetch_array(mysql_query("SELECT * FROM `rb_predikat` where (" . number_format($rapn['raport']) . " >=nilai_a) AND (" . number_format($rapn['raport']) . " <= nilai_b) AND kode_kelas='0'"));
        }

        $rapnk = mysql_fetch_array(mysql_query("SELECT sum(GREATEST(nilai1,nilai2,nilai3,nilai4,nilai5,nilai6))/count(nisn) as raport FROM rb_nilai_keterampilan where kodejdwl='$m[kodejdwl]' AND nisn='$iden[nisn]'"));
        $cekpredikat2 = mysql_num_rows(mysql_query("SELECT * FROM rb_predikat where kode_kelas='$_SESSION[kelas]'"));
        if ($cekpredikat2 >= 1) {
            $grade = mysql_fetch_array(mysql_query("SELECT * FROM `rb_predikat` where (" . number_format($rapnk['raport']) . " >=nilai_a) AND (" . number_format($rapnk['raport']) . " <= nilai_b) AND kode_kelas='$_SESSION[kelas]'"));
        } else {
            $grade = mysql_fetch_array(mysql_query("SELECT * FROM `rb_predikat` where (" . number_format($rapnk['raport']) . " >=nilai_a) AND (" . number_format($rapnk['raport']) . " <= nilai_b) AND kode_kelas='0'"));
        }

        // $nilai = mysql_fetch_array(mysql_query("  SELECT * 
        // FROM rb_absensi_siswa 
        // WHERE kodejdwl = '$m[kodejdwl]' 
        // AND nisn = '$_SESSION[id]'
        // ORDER BY tanggal ASC"));
        // echo "SELECT * 
        // FROM rb_absensi_siswa 
        // WHERE kodejdwl = '$m[kodejdwl]' 
        // AND nisn = '$_SESSION[id]'
        // ORDER BY tanggal ASC";
        // var_dump($nilai['nilai_keterampilan']);


        echo "<tr>
        <td align=center>$no</td>
        <td>$m[namamatapelajaran]</td>
        <td align=center>$m[kkm]</td>";

// Query untuk mengambil tanggal absensi yang diurutkan secara menurun (tanggal terbaru di atas)
$query_pertemuan = mysql_query("
  SELECT DISTINCT tanggal 
  FROM rb_absensi_siswa 
  WHERE kodejdwl = '$m[kodejdwl]'
  ORDER BY tanggal ASC
");

$jumlah_pertemuan = mysql_num_rows($query_pertemuan);

// Jika tidak ada pertemuan, tampilkan nilai 0
if ($jumlah_pertemuan == 0) {
    echo "<td align='center' colspan='1'>0</td>"; // Menampilkan nilai 0 jika tidak ada pertemuan
    echo "<td align='center' colspan='1'>0</td>"; // Menampilkan nilai 0 jika tidak ada pertemuan
    echo "<td align='center' colspan='1'>0</td>"; // Menampilkan nilai 0 jika tidak ada pertemuan
}
 else {
    // Inisialisasi variabel untuk menampilkan pertemuan
    $pertemuan_counter = 1;

    // Loop untuk mencetak <td> dinamis sesuai jumlah pertemuan
    while ($pertemuan = mysql_fetch_array($query_pertemuan)) {
        // Ambil nilai keterampilan, sikap, dan pengetahuan untuk pertemuan berdasarkan tanggal
        $query_nilai = mysql_query("
            SELECT nilai_keterampilan, nilai_sikap, nilai_pengetahuan 
            FROM rb_absensi_siswa 
            WHERE kodejdwl = '$m[kodejdwl]' 
            AND nisn = '$_SESSION[id]' 
            AND tanggal = '$pertemuan[tanggal]'
        ");
        
        // Ambil hasil nilai
        $nilai = mysql_fetch_array($query_nilai);

        // Jika nilai tidak ada, tampilkan nilai 0
        if (!$nilai) {
            $rata_rata = 0;
        } else {
            // Hitung rata-rata dari tiga nilai jika nilai ada
            $rata_rata = ($nilai['nilai_keterampilan'] + $nilai['nilai_sikap'] + $nilai['nilai_pengetahuan']) / 3;
        }

        // Tampilkan rata-rata nilai di dalam <td>
        echo "<td align='center' colspan='1'>" . number_format($rata_rata, 2) . "</td>";

        $pertemuan_counter++;
    }
}

echo "</tr>";




        $no++;
    }
}

echo "</table></div></div></div></div>";
?>
