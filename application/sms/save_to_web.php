<?php
include "../../config/koneksi.php";
$code = $_GET['code'];
$op = $_GET['op'];

header('Content-Type: text/xml');
echo "<?xml version='1.0'?>";
echo "<data>";

// cek kesesuaian kode API
if ($code == '19891989'){
	if ($op == 'send'){
	    // baca data SMS
		$pesan = $_GET['pesan'];
		$notelp = $_GET['notelp'];
		$waktu = $_GET['timee'];
		$idmodem = $_GET['idmodem'];
		
		// menyimpan data SMS ke inbox di database hosting
		$query = "INSERT INTO rb_sms_inbox (pesan, nohp, waktu, modem) VALUES ('$pesan', '$notelp', '$waktu', '$idmodem')";
		mysql_query($query);

		$qr = mysql_query("SELECT * FROM rb_sms_autoreply where keyword='$pesan'");
		$ar = mysql_num_rows($qr);
		$r = mysql_fetch_array($qr);
		if ($ar >= 1){
			mysql_query("INSERT INTO rb_sms VALUES('','$notelp','$r[isi_pesan]')");
			mysql_query("INSERT INTO rb_sms_outbox VALUES('','$notelp','$r[isi_pesan]','".date('Y-m-d H:i:s')."')");
		}
	}
}
echo "</data>";
?>