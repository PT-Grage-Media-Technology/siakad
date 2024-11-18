<?php 
$tes = mysql_query("SELECT * FROM rb_mata_pelajaran WHERE nip='$_SESSION[id]'");
while ($r = mysql_fetch_array($tes)){
  
  var_dump($r) ;
}

echo $_SESSION['id'];
?>