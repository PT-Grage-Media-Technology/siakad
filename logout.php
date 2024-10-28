<?php
session_start();

// Tangani logout saat URL parameter `action=logout` ada
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
  session_destroy();
  echo "<script>
            alert('Sukses Keluar dari sistem.');
            window.location = 'index.php';
          </script>";
  die();
}

// Script konfirmasi logout di JavaScript
echo "<script>
        if (confirm('Apakah anda yakin akan logout?')) {
            window.location.href = 'logout.php?action=logout';
        } else {
            window.history.back();
        }
      </script>";
?>