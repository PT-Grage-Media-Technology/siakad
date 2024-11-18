<?php
 
 $jk = mysql_query("SELECT * FROM rb_jenis_kelamin");
 while ($a = mysql_fetch_array($jk)) {
   echo "<option value='$a[id_jenis_kelamin]'>$a[jenis_kelamin]</option>";
 }

?>