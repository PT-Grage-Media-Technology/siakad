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
    echo"tes";
}