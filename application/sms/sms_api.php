<?php
include "../../config/koneksi.php";
$code = $_GET['code'];
$op = $_GET['op'];
header('Content-Type: text/xml');
echo "<?xml version='1.0'?>";
echo "<data>";
if ($code == '19891989'){
	if ($op == 'get'){	
		$query = "SELECT * FROM rb_sms";
		$hasil = mysql_query($query);
		while ($data = mysql_fetch_array($hasil)){
			echo "<sms>";
			echo "<id>".$data['id_sms']."</id>";	
			echo "<nohp>".$data['nohp']."</nohp>";
			echo "<pesan>".$data['pesan']."</pesan>";
			echo "</sms>";	
		}
	}else if ($op == 'del'){
		$id = $_GET['id'];
		$query = "DELETE FROM rb_sms WHERE id_sms = '$id'";
		mysql_query($query);
	}
}
echo "</data>";
?>