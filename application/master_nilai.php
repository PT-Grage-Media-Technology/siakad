<div class="col-xs-12">
  <div class="box">
    <div class="box-header">
      <h3 class="box-title">Data Nilai </h3>
      <?php if ($_SESSION['level'] != 'kepala') { ?>
        <a class='pull-right btn btn-primary btn-sm' href='index.php?view=ruangan&act=tambah'>Tambahkan Data</a>
      <?php } ?>
    </div><!-- /.box-header -->
    <div class="box-body">
      <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
        <p><input type="text"> = <input type="text"> - <input type="text"></p>
        <p>
          <input type="text" class="form-control d-inline" placeholder="Nilai Huruf" style="width: 30px;"> = <input type="text" class="form-control d-inline" style="width: 150px;"> - <input type="text" class="form-control d-inline" style="width: 150px;">
        </p>

      </div><!-- /.table-responsive -->
    </div><!-- /.box-body -->
  </div><!-- /.box -->
</div>