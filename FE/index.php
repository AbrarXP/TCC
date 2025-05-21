<?php

function add_note()
{
    $judul = $_POST['judul'];
    $catatan = $_POST['catatan'];

    $url = "https://notes-be-970101336895.us-central1.run.app/add-note"; // URL backend
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
    } else {
        header('location:index.php?msg=berhasilTambah');
    }
}

function edit_note(){

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
}

function delete_note(){
    $id = $_POST['id'];
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
}

if (isset($_POST['submit-add-note'])) {
    add_note();
}

if (isset($_POST['submit-edit-note'])) {
    edit_note();
}

if (isset($_POST['submit-delete-note'])) {
    delete_note();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="style.css" rel="stylesheet">
    <title>Catatan</title>
</head>

<body style="background-color: rgb(31, 31, 31)">

    <div class="row justify-content-center" style="height: 100vh;">
        <div class=" col col-9 d-flex justify-content-center align-items-center">
            <div class=" text-white card p-4" style="width: 70vw; height: 90vh; background-color:rgb(43, 43, 43)">
                <p class="fw-bold fs-5 text-center"><i class='bx bxs-time'></i> Semua Catatan</p>
                <div class="overflow-y-scroll">
                    <?php
                    $url = "https://notes-be-970101336895.us-central1.run.app/notes"; // URL backend

                    // Ambil data dari API
                    $response = file_get_contents($url);

                    // Parse JSON ke array PHP
                    $data = json_decode($response, true);

                    // Simpan ke variabel
                    $notes = $data;

                    foreach ($notes as $note) {
                    ?>
                        <div class="m-2">
                            <h6 class="badge text-bg-primary ms-2">ID | <?= $note['id'] ?><h6>
                                    <div class="card mt-2 mb-2 p-2 overflow-y-scroll text-center" style="width:auto; height:40vh; background-color:rgb(59, 59, 59)">
                                        <p class="text-white fw-bold mt-4"><?= $note['title'] ?></p>
                                        <hr style="color:rgb(255,255,255)">
                                        <p class="text-white p-3"><?= $note['content'] ?></p>
                                    </div>
                                    <div class="d-flex">
                                        <button onclick="toggle('<?= $note['id'] ?>', '<?= htmlspecialchars($note['title'], ENT_QUOTES) ?>', '<?= htmlspecialchars($note['content'], ENT_QUOTES) ?>')"
                                            id="<?= $note['id'] ?>"
                                            class="btn btn-outliend bg-warning text-white fw-bold">
                                            <i class='bx bx-pencil'></i> / <i class='bx bx-plus-medical'></i>
                                        </button>
                                        <form action="index.php" method="POST">
                                            <input type="hidden" name="id" value="<?= $note['id'] ?>">
                                            <button type="submit" name="submit-delete-note" class="btn btn-danger text-white fw-bold">
                                                <i class='bx bxs-trash'></i>
                                            </button>
                                        </form>

                                    </div>
                        </div>
                        <hr>
                    <?php
                    }
                    ?>

                </div>
            </div>
        </div>

        <div class=" col  col-3 d-flex justify-content-center align-items-center">

            <div id="addCard" class=" text-white card p-4" style="width: 20vw; height: 90vh; background-color:rgba(98, 244, 0, 0.21)">
                <p class="fw-bold fs-5 text-center"><i class='bx bx-plus-medical'></i> Tambah Catatan</p>
                <div class="overflow-y-scroll">
                    <div class="m-2">
                        <form action="index.php" method="POST">
                            <div class="mb-3">
                                <label for="judulcatatan" class="form-label">Judul Catatan</label>
                                <input name="judul" type="textarea" placeholder="Minky momo" class="form-control" id="judulcatatan" aria-describedby="emailHelp">
                            </div>
                            <div class="mb-3">
                                <label for="message">Catatan</label>
                                <textarea class="form-control" placeholder="Suatu hari.." id="message" name="catatan" rows="15" cols="30" style="resize:vertical"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary" name="submit-add-note">Submit</button>
                        </form>
                    </div>
                </div>
            </div>

            <div id="editCard" class=" text-white card p-4" style="width: 20vw; height: 90vh; background-color:rgba(244, 224, 0, 0.21); display:none">
                <p class="fw-bold fs-5 text-center"><i class='bx bx-pencil'></i> Edit Catatan</p>
                <div class="overflow-y-scroll">
                    <div class="m-2">

                        <form action="edit.php" method="POST">
                            <input id="idCatatan" name="IdCatatan" type="hidden" class="form-control">
                            <div class="mb-3">
                                <label for="judulcatatan" class="form-label">Judul Catatan</label>
                                <input id="textfieldJudulEdit" name="judul" type="textarea" placeholder="Minky momo" class="form-control" id="judulcatatan" aria-describedby="emailHelp">
                            </div>
                            <div class="mb-3">
                                <label for="message">Catatan</label>
                                <textarea class="form-control" id="textfieldCatatanEdit" name="catatan" rows="15" cols="30" style="resize:vertical"></textarea>
                            </div>
                            <button type="submit" class="btn btn-danger" name="submit-edit-note">Submit</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        function toggle(id, title, content) {
            let editCard = document.getElementById("editCard");
            let addCard = document.getElementById("addCard");

            let btnEdit = document.getElementById(id);

            let textfieldjudul = document.getElementById("textfieldJudulEdit");
            let textfieldcatatan = document.getElementById("textfieldCatatanEdit");

            let idCatatan = document.getElementById("idCatatan");

            // Toggle visibilitas
            if (addCard.style.display == "block") {

                addCard.style.display = "none";
                editCard.style.display = "block";

                textfieldjudul.value = title;
                textfieldcatatan.value = content;
                idCatatan.value = id;

            } else {

                addCard.style.display = "block";
                editCard.style.display = "none";
            }
        }
    </script>

</body>

</html>
