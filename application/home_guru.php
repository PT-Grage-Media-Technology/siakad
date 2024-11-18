<?php 
$tes = mysql_query("SELECT * FROM rb_mata_pelajaran WHERE nip='$_SESSION[id]'");
$no = 1;
while ($r = mysql_fetch_array($tes)){
  echo"<p>$no - $r[namamatapelajaran]</p?";
  var_dump($r) ;
}

// echo $_SESSION['id'];
echo "SELECT * FROM rb_mata_pelajaran WHERE nip='$_SESSION[id]'";
?>