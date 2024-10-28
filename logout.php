<?php
session_start();

echo "<script>
    if (confirm('Apakah Anda yakin akan logout?')) {
        // Jika dikonfirmasi, lanjutkan dengan session destroy
        " . session_destroy() . ";
        alert('Sukses Keluar dari sistem.');
        window.location = 'index.php';
    } else {
        // Jika batal, refresh halaman
        window.location.reload();
    }
</script>";
die();
?>
