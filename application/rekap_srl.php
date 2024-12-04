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
                <h3 class="box-title">Rekap Sumatif Ruang Lingkup</h3>
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
}
?>