<div class="col-xs-12">
    <div class="box">
        <div class="box-header">

            <?php
            // Ambil tahun akademik yang terbaru
            $tahun = mysql_query("SELECT * FROM rb_tahun_akademik ORDER BY id_tahun_akademik DESC");
            $tahun_terbaru = mysql_fetch_array($tahun); // Ambil tahun terbaru
            mysql_data_seek($tahun, 0); // Kembali ke awal data query untuk loop
            
            // Jika pengguna belum memilih tahun, gunakan tahun terbaru
            $tahun_dipilih = isset($_GET['tahun']) ? $_GET['tahun'] : $tahun_terbaru['id_tahun_akademik'];

            // Ambil nama tahun akademik yang dipilih
            $nama_tahun_dipilih = '';
            while ($k = mysql_fetch_array($tahun)) {
                if ($tahun_dipilih == $k['id_tahun_akademik']) {
                    $nama_tahun_dipilih = $k['nama_tahun'];
                }
            }
            mysql_data_seek($tahun, 0); // Kembali ke awal untuk loop dropdown
            ?>

            <?php
            // include 'koneksi.php';
            
            //Periksa apakah data dikirim melalui POST
// if (isset($_POST['kktpInput'])) {
//     // Tangkap data dari form
//     $kodejdwl = $_POST['kodejdwl'];
//     $kktp = $_POST['kktp'];
            
            //     // Tangkap parameter tahun dari URL
//     $tahun = isset($_GET['tahun']) ? $_GET['tahun'] : '';
            
            //     // Perbarui data KKTP di database
//     $query = mysql_query("UPDATE rb_jadwal_pelajaran SET kktp='$kktp' WHERE kodejdwl='$kodejdwl'");
            
            //     // Feedback dan pengalihan
//     if ($query) {
//         echo "<script>
//             alert('KKTP berhasil diperbarui!');
//             window.location='index.php?view=jadwalguru&tahun=$tahun';
//         </script>";
//     } else {
//         echo "<script>
//             alert('Gagal memperbarui KKTP!');
//             window.location='index.php?view=jadwalguru&tahun=$tahun';
//         </script>";
//     }
// } else {
//     // Jika halaman diakses tanpa submit, redirect atau tampilkan pesan
//     header('Location: index.php?view=jadwalguru&tahun=' . (isset($_GET['tahun']) ? $_GET['tahun'] : ''));
//     exit();
// }
            ?>




            <!-- Menampilkan form dan h3 -->
            <h3 class="box-title">
                Jadwal Mengajar anda pada - <?php echo $nama_tahun_dipilih; ?>
            </h3>

            <form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
                <!-- Tambahkan hidden input untuk menyimpan parameter view -->
                <input type="hidden" name="view" value="jadwalguru">
                <select name='tahun' style='padding:4px' onchange='this.form.submit()'>
                    <option value=''>- Pilih Tahun Akademik -</option>
                    <?php
                    while ($k = mysql_fetch_array($tahun)) {
                        $selected = ($tahun_dipilih == $k['id_tahun_akademik']) ? 'selected' : '';
                        echo "<option value='{$k['id_tahun_akademik']}' $selected>{$k['nama_tahun']}</option>";
                    }
                    ?>
                </select>
            </form>


        </div>
        <!-- /.box-header -->
        <class="box-body">
            <div class="table-responsive">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
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
                            <th>KKTP</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $tampil = mysql_query("SELECT a.*, e.nama_kelas, b.namamatapelajaran, b.kode_pelajaran, c.nama_guru, d.nama_ruangan 
                FROM rb_jadwal_pelajaran a 
                JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran
                JOIN rb_guru c ON a.nip=c.nip 
                JOIN rb_ruangan d ON a.kode_ruangan=d.kode_ruangan
                JOIN rb_kelas e ON a.kode_kelas=e.kode_kelas 
                WHERE a.nip='$_SESSION[id]' AND a.id_tahun_akademik='$tahun_dipilih' 
                ORDER BY a.hari DESC");

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
                        <td>$r[id_tahun_akademik]</td>";
                        if($r[kktp]){
                            echo "<td><form method='POST' class='form-horizontal' action='' id='kktpForm'>
                            <input type='hidden' name='kodejdwl' value='$r[kodejdwl]'>
                            <input style='width:50px'  name='kktp' type='number' value='$r[kktp]' style='padding:4px' onchange='submitFormWithAlert(this)'/>   
                          </form></td>";
                        }else{
                            echo "<form method='POST' class='form-horizontal' action='' id='kktpForm'>
                            <input type='hidden' name='kodejdwl' value='$r[kodejdwl]'>
                            <input style='width:50px' name='kktp' type='number' style='padding:4px' onchange='submitFormWithAlert(this)'/>   
                          </form>";
                        }
                     
                        echo"<td><a class='btn btn-success btn-xs' href='index.php?view=journalguru&act=lihat&id=$r[kodejdwl]&tahun=$r[id_tahun_akademik]'>Agenda Mengajar</a></td>
                    </tr>";
                    

                            $no++;




                        }
                        ?>
                    </tbody>
                </table>
            </div>

            



            <!-- <script>
                // Script untuk menangani pengisian nilai lama di modal
                document.addEventListener('DOMContentLoaded', function () {
                    const editButtons = document.querySelectorAll('.edit-kktp-btn');
                    const modalKodeJdwl = document.getElementById('modalKodeJdwl');
                    const modalKktp = document.getElementById('modalKktp');

                    editButtons.forEach(button => {
                        button.addEventListener('click', function () {
                            console.log("ds");
                            const kodejdwl = this.getAttribute('data-id');
                            const kktp = this.getAttribute('data-kktp');

                            modalKodeJdwl.value = kodejdwl;
                            modalKktp.value = kktp;
                        });
                    });
                });

                //memastikan edit kktp
                // document.addEventListener('DOMContentLoaded', function () {
                //     const saveButton = document.querySelector('.btn-primary');
                //     const modalKodeJdwl = document.getElementById('modalKodeJdwl');
                //     const modalKktp = document.getElementById('modalKktp');

                //     saveButton.addEventListener('click', function (e) {
                //         if (!modalKodeJdwl.value || !modalKktp.value) {
                //             e.preventDefault();
                //             alert('Pastikan semua field diisi!');
                //         }
                //     });
                // });

            </script> -->
