<?php
	if (isset($_GET[cek])){
		$kode = anti_injection($_POST['kode']); 
		$cek = mysql_fetch_array(mysql_query("SELECT * FROM rb_psb_aktivasi where kode_pendaftaran='$kode'"));
		$total = mysql_num_rows(mysql_query("SELECT * FROM rb_psb_aktivasi where kode_pendaftaran='$kode'"));
		if ($cek[proses] == 1){
			echo "<script>window.alert('Maaf, Kode Aktivasi yang anda masukkan sudah terdaftar,..');
	                                  window.location=('index.mu')</script>";
		}elseif ($total < 1){
			echo "<script>window.alert('Maaf, Kode Aktivasi yang anda masukkan tidak ditemukan,..');
	                                  window.location=('index.mu')</script>";
		}else{
			echo "<script>document.location='$cek[status]-dek-$cek[kode_pendaftaran].mu';</script>";
		}
	}

$query = mysql_query("SELECT * FROM rb_halaman where id_halaman='1' AND status='psb'");
$row = mysql_fetch_array($query);
	echo "<div class='alert alert-success'>$row[judul]</div>
	      <p>".nl2br($row['isi_halaman'])."</p>";
