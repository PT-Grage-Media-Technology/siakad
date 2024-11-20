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
                    <tr><th width='140px' scope='row'>Guru</th>   
                   <td><select class='form-control' name='nip'> 
                                    <option value='0' selected>- Pilih Guru -</option>";
                                    $guru = mysql_query("SELECT * FROM rb_guru WHERE id_jenis_ptk != 6 ORDER BY nama_guru ASC");
                                    while ($a = mysql_fetch_array($guru)) {
                                        echo "<option value='$a[nip]'>$a[nama_guru]</option>";
                                    }
                                    echo "</select>
                    </td></tr>
                   
                 </select>
                      </td></tr>
                     
                      <tr>
                        <th scope='row'>Hari</th>
                        <td>
                            <select class='form-control' name='hari'>
                                <option value='Senin'" . ($hari_ini == 'Senin' ? ' selected' : '') . ">Senin</option>
                                <option value='Selasa'" . ($hari_ini == 'Selasa' ? ' selected' : '') . ">Selasa</option>
                                <option value='Rabu'" . ($hari_ini == 'Rabu' ? ' selected' : '') . ">Rabu</option>
                                <option value='Kamis'" . ($hari_ini == 'Kamis' ? ' selected' : '') . ">Kamis</option>
                                <option value='Jumat'" . ($hari_ini == 'Jumat' ? ' selected' : '') . ">Jumat</option>
                                <option value='Sabtu'" . ($hari_ini == 'Sabtu' ? ' selected' : '') . ">Sabtu</option>
                            </select>
                        </td>
                      </tr>
                    <tr><th scope='row'>Tanggal</th>  <td><input type='text' style='border-radius:0px; padding-left:12px' class='datepicker form-control' value='".date('d-m-Y')."' name='d' data-date-format='dd-mm-yyyy'></td></tr>
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