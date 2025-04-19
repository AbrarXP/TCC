<?php

$judul = $_POST['judul'];
$catatan = $_POST['catatan'];

$url = "https://notes-be-970101336895.us-central1.run.app//add-note"; // URL backend
$data = [
    "title" => $judul,
    "content" => $catatan
];

// Konversi data ke JSON
$jsonData = json_encode($data);

// Inisialisasi cURL
$ch = curl_init($url);

// Set opsi cURL
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

// Eksekusi request dan ambil responsenya
$response = curl_exec($ch);

// Tutup koneksi cURL
curl_close($ch);

// var_dump($response);
//Cek error
if ($response == false) {
    header('location:index.php?msg=gagalTambah');
}else{
    header('location:index.php?msg=berhasilTambah');
}

?>
