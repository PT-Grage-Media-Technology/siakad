<section class="sidebar">
  <!-- Sidebar user panel -->
  <div class="user-panel">
    <div class="pull-left image">
      <img src="<?php echo $foto; ?>" class="img-circle" alt="User Image">
    </div>
    <div class="pull-left info">
      <p><?php echo $nama; ?></p>
      <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
    </div>
  </div>

  <!-- Sidebar menu -->
  <ul class="sidebar-menu">
    <li class="header" style="color:#fff; text-transform:uppercase; border-bottom:2px solid #00c0ef">MENU
      <?php echo $level; ?>
    </li>
    
    <?php
    // Dashboard menu
    $activeDashboard = ($_GET['view'] == '' || $_GET['view'] == 'index') ? 'active' : '';
    if ($_SESSION['level'] == 'guru') {
      echo "<li class='$activeDashboard'><a href='index.php'><i class='fa fa-dashboard'></i> <span>Dashboard</span></a></li>";
    }

    // Menu Wali Kelas
    $tampil = mysql_query("SELECT * FROM rb_kelas ke JOIN rb_guru gu ON ke.nip=gu.nip WHERE ke.nip='$_SESSION[id]'");
    if (mysql_num_rows($tampil) > 0) {
      $tampil = mysql_fetch_array(mysql_query("SELECT * FROM rb_kelas ke JOIN rb_guru gu ON ke.nip=gu.nip WHERE ke.nip='$_SESSION[id]'"));
      $tahun = mysql_fetch_array(mysql_query("SELECT * FROM rb_tahun_akademik ORDER BY id_tahun_akademik DESC"));
      echo "<li class='treeview " . ($_GET['view'] == 'rekapabsensiswa' || $_GET['view'] == 'raportcetakuts'|| $_GET['view'] == 'extrakulikuler' || $_GET['view'] == 'prestasi' || $_GET['view'] == 'raport' || $_GET['view'] == 'raportcetak' ? 'active' : '') . "'>
      <a href='#'><i class='fa fa-user'></i> <span>Menu Wali Kelas</span><i class='fa fa-angle-left pull-right'></i></a>
      <ul class='treeview-menu'>
        <li><a href='index.php?view=rekapabsensiswa&tahun=$tahun[id_tahun_akademik]&kelas=$tampil[kode_kelas]'><i class='fa fa-th-large'></i> <span>Rekap Absensi
          Siswa</span></a></li>
          <li><a href='index.php?view=extrakulikuler&tahun=$tahun[id_tahun_akademik]&kelas=$tampil[kode_kelas]'><i class='fa fa-circle-o'></i> Data Ekstrakulikuler</a></li>
          <li><a href='index.php?view=prestasi&tahun=$tahun[id_tahun_akademik]&kelas=$tampil[kode_kelas]'><i class='fa fa-circle-o'></i> Data Prestasi</a></li>
          <li><a href='index.php?view=raport&tahun=$tahun[id_tahun_akademik]&kelas=$tampil[kode_kelas]'><i class='fa fa-circle-o'></i> Data Nilai Raport</a></li>
          <li><a href='index.php?view=raportcetak&tahun=$tahun[id_tahun_akademik]&id=$tampil[kode_kelas]'><i class='fa fa-circle-o'></i> Cetak Raport</a></li>
          </ul>
          </li>";
          // <li><a href='index.php?view=raportuts&tahun=$tahun[id_tahun_akademik]&kelas=$tampil[kode_kelas]'><i class='fa fa-circle-o'></i> Data Nilai STS</a></li>
          // <li><a href='index.php?view=raportcetakuts&tahun=$tahun[id_tahun_akademik]&id=$tampil[kode_kelas]'><i class='fa fa-circle-o'></i> Cetak Raport STS</a></li>
          // <li><a href='index.php?view=raportsas&tahun=$tahun[id_tahun_akademik]&kelas=$tampil[kode_kelas]'><i class='fa fa-circle-o'></i> Data Nilai SAS</a></li>
    }

    // Modul Mengajar
    echo "<li class='treeview" . ($_GET['view'] == 'jadwalguru' ? 'active' : '') . "'>
      <a href='#'><i class='fa fa-user'></i> <span>Modul Mengajar</span><i class='fa fa-angle-left pull-right'></i></a>
      <ul class='treeview-menu'>
        <li><a href='index.php?view=jadwalguru' class='" . ($_GET['view'] == 'jadwalguru' ? 'active' : '') . "'>Aktivitas Mengajar</a></li>";
        if ($_GET['view'] == 'journalguru') {
          $idjr = $_GET['id'];
        } else {
          $idjr = $_GET['jdwl'];
        }

        $mapel = mysql_fetch_array(mysql_query("SELECT * FROM rb_jadwal_pelajaran WHERE kodejdwl=$idjr"));
        if (
          // isset($_GET['act']) && $_GET['act'] === 'lihat' &&
          // isset($_GET['id']) &&
          isset($_GET['tahun']) &&
          isset($_GET['kode_kelas']) &&
          ($_GET['view'] == 'journalguru' || $_GET['view'] == 'soal' || $_GET['view'] == 'raportuts' || $_GET['view'] == 'raportsas' || $_GET['view'] == 'raport')
      ) {
        // $idjr = $mapel['kodejdwl'];
      
      echo "
      <li><a class=' ". ($_GET['view'] === 'raportuts' ? 'active' : '') ."' href='index.php?view=raportuts&act=listsiswa&jdwl={$idjr}&kd={$mapel[kode_pelajaran]}&kode_kelas={$mapel[kode_kelas]}&tahun={$_GET['tahun']}'>Nilai STS</a></li>
      <li><a class= '". ($_GET['view'] === 'raportsas' ? 'active' : '') ."' href='index.php?view=raportsas&act=listsiswa&jdwl={$idjr}&kd={$mapel[kode_pelajaran]}&kode_kelas={$mapel[kode_kelas]}&tahun={$_GET['tahun']}'>Nilai SAS</a></li>
      <li><a href='index.php?view=soal&act=listsoal&jdwl={$idjr}&kd={$mapel[kode_pelajaran]}&kode_kelas={$mapel[kode_kelas]}&tahun={$_GET['tahun']}'><span>Quiz/Ujian Online</span></a></li>

      ";
    } 
    echo"</ul>
    </li>";
    // <li><a href='index.php?view=raport&act=listsiswasikap&jdwl={$_GET['id']}&kd={$mapel[kode_pelajaran]}&id={$mapel[kode_kelas]}&tahun={$_GET['tahun']}'>Nilai Raport</a></li>
    

    // Menu Guru Piket
    $tampilPiket = mysql_query("SELECT * FROM rb_jadwal_guru_piket a JOIN rb_guru b ON a.nip=b.nip WHERE a.hari = '$hari_ini' AND a.nip = '$_SESSION[id]'");
    if (mysql_num_rows($tampilPiket) > 0) {
      $activePiket = ($_GET['view'] == 'absensiguru') ? 'active' : '';
      echo "<li class='treeview $activePiket'>
        <a href='#'><i class='fa fa-user'></i> <span>Menu Guru Piket</span><i class='fa fa-angle-left pull-right'></i></a>
        <ul class='treeview-menu'>
          <li><a href='index.php?view=absensiguru' class='" . ($_GET['view'] == 'absensiguru' ? 'active' : '') . "'>Absensi Guru</a></li>
        </ul>
      </li>";
    }

    // Documentation
    $activeDocumentation = ($_GET['view'] == 'dokumentasiguru') ? 'active' : '';
    echo "<li class='$activeDocumentation'><a href='index.php?view=dokumentasiguru'><i class='fa fa-book'></i> <span>Documentation</span></a></li>";
    ?>
  </ul>
</section>
