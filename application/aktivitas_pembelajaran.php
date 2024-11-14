<?php
// Menggunakan mysqli untuk koneksi ke database
$koneksi = new mysqli("153.92.15.8", "u610515881_siakad", "Siakad@1", "u610515881_db_siakad");

// Cek koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Ambil tahun akademik terbaru (id_tahun_akademik paling besar)
$query_tahun = "SELECT id_tahun_akademik, nama_tahun FROM rb_tahun_akademik ORDER BY id_tahun_akademik DESC LIMIT 1";
$result_tahun = $koneksi->query($query_tahun);
$latest_year = $result_tahun->fetch_assoc();

// Jika tidak ada tahun akademik dipilih, set default ke tahun terbaru
$tahun_dipilih = isset($_GET['tahun']) ? $_GET['tahun'] : $latest_year['id_tahun_akademik'];
$nama_tahun = isset($_GET['tahun']) ? 
    $koneksi->query("SELECT nama_tahun FROM rb_tahun_akademik WHERE id_tahun_akademik = '$tahun_dipilih'")->fetch_assoc()['nama_tahun'] : 
    $latest_year['nama_tahun'];

echo "Aktivitas Pembelajaran Guru - $nama_tahun";
?>

<form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
    <input type="hidden" name="view" value="aktivitaspembelajaran">

    <!-- Filter Tanggal -->
    <select name='tanggal' style='padding:4px' onchange='this.form.submit()'>
        <?php
        $today = date('j'); // Mengambil tanggal hari ini
        $selectedTanggal = isset($_GET['tanggal']) ? $_GET['tanggal'] : $today; // Default ke tanggal hari ini jika kosong

        for ($i = 1; $i <= 31; $i++) {
            $selected = ($selectedTanggal == $i) ? 'selected' : '';
            echo "<option value='$i' $selected>$i</option>";
        }
        ?>
    </select>

    <!-- Filter Bulan -->
    <select name='bulan' style='padding:4px' onchange='this.form.submit()'>
        <?php
        $currentMonth = date('n'); // Mengambil bulan saat ini
        $selectedBulan = isset($_GET['bulan']) ? $_GET['bulan'] : $currentMonth; // Default ke bulan saat ini jika kosong
        $bulanNama = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        for ($i = 1; $i <= 12; $i++) {
            $selected = ($selectedBulan == $i) ? 'selected' : '';
            echo "<option value='$i' $selected>{$bulanNama[$i - 1]}</option>";
        }
        ?>
    </select>

    <select name='tahun' style='padding:4px' onchange='this.form.submit()'>
        <option value=''>- Pilih Tahun Akademik -</option>
        <?php
        $query_tahun = "SELECT * FROM rb_tahun_akademik ORDER BY id_tahun_akademik DESC";
        $result_tahun = $koneksi->query($query_tahun);
        while ($k = $result_tahun->fetch_assoc()) {
            $selected = ($tahun_dipilih == $k['id_tahun_akademik']) ? 'selected' : '';
            echo "<option value='$k[id_tahun_akademik]' $selected>$k[nama_tahun]</option>";
        }
        ?>
    </select>
</form>