<?php
// Cek apakah form disubmit
if (isset($_POST['kktp'])) {
    $coba = mysql_query("UPDATE rb_jadwal_pelajaran SET kktp='{$_POST['kktp']}' WHERE kodejdwl='{$_POST['kodejdwl']}'");

    // Redirect setelah query dijalankan
    echo "<script>history.back();</script>";
}
?>



        </div><!-- /.box-body -->
    </div>
</div>

<div class="col-xs-12">
    <div class="box">
        <div class="box-header">

            <?php
            // Ambil tahun akademik yang terbaru
            $tahun = mysql_query("SELECT * FROM rb_tahun_akademik ORDER BY id_tahun_akademik DESC");
            $tahun_terbaru = mysql_fetch_array($tahun); // Ambil tahun terbaru
            mysql_data_seek($tahun, 0); // Kembali ke awal data query untuk loop
            
            // Jika pengguna belum memilih tahun, gunakan tahun terbaru
            $tahun_dipilih = isset($_GET['tahun']) ? $_GET['tahun'] : $tahun_terbaru['id_tahun_akademik'];

            // Ambil nama tahun akademik yang dipilih
            $nama_tahun_dipilih = '';
            while ($k = mysql_fetch_array($tahun)) {
                if ($tahun_dipilih == $k['id_tahun_akademik']) {
                    $nama_tahun_dipilih = $k['nama_tahun'];
                }
            }
            mysql_data_seek($tahun, 0); // Kembali ke awal untuk loop dropdown
            ?>

            <!-- Menampilkan form dan h3 -->
            <h3 class="box-title">
                Jadwal Piket anda
            </h3>

            <form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
                <!-- Tambahkan hidden input untuk menyimpan parameter view -->
                <input type="hidden" name="view" value="jadwalguru">
                <select name='tahun' style='padding:4px' onchange='this.form.submit()'>
                    <option value=''>- Pilih Tahun Akademik -</option>
                    <?php
                    while ($k = mysql_fetch_array($tahun)) {
                        $selected = ($tahun_dipilih == $k['id_tahun_akademik']) ? 'selected' : '';
                        echo "<option value='{$k['id_tahun_akademik']}' $selected>{$k['nama_tahun']}</option>";
                    }
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
                            <th>Nip</th>
                            <th>Hari</th>
                            <th>Guru</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        $tampil = mysql_query("SELECT * FROM rb_jadwal_guru_piket a JOIN rb_guru b ON a.nip=b.nip WHERE a.tanggal = CURDATE() AND a.nip = '$_SESSION[id]'");


                        $no = 1;
                        if (mysql_num_rows($tampil) > 0) { // Memeriksa apakah ada data
                            while ($r = mysql_fetch_array($tampil)) {
                                echo "<tr><td>$no</td>
                                <td>$r[nip]</td>
                                <td>$r[hari]</td>
                                <td>$r[nama_guru]</td>
                                <td>" . tgl_indo($r['tanggal']) . "</td>";
                                echo "</tr>";
                                $no++;
                            }
                        } else {
                            echo "<tr><td colspan='6' style='text-align:center;'>Tidak ada data</td></tr>"; // Menampilkan pesan jika tidak ada data
                        }
                        ?>
                    <tbody>
                </table>
            </div>
        </div><!-- /.box-body -->

    </div>
</div>

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
<script>

    function submitFormWithAlert(selectElement) {
        const selectedValue = selectElement.value;
        if (selectedValue) {
            const confirmSubmit = confirm(`Apakah Anda yakin ingin memberikan nilai ${selectedValue}?`);
            if (confirmSubmit) {
                document.getElementById('kktpForm').submit();
            }
        }
    }
    </script>