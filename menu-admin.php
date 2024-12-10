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

  <!-- sidebar menu: : style can be found in sidebar.less -->
  <ul class="sidebar-menu">
    <li class="header" style='color:#fff; text-transform:uppercase; border-bottom:2px solid #00c0ef'>MENU <?php echo $level; ?></li>
    <li><a href="index.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>

    <li class="treeview <?php echo ($_GET['view'] == 'identitas' || $_GET['view'] == 'kurikulum') ? 'active' : ''; ?>">
      <a href="#"><i class="fa fa-th"></i> <span>Data Master</span><i class="fa fa-angle-left pull-right"></i></a>
      <ul class="treeview-menu">
        <li class="<?php echo ($_GET['view'] == 'identitas') ? 'active' : ''; ?>"><a href="index.php?view=identitas"><i class="fa fa-circle-o"></i> Data Identitas Sekolah</a></li>
        <li class="<?php echo ($_GET['view'] == 'kurikulum') ? 'active' : ''; ?>"><a href="index.php?view=kurikulum"><i class="fa fa-circle-o"></i> Data Kurikulum</a></li>
        <li class="<?php echo ($_GET['view'] == 'tahunakademik') ? 'active' : ''; ?>"><a href="index.php?view=tahunakademik"><i class="fa fa-circle-o"></i> Data Tahun Akademik</a></li>
        <li class="<?php echo ($_GET['view'] == 'angkatan') ? 'active' : ''; ?>"><a href="index.php?view=angkatan"><i class="fa fa-circle-o"></i> Data Tahun Angkatan</a></li>
        <li class="<?php echo ($_GET['view'] == 'gedung') ? 'active' : ''; ?>"><a href="index.php?view=gedung"><i class="fa fa-circle-o"></i> Data Gedung</a></li>
        <li class="<?php echo ($_GET['view'] == 'ruangan') ? 'active' : ''; ?>"><a href="index.php?view=ruangan"><i class="fa fa-circle-o"></i> Data Ruangan</a></li>
        <li class="<?php echo ($_GET['view'] == 'golongan') ? 'active' : ''; ?>"><a href="index.php?view=golongan"><i class="fa fa-circle-o"></i> Data Golongan</a></li>
        <li class="<?php echo ($_GET['view'] == 'ptk') ? 'active' : ''; ?>"><a href="index.php?view=ptk"><i class="fa fa-circle-o"></i> Data Jenis PTK</a></li>
        <li class="<?php echo ($_GET['view'] == 'jurusan') ? 'active' : ''; ?>"><a href="index.php?view=jurusan"><i class="fa fa-circle-o"></i> Data Jurusan</a></li>
        <li class="<?php echo ($_GET['view'] == 'kelas') ? 'active' : ''; ?>"><a href="index.php?view=kelas"><i class="fa fa-circle-o"></i> Data Kelas</a></li>
        <li class="<?php echo ($_GET['view'] == 'statuspegawai') ? 'active' : ''; ?>"><a href="index.php?view=statuspegawai"><i class="fa fa-circle-o"></i> Data Status Kepegawaian</a></li>
      </ul>
    </li>
    <li class="treeview <?php echo ($_GET['view'] == 'siswa' || $_GET['view'] == 'guru' || $_GET['view'] == 'wakilkepala' || $_GET['view'] == 'admin') ? 'active' : ''; ?>">
      <a href="#"><i class="fa fa-user"></i> <span>Data Pengguna</span><i class="fa fa-angle-left pull-right"></i></a>
      <ul class="treeview-menu">
        <li class="<?php echo ($_GET['view'] == 'siswa') ? 'active' : ''; ?>"><a href="index.php?view=siswa"><i class="fa fa-circle-o"></i> Data Siswa</a></li>
        <li class="<?php echo ($_GET['view'] == 'guru') ? 'active' : ''; ?>"><a href="index.php?view=guru"><i class="fa fa-circle-o"></i> Data Guru</a></li>
        <li class="<?php echo ($_GET['view'] == 'wakilkepala') ? 'active' : ''; ?>"><a href="index.php?view=wakilkepala"><i class="fa fa-circle-o"></i> Data Kepala Sekolah</a></li>
        <li class="<?php echo ($_GET['view'] == 'admin') ? 'active' : ''; ?>"><a href="index.php?view=admin"><i class="fa fa-circle-o"></i> Data Administrator</a></li>
      </ul>
    </li>

    <li class="treeview <?php echo ($_GET['view'] == 'datarating' || $_GET['view'] == 'kelompokmapel' || $_GET['view'] == 'matapelajaran' || $_GET['view'] == 'jadwalpelajaran' || $_GET['view'] == 'penilaiandiri' || $_GET['view'] == 'pertanyaanrefleksi' || $_GET['view'] == 'penilaianteman') ? 'active' : ''; ?>">
      <a href="#"><i class="fa fa-tag"></i> <span>Data Akademik</span><i class="fa fa-angle-left pull-right"></i></a>
      <ul class="treeview-menu">
        <li class="<?php echo ($_GET['view'] == 'datarating') ? 'active' : ''; ?>"><a href="index.php?view=datarating"><i class="fa fa-circle-o"></i> Data Rating</a></li>
        <li class="<?php echo ($_GET['view'] == 'kelompokmapel') ? 'active' : ''; ?>"><a href="index.php?view=kelompokmapel"><i class="fa fa-circle-o"></i> Data Kelompok Mapel</a></li>
        <li class="<?php echo ($_GET['view'] == 'matapelajaran') ? 'active' : ''; ?>"><a href="index.php?view=matapelajaran"><i class="fa fa-circle-o"></i> Data Mata Pelajaran</a></li>
        <li class="<?php echo ($_GET['view'] == 'jadwalpelajaran') ? 'active' : ''; ?>"><a href="index.php?view=jadwalpelajaran"><i class="fa fa-circle-o"></i> Data Jadwal Pelajaran</a></li>
        <li class="<?php echo ($_GET['view'] == 'penilaiandiri') ? 'active' : ''; ?>"><a href="index.php?view=penilaiandiri"><i class="fa fa-circle-o"></i> Data Penilaian Diri</a></li>
        <li class="<?php echo ($_GET['view'] == 'pertanyaanrefleksi') ? 'active' : ''; ?>"><a href="index.php?view=pertanyaanrefleksi"><i class="fa fa-circle-o"></i> Data Pertanyaan Refleksi</a></li>
        <li class="<?php echo ($_GET['view'] == 'penilaianteman') ? 'active' : ''; ?>"><a href="index.php?view=penilaianteman"><i class="fa fa-circle-o"></i> Data Penilaian Teman</a></li>
      </ul>
    </li>

    <!-- <li class="treeview">
      <a href="#"><i class="fa fa-send"></i> <span>SMS Gateway</span><i class="fa fa-angle-left pull-right"></i></a>
      <ul class="treeview-menu">
        <li><a href="index.php?view=sms"><i class="fa fa-circle-o"></i> Send SMS</a></li>
        <li><a href="index.php?view=broadcast"><i class="fa fa-circle-o"></i> Broadcast SMS</a></li>
        <li><a href="index.php?view=autoreply"><i class="fa fa-circle-o"></i> Autoreply</a></li>
        <li><a href="index.php?view=smstoweb"><i class="fa fa-circle-o"></i> Inbox SMS2WEB</a></li>
        <li><a href="index.php?view=outboxautoreply"><i class="fa fa-circle-o"></i> Outbox Autoreply</a></li>
      </ul>
    </li> -->

    <li class="treeview <?php echo ($_GET['view'] == 'absensiswa' || $_GET['view'] == 'rekapabsensiswa') ? 'active' : ''; ?>">
      <a href="#"><i class="fa fa-th-large"></i> <span>Data Absensi</span><i class="fa fa-angle-left pull-right"></i></a>
      <ul class="treeview-menu">
        <li class="<?php echo ($_GET['view'] == 'absensiswa') ? 'active' : ''; ?>"><a href="index.php?view=absensiswa"><i class="fa fa-circle-o"></i> Absensi Siswa</a></li>
        <li class="<?php echo ($_GET['view'] == 'rekapabsensiswa') ? 'active' : ''; ?>"><a href="index.php?view=rekapabsensiswa"><i class="fa fa-circle-o"></i> Rekap Absensi Siswa</a></li>
      </ul>
    </li>

    <!-- <li class="treeview">
      <a href="#"><i class="fa fa-list"></i> <span>PSB Online</span><i class="fa fa-angle-left pull-right"></i></a>
      <ul class="treeview-menu">
        <li><a href="index.php?view=psbmenu"><i class="fa fa-circle-o"></i> Data Menu</a></li>
        <li><a href="index.php?view=psbhalaman"><i class="fa fa-circle-o"></i> Data Halaman</a></li>
        <li><a href="index.php?view=psbaktivasi"><i class="fa fa-circle-o"></i> Kode Aktivasi</a></li>
        <li><a href="index.php?view=psbsma"><i class="fa fa-circle-o"></i> Data Pendaftar SMA</a></li>
        <li><a href="index.php?view=psbsmk"><i class="fa fa-circle-o"></i> Data Pendaftar SMK</a></li>
        <li><a href="index.php?view=psbsmp"><i class="fa fa-circle-o"></i> Data Pendaftar SMP</a></li>
      </ul>
    </li> -->
    <li><a href="index.php?view=jadwalgurupiket"><i class="fa fa-tags"></i><span>Jadwal Guru Piket</span></a></li>
    <!-- <li><a href="index.php?view=journalkbm"><i class="fa fa-tags"></i><span>Journal KBM</span></a></li> -->
    <!-- <li><a href="index.php?view=forum"><i class="fa fa-th-list"></i> <span>Forum Diskusi</span></a></li> -->
    <!-- <li><a href="index.php?view=soal"><i class="fa fa-users"></i><span>Quiz/Ujian Online</span></a></li> -->
    <li class="treeview <?php echo ($_GET['view'] == 'raportuts' || $_GET['view'] == 'raportcetakuts' || $_GET['view'] == 'capaianhasilbelajar' || $_GET['view'] == 'extrakulikuler' || $_GET['view'] == 'prestasi' || $_GET['view'] == 'raport' || $_GET['view'] == 'raportcetak') ? 'active' : ''; ?>">
      <a href="#"><i class="fa fa-calendar"></i> <span>Laporan Nilai Siswa</span><i class="fa fa-angle-left pull-right"></i></a>
      <ul class="treeview-menu">
        <li class="<?php echo ($_GET['view'] == 'raportuts') ? 'active' : ''; ?>"><a href="index.php?view=raportuts"><i class="fa fa-circle-o"></i> Data Nilai STS</a></li>
        <li class="<?php echo ($_GET['view'] == 'raportcetakuts') ? 'active' : ''; ?>"><a href="index.php?view=raportcetakuts"><i class="fa fa-circle-o"></i> Cetak Raport STS</a></li>
        <li class="<?php echo ($_GET['view'] == 'capaianhasilbelajar') ? 'active' : ''; ?>"><a href="index.php?view=capaianhasilbelajar"><i class="fa fa-circle-o"></i> Data Capaian Belajar</a></li>
        <li class="<?php echo ($_GET['view'] == 'extrakulikuler') ? 'active' : ''; ?>"><a href="index.php?view=extrakulikuler"><i class="fa fa-circle-o"></i> Data Ekstrakulikuler</a></li>
        <li class="<?php echo ($_GET['view'] == 'prestasi') ? 'active' : ''; ?>"><a href="index.php?view=prestasi"><i class="fa fa-circle-o"></i> Data Prestasi</a></li>
        <li class="<?php echo ($_GET['view'] == 'raport') ? 'active' : ''; ?>"><a href="index.php?view=raport"><i class="fa fa-circle-o"></i> Data Nilai Raport</a></li>
        <li class="<?php echo ($_GET['view'] == 'raportcetak') ? 'active' : ''; ?>"><a href="index.php?view=raportcetak"><i class="fa fa-circle-o"></i> Cetak Raport</a></li>
      </ul>
    </li>
    <li class="<?php echo ($_GET['view'] == 'dokumentasi') ? 'active' : ''; ?>"><a href="index.php?view=dokumentasi"><i class="fa fa-book"></i> <span>Documentation</span></a></li>
  </ul>
</section>