<?php
if ($_SESSION['is_kurikulum']) {
    // Mengambil tanggal yang dipilih dari GET
    $tanggal_dipilih = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('d');
    $bulan_dipilih = isset($_GET['bulan']) ? $_GET['bulan'] : date('n');

    // Query untuk mengambil data
    $query = "SELECT jl.*, a.kode_kelas, b.nama_kelas, c.namamatapelajaran, c.kode_pelajaran, d.nama_guru,
              (SELECT kode_kehadiran 
               FROM rb_absensi_guru ag 
               WHERE ag.nip = jl.users 
               AND ag.tanggal = jl.tanggal 
               AND ag.jam_ke = jl.jam_ke  
               LIMIT 1) AS kode_kehadiran
              FROM rb_journal_list jl 
              JOIN rb_jadwal_pelajaran a ON jl.kodejdwl = a.kodejdwl
              JOIN rb_kelas b ON a.kode_kelas = b.kode_kelas 
              JOIN rb_mata_pelajaran c ON a.kode_pelajaran = c.kode_pelajaran 
              JOIN rb_guru d ON jl.users = d.nip
              WHERE DAY(jl.tanggal) = '$tanggal_dipilih' 
              AND MONTH(jl.tanggal) = '$bulan_dipilih'
              ORDER BY jl.waktu_input DESC";

    $result = $koneksi->query($query);
    $no = 1;
    while ($r = $result->fetch_assoc()) {
        echo "<tr>
                <td>$no</td>
                <td>$r[users]</td>
                <td>$r[nama_guru]</td>
                <td>$r[hari]</td>
                <td>" . tgl_indo($r['tanggal']) . "</td>
                <td>$r[jam_ke]</td>
                <td>$r[kode_kelas]</td>
                <td>$r[namamatapelajaran]</td>";

        $query_pemberitahuan = "SELECT * FROM rb_pemberitahuan_guru 
                                WHERE nip_guru='$r[users]' 
                                AND is_read=0 
                                AND kode_kelas='$r[kode_kelas]' 
                                AND kode_mapel='$r[kode_pelajaran]' 
                                AND id_tujuan_pembelajaran='$r[kodejdwl]' 
                                AND tanggal_absen='$r[tanggal]' 
                                AND jam_ke='$r[jam_ke]'";
        $result_pemberitahuan = $koneksi->query($query_pemberitahuan);
        $pe = $result_pemberitahuan->fetch_assoc();

        echo "<td>";
        if (isset($r['kode_kehadiran'])) {
            echo "$r[kode_kehadiran]";
        } else {
            if ($result_pemberitahuan->num_rows > 0) {
                echo "Sudah Kirim Pemberitahuan";
            } else {
                echo "
                <form method='POST' id='pemberitahuan' action='' onsubmit='return submitFormWithAlert()'>
                    <input type='hidden' name='users' value='$r[users]'>
                    <input type='hidden' name='kodejdwl' value='$r[kodejdwl]'>
                    <input type='hidden' name='jam_ke' value='$r[jam_ke]'>
                    <button class='btn btn-primary btn-xs' type='submit' name='peringatkan'>Peringatkan</button>
                </form>";
            }
        }
        echo "</td>";

        echo "<td>
                <center>
                    <a class='btn btn-warning btn-xs' href='index.php?view=journalguru&act=lihat&id=$r[kodejdwl]'>Detail Tujuan Pembelajaran Guru</a>
                    <a class='btn btn-primary btn-xs' href='index.php?view=absensiswa&act=tampilabsen&id=$r[kode_kelas]&kd=$r[kode_pelajaran]&idjr=$r[kodejdwl]&tgl=$r[tanggal]&jam=$r[jam_ke]'>Absensi</a>
                </center>
              </td>
            </tr>";
        $no++;

        if (isset($_POST['peringatkan']) && $_POST['users'] == $r['users'] &&
            $_POST['kodejdwl'] == $r['kodejdwl'] && $_POST['jam_ke'] == $r['jam_ke']) {
            // Mendapatkan NIP pengguna
            $nip = $koneksi->real_escape_string($_POST['users']); // Menyantisisasi input

            $pesan = 'Segera Absen Muridnya';
            $tanggal = date('Y-m-d H:i:s');

            $insertResult = $koneksi->query("INSERT INTO rb_pemberitahuan_guru (nip_guru, pesan, is_read, kode_kelas, kode_pelajaran, id_tujuan_pembelajaran, tanggal_absen, jam_ke, tanggal_input)
                                             VALUES ('$nip', '$pesan', 0, '$r[kode_kelas]', '$r[kode_pelajaran]', '$r[kodejdwl]', '$r[tanggal]', '$r[jam_ke]', '$tanggal')");

            if ($insertResult) {
                echo "<script>document.location='index.php?view=aktivitaspembelajaran';</script>";
            }
        }
    }
}

$koneksi->close();
?>
