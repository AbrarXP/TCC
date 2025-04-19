<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Ambil data dari form
    $id = $_POST['IdCatatan']; // ID catatan
    $judul = $_POST['judul']; // Judul catatan
    $catatan = $_POST['catatan']; // Isi catatan

    // URL API untuk update catatan
    $url = "https://notes-be-970101336895.us-central1.run.app/edit-note/$id"; 

    // Data yang akan dikirim ke API dalam format JSON
    $data = json_encode([
        "title" => $judul,
        "content" => $catatan
    ]);

    // Konfigurasi opsi HTTP request
    $options = [
        "http" => [
            "method"  => "PUT", // Gunakan metode PUT untuk update data
            "header"  => "Content-Type: application/json\r\n",
            "content" => $data
        ]
    ];

    // Buat stream context
    $context = stream_context_create($options);

    // Kirim request ke API
    $response = file_get_contents($url, false, $context);

    // Cek apakah request berhasil
    if ($response === false) {
        die("Gagal memperbarui catatan!");
    }

    // Konversi response JSON ke array PHP
    $result = json_decode($response, true);

    // Redirect kembali ke halaman utama setelah update
    header("Location: index.php?msg=berhasilUpdate");
    exit();
}
?>
