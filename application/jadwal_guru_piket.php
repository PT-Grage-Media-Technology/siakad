<?php if ($_GET[act]==''){ ?>
            <div class="col-xs-12">  
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Jadwal Guru Piket</h3>
                  <a class='btn btn-primary pull-right' href='index.php?view=jadwalgurupiket&act=tambah' title='Tambah Jadwal'>Tambah Jadwal</a>
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
                        
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                  <?php
                   
                      $tampil = mysql_query("SELECT * FROM rb_jadwal_guru_piket a JOIN rb_guru b ON a.nip=b.nip");
                    
                   
                    $no = 1;
                    while($r=mysql_fetch_array($tampil)){
                    echo "<tr><td>$no</td>
                              <td>$r[nip]</td>
                              <td>$r[hari]</td>
                              <td>$r[nama_guru]</td>
                              <td>$r[tanggal]</td>";
                                echo "<td style='width:80px !important'><center>
                                        <a class='btn btn-success btn-xs' title='Lihat Journal' href='index.php?view=journalkbm&act=lihat&id=$r[kodejdwl]'><span class='glyphicon glyphicon-search'></span> Edit</a>
                                        <a class='btn btn-success btn-xs' title='Lihat Journal' href='index.php?view=journalkbm&act=lihat&id=$r[kodejdwl]'><span class='glyphicon glyphicon-search'></span> Delete</a>
                                      </center></td>";
                            echo "</tr>";
                      $no++;
                      }
                  ?>
                    <tbody>
                  </table>
                  </div>
                </div><!-- /.box-body -->

                </div>
            </div>
            <?php 
}elseif($_GET[act]=='tambah'){
    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Tambah Jadwal</h3>
                </div>
              <div class='box-body'>
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                  <input type='hidden' name='jdwl' value='$_GET[jdwl]'>
                    <tr><th width='140px' scope='row'>Kelas</th>   <td><select class='form-control' name='a'>"; 
                                                $kelas = mysql_query("SELECT * FROM rb_kelas");
                                                while($a = mysql_fetch_array($kelas)){
                                                  if ($e[kode_kelas]==$a[kode_kelas]){
                                                    echo "<option value='$a[kode_kelas]' selected>$a[nama_kelas]</option>";
                                                  }
                                                }
                                                echo "</select>
                    </td></tr>
                    <tr><th scope='row'>Mata Pelajaran</th>   <td><select class='form-control' name='b'>"; 
                                                $mapel = mysql_query("SELECT * FROM rb_mata_pelajaran");
                                                while($a = mysql_fetch_array($mapel)){
                                                  if ($e[kode_pelajaran]==$a[kode_pelajaran]){
                                                    echo "<option value='$a[kode_pelajaran]' selected>$a[namamatapelajaran]</option>";
                                                  }
                                                }
                                                echo "</select>
                    </td></tr>
                   
                    <tr><th scope='row'>Hari</th>  <td><input type='text' class='form-control' value='$hari_ini' name='c'></td></tr>
                    <tr><th scope='row'>Tanggal</th>  <td><input type='text' style='border-radius:0px; padding-left:12px' class='datepicker form-control' value='".date('d-m-Y')."' name='d' data-date-format='dd-mm-yyyy'></td></tr>
                    <tr><th scope='row'>Jam Ke</th>  <td><input type='number' class='form-control' value='$jam' name='e'></td></tr>
                    <tr><th scope='row'>Materi</th>  <td><textarea style='height:80px' class='form-control' name='f'></textarea></td></tr>
                    <tr><th scope='row'>Keterangan</th>  <td><textarea style='height:160px'  class='form-control' name='g'></textarea></td></tr>
                    </td></tr>
                  </tbody>
                  </table>
                </div>
              </div>
              <div class='box-footer'>
                    <button type='submit' name='tambah' class='btn btn-info'>Tambahkan</button>
                    <a href='index.php?view=journalguru'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
              </form>
            </div>";
}