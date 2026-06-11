<?php
include '../koneksi.php';
session_start();

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login/loginadmin.php");
    exit;
}

if (isset($_GET['id'])) {

    $id_buku = mysqli_real_escape_string($koneksi, $_GET['id']);

    // Ambil data buku
    $result = mysqli_query($koneksi, "SELECT * FROM buku WHERE id_buku = '$id_buku'");

    if (mysqli_num_rows($result) > 0) {

        $buku = mysqli_fetch_assoc($result);

        // Hapus data peminjaman yang terkait buku
        mysqli_query($koneksi, "DELETE FROM peminjaman WHERE id_buku = '$id_buku'");

        // Hapus data buku
        $hapusBuku = mysqli_query($koneksi, "DELETE FROM buku WHERE id_buku = '$id_buku'");

        if ($hapusBuku) {

            // Hapus gambar cover jika ada
            if (!empty($buku['foto_cover']) && file_exists('../gambar/cover/' . $buku['foto_cover'])) {
                unlink('../gambar/cover/' . $buku['foto_cover']);
            }

            echo "
            <script>
                alert('Buku berhasil dihapus!');
                document.location.href='katalog.php';
            </script>";
        } else {

            echo "
            <script>
                alert('Gagal menghapus buku!');
                document.location.href='katalog.php';
            </script>";
        }

    } else {

        echo "
        <script>
            alert('Data buku tidak ditemukan!');
            document.location.href='katalog.php';
        </script>";
    }
} else {

    header("Location: katalog.php");
    exit;
}
?>