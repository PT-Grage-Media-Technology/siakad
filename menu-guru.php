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
    <li class="header" style='color:#fff; text-transform:uppercase; border-bottom:2px solid #00c0ef'>MENU
      <?php echo $level; ?></li>
    <?php
    if ($_SESSION[level] == 'guru') {
      echo "<li><a href='index.php'><i class='fa fa-dashboard'></i> <span>Dashboard</span></a></li>";
    }
    ?>
    <li><a href=""><i class="glyphicon glyphicon-list-alt"></i><span>Rekap Absensi</span></a></li>

    <li><a href="https://siakad.demogmt.online/index.php?view=aktivitaspembelajaran"><i class="glyphicon glyphicon-align-justify"></i> <span>Aktivitas Pembelajaran</span></a></li>

    <li class="treeview">
      <a href="#" class="d-flex justify-content-between align-items-center" onclick="this.classList.toggle('fa-sort-asc')">
        <span>Modul Mengajar</span>
        <i class="fa fa-caret-down ml-10"></i>
      </a>

      <ul class="treeview-menu">
        <li>
          <a href="index.php?view=jadwalguru">
            <i class="fa fa-check"></i>
            <span>Aktivitas Mengajar</span>
          </a>
        </li>
        <li>
          <a href="https://siakad.demogmt.online/index.php?view=raportuts&act=listsiswa&jdwl=$_GET[id]&kd=$d[kode_pelajaran]&id=$d[kode_kelas]&tahun=$_GET[tahun]">
            <i class="glyphicon glyphicon-list-alt"></i>
            <span>Nilai UTS</span>
          </a>
        </li>
        <li>
          <a href="https://siakad.demogmt.online/index.php?view=raport&act=listsiswasikap&jdwl=$_GET[id]&kd=$d[kode_pelajaran]&id=$d[kode_kelas]&tahun=$_GET[tahun]">
            <i class="glyphicon glyphicon glyphicon-book"></i>
            <span>Nilai Raport</span>
          </a>
        </li>
        <li>
          <a href="https://siakad.demogmt.online/index.php?view=forum&act=list&jdwl=$_GET[id]&kd=$d[kodejdwl]&id=$d[kode_kelas]&kd=$d[kode_pelajaran]&tahun=$_GET[tahun]">
            <i class="fa fa-users"></i>
            <span>Forum Diskusi</span>
          </a>
        </li>
        <li>
          <a href="https://siakad.demogmt.online/index.php?view=soal&act=listsoalsiswa&jdwl=$_GET[id]&kd=$d[kodejdwl]&id=$d[kode_kelas]&kd=$d[kode_pelajaran]&tahun=$_GET[tahun]">
            <i class="fa fa-th-list"></i>
            <span>Quiz/Ujian Online</span>
          </a>
        </li>
      </ul>
    </li>







    <!-- <li><a href="index.php?view=absensiswa&act=detailabsenguru"><i class="fa fa-th-large"></i> <span>Absensi
          Siswa</span></a></li> -->
    <!-- <li><a href="index.php?view=bahantugas&act=listbahantugasguru"><i class="fa fa-file"></i><span>Bahan dan
          Tugas</span></a></li> -->
    <!-- <li><a href="index.php?view=soal&act=detailguru"><i class="fa fa-users"></i><span>Quiz / Ujian Online</span></a> -->
    <!-- </li> -->
    <!-- <li><a href="index.php?view=forum&act=detailguru"><i class="fa fa-th-list"></i> <span>Forum Diskusi</span></a></li> -->
    <!-- <li><a href="index.php?view=kompetensiguru"><i class="fa fa-tags"></i> <span>Kompetensi Dasar</span></a></li> -->
    <!-- <li><a href="index.php?view=journalguru"><i class="fa fa-list"></i> <span>Journal KBM</span></a></li> -->
    <!-- <li class="treeview">
      <a href="#"><i class="fa fa-calendar"></i> <span>Laporan Nilai Siswa</span><i
          class="fa fa-angle-left pull-right"></i></a>
      <ul class="treeview-menu">
        <li><a href="index.php?view=raportuts&act=detailguru"><i class="fa fa-circle-o"></i> Input Nilai UTS</a></li>
        <li><a href="index.php?view=raport&act=detailguru"><i class="fa fa-circle-o"></i> Input Nilai Raport</a></li>
      </ul>
    </li> -->
    <li><a href="index.php?view=dokumentasiguru"><i class="fa fa-book"></i> <span>Documentation</span></a></li>
  </ul>
</section>