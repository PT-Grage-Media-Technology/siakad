<?php 
$tes = mysql_query("SELECT * FROM rb_mata_pelajaran");
while ($r = mysql_fetch_array($tes)){
  
  var_dump($r) ;
}
?>