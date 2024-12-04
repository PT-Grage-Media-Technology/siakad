<style>
  .table-responsive {
    overflow-x: auto;
    /* Hanya aktifkan scroll horizontal jika diperlukan */
  }

  @media (min-width: 768px) {
    .table-responsive {
      overflow-x: visible;
      /* Nonaktifkan scroll horizontal di desktop */
    }
  }

  /* Gaya tabel baru */
  table {
    border-collapse: collapse;
    width: 100%;
    text-align: center;
  }

  th,
  td {
    border: 1px solid black;
    padding: 5px;
    text-align: center;
  }

  th {
    background-color: #f2f2f2;
  }
</style>

<?php if ($_GET[act] == '') { ?>
  <div class="col-xs-12">
    <div class="box">
      <div class="box-header">
        <h3 class="box-title"><?php if (isset($_GET[kelas]) and isset($_GET[tahun])) {
          echo "Rekap Absensi siswa";
        } else {
          echo "Rekap Sumatif Ruang Lingkup " . date('Y');
        } ?></h3>
        <?php
        $siswa = mysql_fetch_array(mysql_query("SELECT * FROM rb_siswa where nisn='$_GET[id]'"))
          ?>

      </div><!-- /.box-header -->
      <div class="box-body">
        <div class="table-responsive">
          <table>
            <thead>
              <tr>
                <th rowspan="2">No</th>
                <th rowspan="2">Nama Siswa</th>
                <th colspan="3">SUMATIF LINGKUP MATERI</th>
                <th rowspan="2">NA SUMATIF (S)</th>
                <th rowspan="2">STS</th>
                <th rowspan="2">NON TES</th>
                <th rowspan="2">NA SUMATIF AKHIR SEMESTER (AS)</th>
                <th rowspan="2">Nilai Rapor<br>(Rerata S + AS)</th>
              </tr>
              <tr>
                <th>Proses perumusan pancasila</th>
                <th>Proses perumusan pancasila</th>
                <th>Proses perumusan pancasila</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 1;
              $tampil = mysql_query("SELECT * FROM rb_siswa a JOIN rb_jenis_kelamin b ON a.id_jenis_kelamin=b.id_jenis_kelamin where a.kode_kelas='$_GET[id]' ORDER BY a.id_siswa");
              while ($r = mysql_fetch_array($tampil)) {
                echo "
      <tr>
        <td>$no</td>
        <td>$r[nama]
              <input type='number' value='$r[nisn]' name='nisn[$no]' style='width:50px;' hidden>
              </td>
        <td>90</td>
        <td>90</td>
        <td>90</td>
        <td>80</td>
        <td>88</td>
        <td>95</td>
        <td>90</td>
        <td>90</td>
      </tr>";
                $no++;
              }
              ?>
            </tbody>
          </table>

        </div>
      </div><!-- /.box-body -->
    </div>
  </div>
  <?php
}
?>