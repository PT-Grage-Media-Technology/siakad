<?php
  session_start();
  
  echo "<script>
          if (confirm('Apakah anda yakin akan logout?')) {
            " . 
              "window.location.href = 'logout.php?action=logout';
            " . "
          } else {
            window.history.back();
          }
        </script>";
        
  if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    echo "<script>
            window.alert('Sukses Keluar dari sistem.');
            window.location = 'index.php';
          </script>";
    die();
  }
?>
