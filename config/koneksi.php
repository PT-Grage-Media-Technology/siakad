<?php
date_default_timezone_set('Asia/Jakarta');
$server = "153.92.15.8";
$username = "u610515881_siakad";
$password = "Siakad@1";
$database = "u610515881_db_siakad";

mysql_connect($server,$username,$password);
mysql_select_db($database);

function anti_injection($data){
  $filter = mysql_real_escape_string(stripslashes(strip_tags(htmlspecialchars($data,ENT_QUOTES))));
  return $filter;
}

function average($arr){
   if (!is_array($arr)) return false;
   return array_sum($arr)/count($arr);
}

function cek_session_admin(){
	$level = $_SESSION[level];
	if ($level != 'superuser' AND $level != 'kepala' AND $_SESSION['is_penjamin_mutu'] != true){
		echo "<script>document.location='index.php';</script>";
	}
}

function cek_session_guru(){
	$level = $_SESSION[level];
	if ($level != 'guru' AND $level != 'superuser' AND $level != 'kepala'){
		echo "<script>document.location='index.php';</script>";
	}
}

function cek_session_siswa(){
	$level = $_SESSION[level];
	if ($level != 'siswa'){
		echo "<script>document.location='index.php';</script>";
	}
}

?>