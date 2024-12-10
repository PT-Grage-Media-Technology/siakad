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
    <li><a href="index.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
    <li class="treeview" id="data-pengguna">
      <a href="#"><i class="fa fa-user"></i> <span>Data Pengguna</span><i class="fa fa-angle-left pull-right"></i></a>
      <ul class="treeview-menu">
        <li><a href="index.php?view=siswa"><i class="fa fa-circle-o"></i> Data Siswa</a></li>
        <li><a href="index.php?view=guru"><i class="fa fa-circle-o"></i> Data Guru</a></li>
        <li><a href="index.php?view=wakilkepala"><i class="fa fa-circle-o"></i> Data Kepala Sekolah</a></li>
      </ul>
    </li>
    <li class="treeview" id="data-master">
      <a href="#"><i class="fa fa-th"></i> <span>Data Master</span><i class="fa fa-angle-left pull-right"></i></a>
      <ul class="treeview-menu">
        <li><a href="index.php?view=kelas"><i class="fa fa-circle-o"></i> Data Kelas</a></li>
        <li><a href="index.php?view=matapelajaran"><i class="fa fa-circle-o"></i> Data Mata Pelajaran</a></li>
        <li><a href="index.php?view=jadwalpelajaran"><i class="fa fa-circle-o"></i> Data Jadwal Pelajaran</a></li>
      </ul>
    </li>
    <li class="treeview" id="data-absensi">
      <a href="#"><i class="fa fa-th-large"></i> <span>Data Absensi</span><i
          class="fa fa-angle-left pull-right"></i></a>
      <ul class="treeview-menu">
        <li><a href="index.php?view=rekapabsensiswa"><i class="fa fa-th-large"></i> <span>Rekap Absensi Siswa</span></a>
        </li>
        <li><a href="index.php?view=rekapguru"><i class="fa fa-address-book-o"></i> <span>Rekap Absensi Guru</span></a>
        </li>
        <li><a href="index.php?view=jadwalgurupiket"><i class="fa fa-tags"></i><span>Jadwal Guru Piket</span></a></li>
      </ul>
    </li>
    <li><a href="index.php?view=bahantugas"><i class="fa fa-file"></i><span>Bahan dan Tugas</span></a></li>
    <!-- <li><a href="index.php?view=soal"><i class="fa fa-users"></i><span>Quiz / Ujian Online</span></a></li> -->
    <!-- <li><a href="index.php?view=journalkbm"><i class="fa fa-tags"></i><span>Journal KBM</span></a></li> -->
    <!-- <li><a href="index.php?view=forum"><i class="fa fa-th-list"></i> <span>Forum Diskusi</span></a></li> -->
    <li><a href="index.php?view=raportcetak"><i class="fa fa-calendar"></i> <span>Laporan Nilai Siswa</span></a></li>
    <li><a href="index.php?view=dokumentasi"><i class="fa fa-book"></i> <span>Documentation</span></a></li>
  </ul>
</section>

<script>
  document.querySelectorAll('.treeview > a').forEach(item => {
    item.addEventListener('click', function(e) {
      e.preventDefault(); // Mencegah link default
      const parent = this.parentElement;

      // Tutup semua dropdown lainnya
      document.querySelectorAll('.treeview.active').forEach(treeview => {
        if (treeview !== parent) {
          treeview.classList.remove('active');
          treeview.querySelector('.treeview-menu').style.display = 'none'; // Menyembunyikan menu
        }
      });

      // Toggle dropdown yang diklik
      const menu = parent.querySelector('.treeview-menu');
      if (parent.classList.contains('active')) {
        parent.classList.remove('active');
        menu.style.display = 'none'; // Menyembunyikan menu
      } else {
        parent.classList.add('active');
        menu.style.display = 'block'; // Menampilkan menu
      }
    });
  });
</script>