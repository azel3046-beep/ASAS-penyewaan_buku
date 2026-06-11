<?php
include '../koneksi.php';
session_start();

// Proteksi halaman: Hanya Admin yang bisa memproses pengembalian buku fisik
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login/loginadmin.php");
    exit;
}

// Pastikan parameter yang dibutuhkan ada di URL
if (isset($_GET['id']) && isset($_GET['id_buku']) && isset($_GET['tgl_harus'])) {
    $id_peminjaman = $_GET['id'];
    $id_buku = $_GET['id_buku'];
    $tgl_kembali_seharusnya = $_GET['tgl_harus'];
    $tgl_kembali_aktual = date('Y-m-d'); // Tanggal hari ini saat buku dikembalikan

    // 1. Update status peminjaman menjadi 'dikembalikan' dan isi tanggal kembali aktual
    $query_update = "UPDATE peminjaman SET 
                     tgl_kembali_aktual = '$tgl_kembali_aktual', 
                     status_buku = 'dikembalikan' 
                     WHERE id_peminjaman = '$id_peminjaman'";

    if (mysqli_query($koneksi, $query_update)) {
        
        // 2. Kembalikan stok buku (+1) karena buku fisik sudah kembali ke perpustakaan
        mysqli_query($koneksi, "UPDATE buku SET stok = stok + 1 WHERE id_buku = '$id_buku'");

        // 3. LOGIKA CEK KETERLAMBATAN (DENDA)
        // Mengubah string tanggal menjadi format timestamp untuk dibandingkan
        $time_seharusnya = strtotime($tgl_kembali_seharusnya);
        $time_aktual = strtotime($tgl_kembali_aktual);

        if ($time_aktual > $time_seharusnya) {
            // Jika tanggal kembali aktual melewati tenggat waktu
            $selisih_detik = $time_aktual - $time_seharusnya;
            $hari_terlambat = floor($selisih_detik / (60 * 60 * 24)); // Konversi detik ke hari

            // Masukkan data pelanggaran keterlambatan ke tabel denda
            $query_denda = "INSERT INTO denda (id_peminjaman, jumlah_hari_terlambat, status_denda) 
                            VALUES ('$id_peminjaman', '$hari_terlambat', 'belum_kembali')";
            mysqli_query($koneksi, $query_denda);

            echo "<script>
                    alert('Buku berhasil dikembalikan. MAAF, MEMBER TERLAMBAT MENGEMBALIKAN SELAMA $hari_terlambat HARI! Data denda telah dicatat.');
                    window.location = 'index.php';
                  </script>";
        } else {
            // Jika dikembalikan tepat waktu atau lebih cepat
            echo "<script>
                    alert('Buku berhasil dikembalikan tepat waktu! Transaksi sukses.');
                    window.location = 'index.php';
                  </script>";
        }
    } else {
        echo "<script>
                alert('Gagal memproses pengembalian buku!');
                window.location = 'index.php';
              </script>";
    }
} else {
    // Jika mencoba akses langsung tanpa data via URL, lempar balik ke index
    header("Location: index.php");
    exit;
}
?>