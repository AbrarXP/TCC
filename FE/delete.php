<?php

$id = $_GET['id'];
$url = "https://notes-be-970101336895.us-central1.run.app/delete-note/$id"; // URL backend


// Konfigurasi HTTP request
$options = [
    "http" => [
        "method" => "DELETE",
        "header" => "Content-Type: application/json\r\n"
    ]
];

$context = stream_context_create($options);
$response = file_get_contents($url, false, $context);

// Redirect setelah request selesai
header('location:index.php');



// Cek error
if ($response == false) {
    header('location:index.php?msg=gagalHapus');
}else{
    header('location:index.php?msg=berhasilHapus');
} 

?>
