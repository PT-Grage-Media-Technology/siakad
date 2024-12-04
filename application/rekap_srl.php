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
</style>

<?php if ($_GET[act] == '') { ?>
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Rekap Absensi Siswa Dummy</h3>
                <form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
                    <input type="hidden" name='view' value='rekapabsensiswa'>

                    <!-- Dropdown Tahun Akademik -->
                    <select name='tahun' style='padding:4px' onchange="this.form.submit()">
                        <?php
                        echo "<option value=''>- Pilih Tahun Akademik -</option>";
                        echo "<option value='2023' selected>2023</option>";
                        echo "<option value='2022'>2022</option>";
                        ?>
                    </select>

                    <!-- Dropdown Kelas -->
                    <select name='kelas' style='padding:4px' onchange="this.form.submit()">
                        <?php
                        echo "<option value=''>- Pilih Kelas -</option>";
                        echo "<option value='Kelas 1' selected>Kelas 1 - Kelas Pertama</option>";
                        echo "<option value='Kelas 2'>Kelas 2 - Kelas Kedua</option>";
                        ?>
                    </select>
                </form>

            </div><!-- /.box-header -->
            <div class="box-body">
                <div class="table-responsive">
                    <table id="example" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style='width:20px'>No</th>
                                <th>Jadwal Pelajaran</th>
                                <th>Kelas</th>
                                <th>Guru</th>
                                <th>Hari</th>
                                <th>Mulai</th>
                                <th>Selesai</th>
                                <th>Ruangan</th>
                                <th>Semester</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Data dummy absensi
                            $dummy_data = [
                                ['no' => 1, 'mapel' => 'Matematika', 'kelas' => 'Kelas 1', 'guru' => 'Guru A', 'hari' => 'Senin', 'mulai' => '08:00', 'selesai' => '09:00', 'ruangan' => 'Ruang 101', 'semester' => '1'],
                                ['no' => 2, 'mapel' => 'Bahasa Indonesia', 'kelas' => 'Kelas 1', 'guru' => 'Guru B', 'hari' => 'Selasa', 'mulai' => '09:00', 'selesai' => '10:00', 'ruangan' => 'Ruang 102', 'semester' => '1'],
                            ];

                            foreach ($dummy_data as $r) {
                                echo "<tr>
                      <td>{$r['no']}</td>
                      <td>{$r['mapel']}</td>
                      <td>{$r['kelas']}</td>
                      <td>{$r['guru']}</td>
                      <td>{$r['hari']}</td>
                      <td>{$r['mulai']}</td>
                      <td>{$r['selesai']}</td>
                      <td>{$r['ruangan']}</td>
                      <td>{$r['semester']}</td>
                      <td style='width:70px !important'><center>
                          <a class='btn btn-success btn-xs' title='Tampil List Absensi' href='#'><span class='glyphicon glyphicon-th'></span> Tampilkan</a>
                        </center></td>
                    </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div><!-- /.box-body -->
            <?php
            if ($_GET[kelas] == '' and $_GET[tahun] == '') {
                echo "<center style='padding:60px; color:red'>Silahkan Memilih Tahun akademik dan Kelas Terlebih dahulu...</center>";
            }
            ?>
        </div>
    </div>
<?php
} elseif ($_GET[act] == 'tampilabsen') {
    // Data dummy untuk tampilan absensi
    echo "<div class='col-md-12 table-responsive'>
            <div class='box box-info table-responsive'>
                <div class='box-header with-border'>
                    <h3 class='box-title'>Rekap Data Absensi Siswa Dummy</h3>
                </div>
                <div class='box-body'>
                    <div class='col-md-12'>
                        <table class='table table-condensed table-hover'>
                            <tbody>
                                <tr><th width='120px' scope='row'>Kode Kelas</th> <td>Kelas 1</td></tr>
                                <tr><th scope='row'>Nama Kelas</th> <td>Kelas Pertama</td></tr>
                                <tr><th scope='row'>Mata Pelajaran</th> <td>Matematika</td></tr>
                            </tbody>
                        </table>
                    </div>
                    <div class='col-md-12'>
                        <table class='table table-condensed table-bordered table-striped table-responsive'>
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NISN</th>
                                    <th>Nama Siswa</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Pertemuan</th>
                                    <th>Hadir</th>
                                    <th>Sakit</th>
                                    <th>Izin</th>
                                    <th>Alpa</th>
                                    <th>Sikap</th>
                                    <th>Keterampilan</th>
                                    <th>Pengetahuan</th>
                                    <th>Total</th>
                                    <th>Rata-Rata</th>
                                    <th><center>% Kehadiran</center></th>
                                </tr>
                            </thead>
                            <tbody>";

    // Data dummy siswa
    $dummy_siswa = [
        ['no' => 1, 'nisn' => '123456', 'nama' => 'Siswa A', 'jenis_kelamin' => 'L', 'hadir' => 10, 'sakit' => 1, 'izin' => 0, 'alpa' => 1],
        ['no' => 2, 'nisn' => '123457', 'nama' => 'Siswa B', 'jenis_kelamin' => 'P', 'hadir' => 9, 'sakit' => 2, 'izin' => 0, 'alpa' => 2],
    ];

    foreach ($dummy_siswa as $s) {
        $total = 12; // Total pertemuan
        $persen = ($s['hadir'] / $total) * 100;
        echo "<tr>
                        <td>{$s['no']}</td>
                        <td>{$s['nisn']}</td>
                        <td>{$s['nama']}</td>
                        <td>{$s['jenis_kelamin']}</td>
                        <td align=center>$total</td>
                        <td align=center>{$s['hadir']}</td>
                        <td align=center>{$s['sakit']}</td>
                        <td align=center>{$s['izin']}</td>
                        <td align=center>{$s['alpa']}</td>
                        <td align=center>80</td>
                        <td align=center>85</td>
                        <td align=center>90</td>
                        <td align=center>" . ($s['hadir'] + $s['sakit'] + $s['izin'] + $s['alpa']) . "</td>
                        <td align=center>85.00</td>
                        <td align=right>" . number_format($persen, 2) . " %</td>
                      </tr>";
    }

    echo "</tbody>
                  </table>
                </div>
              </div>
            </div>";
}
?>