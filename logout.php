<?php
session_start();


echo "<script>
        function confirmLogout() {
          if (confirm('Apakah anda yakin akan logout?')) {
            window.location.href = 'logout.php?action=logout';
          } else {
            window.history.back();
          }
        }
      </script>";

echo "<script>confirmLogout();</script>";

// Logika PHP untuk menangani logout saat action dipanggil
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    echo "<script>
            alert('Sukses Keluar dari sistem.');
            window.location = 'index.php';
          </script>";
    die();
}
?>
