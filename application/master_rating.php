<?php if ($_GET[act]==''){ ?> 
            <div class="col-xs-12">  
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Data Predikat / Grade Nilai </h3>
                  <?php if($_SESSION[level]!='kepala'){ ?>
                  <a class='pull-right btn btn-primary btn-sm' href='index.php?view=datarating&act=tambah'>Tambahkan Data</a>
                  <?php } ?>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <div class="table-responsive">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th style='width:40px'>No</th>
                        <th>Bintang</th>
                        <th>Kesan</th>
                        <?php if($_SESSION[level]!='kepala'){ ?>
                        <th style='width:70px'>Action</th>
                        <?php } ?>
                      </tr>
                    </thead>
                    <tbody>
                  <?php 
                    $tampil = mysql_query("SELECT * FROM rb_rating");
                    $no = 1;
                    while($r=mysql_fetch_array($tampil)){
                    echo "<tr>
                            <td>$no</td>
                            <center><td>$r[bintang]</td>
                            <center><td>$r[kesan]</td></center>";
                              if($_SESSION[level]!='kepala'){
                        echo "<td><center>
                                <a class='btn btn-success btn-xs' title='Edit Data' href='index.php?view=datarating&act=edit&id=$r[id]'><span class='glyphicon glyphicon-edit'></span></a>
                                <a class='btn btn-danger btn-xs' title='Delete Data' href='index.php?view=datarating&hapus=$r[id]'><span class='glyphicon glyphicon-remove'></span></a>
                              </center></td>";
                              }
                            echo "</tr>";
                      $no++;
                      }
                      if (isset($_GET[hapus])){
                          mysql_query("DELETE FROM rb_rating where id='$_GET[hapus]'");
                          echo "<script>document.location='index.php?view=datarating';</script>";
                      }

                  ?>
                    </tbody>
                  </table>
                  </div>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div>
<?php 
}elseif($_GET[act]=='edit'){
    if (isset($_POST[update])){
        mysql_query("UPDATE rb_rating SET bintang = '$_POST[a]', kesan = '$_POST[b]' where id='$_POST[id]'");
      echo "<script>document.location='index.php?view=datarating';</script>";
    }
    $edit = mysql_query("SELECT * FROM rb_rating where id='$_GET[id]'");
    $s = mysql_fetch_array($edit);
    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Edit Data Predikat / Grade</h3>
                </div>
              <div class='box-body'>
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                    <input type='hidden' name='id' value='$s[id]'>
                    <tr><th width='120px' scope='row'>Bintang</th> <td><input type='text' class='form-control' name='a' value='$s[bintang]'> </td></tr>
                    <tr><th scope='row'>Kesan</th> <td><input type='text' class='form-control' name='b' value='$s[kesan]'> </td></tr>
                  </tbody>
                  </table>
                </div>
              </div>
              <div class='box-footer'>
                    <button type='submit' name='update' class='btn btn-info'>Update</button>
                    <a href='index.php?view=predikat'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
              </form>
            </div>";
}elseif($_GET[act]=='tambah'){
    if (isset($_POST[tambah])){
        mysql_query("INSERT INTO rb_rating VALUES('','$_POST[a]','$_POST[b]')");
        echo "<script>document.location='index.php?view=datarating';</script>";
    }

    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Tambah Data Predikat / Grade</h3>
                </div>
              <div class='box-body'>
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                   <tbody>
                    <tr><th width='120px' scope='row'>Bintang</th> <td><input type='text' class='form-control' name='a'> </td></tr>
                    <tr><th scope='row'>Kesan</th> <td><input type='text' class='form-control' name='b'> </td></tr>
                  </tbody>
                  </table>
                </div>
              </div>
              <div class='box-footer'>
                    <button type='submit' name='tambah' class='btn btn-info'>Tambahkan</button>
                    <a href='index.php?view=datarating'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
              </form>
            </div>";
}
?>

<style>
  .table-responsive {
    overflow-x: auto; /* Hanya aktifkan scroll horizontal jika diperlukan */
}

@media (min-width: 768px) {
    .table-responsive {
        overflow-x: visible; /* Nonaktifkan scroll horizontal di desktop */
    }
}
</style>