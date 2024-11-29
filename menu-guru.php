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
      <?php echo $level; ?>
    </li>
    <?php
    if ($_SESSION[level] == 'guru') {
      echo "<li><a href='index.php'><i class='fa fa-dashboard'></i> <span>Dashboard</span></a></li>";
    }
    ?>
    <!-- <li><a href=""><i class="fa fa-calendar-check-o" aria-hidden="true"></i><span>Rekap Absensi</span></a></li> -->

    <!-- <li><a href="https://siakad.demogmt.online/index.php?view=aktivitaspembelajaran"><i class="glyphicon glyphicon-align-justify"></i> <span>Aktivitas Pembelajaran</span></a></li> -->

    <?php

    // echo $hari_ini;
    $tampil = mysql_query("SELECT * FROM rb_kelas ke JOIN rb_guru gu ON ke.nip=gu.nip WHERE ke.nip='$_SESSION[id]'");

    if (mysql_num_rows($tampil) > 0) {
      echo "<li class='treeview'>
        <a href='#'><i class='fa fa-user'></i> <span>Menu Wali Kelas</span><i class='fa fa-angle-left pull-right'></i></a>
        <ul class='treeview-menu'>
          <li><a href='index.php?view=absensiguru'></i> Absensi Guru</a></li>
        </ul>
      </li>";
    } else {
    }
    ?>

    <li class="treeview">
      <a href="#"><i class="fa fa-user"></i> <span>Modul Mengajar</span><i class="fa fa-angle-left pull-right"></i></a>
      <ul class="treeview-menu">
        <li><a href="index.php?view=jadwalguru"></i> Aktivitas Mengajar</a></li>
        <?php echo "<li><a href='index.php?view=raportuts&act=listsiswa&jdwl=$_GET[id]&kd=$d[kode_pelajaran]&id=$d[kode_kelas]&tahun=$_GET[tahun]'>Nilai UTS</a></li>" ?>
        <!-- <li><a href="index.php?view=raportuts&act=listsiswa&jdwl=$_GET[id]&kd=$d[kode_pelajaran]&id=$d[kode_kelas]&tahun=$_GET[tahun]">Nilai UTS</a></li> -->
        <li><a
            href="index.php?view=raport&act=listsiswasikap&jdwl=$_GET[id]&kd=$d[kode_pelajaran]&id=$d[kode_kelas]&tahun=$_GET[tahun]">Nilai
            Raport</a></li>
        <li><a
            href="index.php?view=forum&act=list&jdwl=$_GET[id]&kd=$d[kodejdwl]&id=$d[kode_kelas]&kd=$d[kode_pelajaran]&tahun=$_GET[tahun]">Forum
            Diskusi</a></li>
        <li><a
            href="index.php?view=soal&act=listsoalsiswa&jdwl=$_GET[id]&kd=$d[kodejdwl]&id=$d[kode_kelas]&kd=$d[kode_pelajaran]&tahun=$_GET[tahun]">Quiz/Ujian
            Online</a></li>
      </ul>
    </li>

    <?php

    // echo $hari_ini;
    $tampil = mysql_query("SELECT * FROM rb_jadwal_guru_piket a JOIN rb_guru b ON a.nip=b.nip WHERE a.hari = '$hari_ini' AND a.nip = '$_SESSION[id]'");

    if (mysql_num_rows($tampil) > 0) {
      echo "<li class='treeview'>
      <a href='#'><i class='fa fa-user'></i> <span>Menu Guru Piket</span><i class='fa fa-angle-left pull-right'></i></a>
      <ul class='treeview-menu'>
        <li><a href='index.php?view=absensiguru'></i> Absensi Guru</a></li>
      </ul>
    </li>";
    } else {
    }
    ?>

    <li><a href="index.php?view=dokumentasiguru"><i class="fa fa-book"></i> <span>Documentation</span></a></li>
  </ul>
</section>