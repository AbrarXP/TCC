<?php

session_start();

// $CurrentActiveUrl = "http://localhost:5000";
$CurrentActiveUrl = "https://notes-be-970101336895.us-central1.run.app";

function add_note(String $baseUrl)
{
    $judul = $_POST['judul'];
    $catatan = $_POST['catatan'];

    $url = "$baseUrl/add-note"; // URL backend
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
        "Content-Type: application/json",
        'Authorization: Bearer ' . $_SESSION['accessToken'],
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

function edit_note(String $baseUrl){

    $id = $_POST['IdCatatan']; // ID catatan
    $judul = $_POST['judul']; // Judul catatan
    $catatan = $_POST['catatan']; // Isi catatan

    $url = "$baseUrl/edit-note/$id";

    $data = json_encode([
        "title" => $judul,
        "content" => $catatan
    ]);

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer " . $_SESSION['accessToken']
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($response === false) {
        $error = "Gagal update: $curlError";
        header("Location: index.php?msg=" . urlencode($error));
    } elseif ($httpCode !== 200) {
        $error = "Gagal update. Kode HTTP: $httpCode";
        header("Location: index.php?msg=" . urlencode($error));
    } else {
        header("Location: index.php?msg=berhasilUpdate");
    }
    exit;
}

function delete_note(String $baseUrl){
    $id = $_POST['id'];
    $url = "$baseUrl/delete-note/$id"; // URL backend

    $ch = curl_init($url);

   // Set opsi cURL untuk metode DELETE dan header
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer " . $_SESSION['accessToken']
    ]);

    // Setting curl
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    $error = curl_error($ch);
    curl_close($ch);

     // Cek apakah terjadi error
    if ($response === false) {
        $error = "Gagal menghubungi server: $curlError";
        header("Location: index.php?msg=" . urlencode($error));
    } else if ($httpCode !== 200) {
        $error = "Gagal menghapus. Kode HTTP: $httpCode";
        header("Location: index.php?msg=" . urlencode($error));
    } else {
        $msg = "berhasilHapus";
        header("Location: index.php?msg=$msg");
    }
    exit;
}

function logout(){
    // Hapus semua session
    $_SESSION = []; // Kosongkan array session
    session_unset(); // Hapus semua variabel session
    session_destroy(); // Hancurkan session

    // Redirect ke halaman login
    header("Location: index.php?msg=SuccessfullyLoggedOut");
}

if (isset($_POST['submit-add-note'])) {
    add_note($CurrentActiveUrl);
}

if (isset($_POST['submit-edit-note'])) {
    edit_note($CurrentActiveUrl);
}

if (isset($_POST['submit-delete-note'])) {
    delete_note($CurrentActiveUrl);
}

if (isset($_POST['logout-btn'])) {
    logout();
}


function HalamanNote(){
    ?>
    <div class="row justify-content-center" style="height: 100vh;">
            <form method= "POST" action="">
                <button type="submit" name="logout-btn" class = "btn btn-danger text-white fw-bold">Log out</button>
            </form>
            <div class=" col col-9 d-flex justify-content-center align-items-center">
                <div class=" text-white card p-4" style="width: 70vw; height: 90vh; background-color:rgb(43, 43, 43)">
                    <p class="fw-bold fs-5 text-center"><i class='bx bxs-time'></i> Semua Catatan</p>
                    <div class="overflow-y-scroll">
                        <?php
                        $url = "https://notes-be-970101336895.us-central1.run.app/notes"; // URL backend
                        // $url = "http://localhost:5000/notes";

                        // Inisialisasi cURL
                        $ch = curl_init($url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, [
                            'Authorization: Bearer ' . $_SESSION['accessToken'],
                        ]);

                        $response = curl_exec($ch);
                        if ($response === false) {
                            echo "Gagal mengambil data notes: " . curl_error($ch);
                            curl_close($ch);
                        }

                        curl_close($ch);

                        $data = json_decode($response, true);
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

                            <form action="index.php" method="POST">
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
    <?php
}

function HalamanLogin(){

    $error = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["btn-login"])) {
        // $url = "http://localhost:5000/auth/login";
        $url = "https://notes-be-970101336895.us-central1.run.app/auth/login";

        $data = json_encode([
            "username" => $_POST['username'],
            "password" => $_POST['password']
        ]);

        // Inisialisasi cURL
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data)
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        // Eksekusi dan ambil response
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Cek error cURL
        if ($response === false) {
            $error = "Gagal login: " . curl_error($ch);
        } else {
            $result = json_decode($response, true);

            // Cek jika login berhasil
            if ($httpCode === 200 && isset($result['accessToken'])) {
                $_SESSION['accessToken'] = $result['accessToken'];
                header("Location: index.php");
            } else {
                $error = $result['msg'] ?? "Login gagal dengan status $httpCode.";
            }
        }

        // Tutup koneksi cURL
        curl_close($ch);
    }
    ?>
    <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert"
            style="position: sticky; top: 10; z-index: 1050;">
            <?php echo $error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-center align-items-center" style="height: 100vh;">
        <div style="width: 20vw; padding: 30px; background-color: rgb(42, 42, 42); border-radius: 10px;" class="col-12">
            <div class="text-center">
                <h2 class="text-white">Login</h2>
            </div>
            <form method="POST" action="">
                <!-- Username -->
                <label for="usernameInput" class="form-label text-white">Username</label>
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input id="usernameInput" class="form-control" type="text" name="username" required>
                </div>

                <!-- Password -->
                <label for="passwordInput" class="form-label text-white">Password</label>
                <div class="input-group mb-3">
                    <span class="input-group-text" onclick="togglePassword()" style="cursor: pointer;">
                        <i id="toggleIcon" class="bi bi-eye"></i>
                    </span>
                    <input id="passwordInput" class="form-control" type="password" name="password" required>
                </div>

                <!-- Tombol login -->
                <div class="d-flex justify-content-center">
                    <button class="btn btn-primary" type="submit" name="btn-login">Login</button>
                </div>
            </form>
            <br>
            <hr>
            <div class="text-center">
                <p class="text-white">
                    Belum punya akun? 
                    <a href="index.php?mode=register" style="color: #0d6efd; text-decoration: underline;">Register</a>
                </p>
            </div>
        </div>
    </div>
    <?php
}

function HalamanRegister(){
    $error = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["btn-register"])) {
        // $url = "http://localhost:5000/auth/register";
        $url = "https://notes-be-970101336895.us-central1.run.app/auth/register";

        $data = json_encode([
            "username" => $_POST['username'],
            "email" => $_POST['email'],
            "password" => $_POST['password']
        ]);

        // Inisialisasi cURL
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data)
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        // Eksekusi dan ambil response
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Cek error cURL
        if ($response === false) {
            $error = "Gagal login: " . curl_error($ch);
        } else {
            $result = json_decode($response, true);

            // Cek jika login berhasil
            if ($httpCode === 201 && isset($result['msg'])) {
                $pesan = $result['msg'];
                header("Location: index.php?msg=$pesan");
            } else {
                $error = $result['msg'] ?? "Login gagal dengan status $httpCode.";
            }
        }

        // Tutup koneksi cURL
        curl_close($ch);
    }
    ?>
        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert"
                style="position: sticky; top: 10; z-index: 1050;">
                <?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <div class="d-flex justify-content-center align-items-center" style="height: 100vh;">
        <div style="width: 20vw; padding: 30px; background-color: rgb(42, 42, 42); border-radius: 10px;" class="col-12">
            <div class="text-center">
                <h2 class="text-white">Register</h2>
            </div>
            <form method="POST" action="">
                <!-- Username -->
                <label for="usernameInput" class="form-label text-white">Username</label>
                <div class="input-group mb-1">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input id="usernameInput" class="form-control" type="text" name="username" required>
                </div>

                <!-- email -->
                <label for="EmailInput" class="form-label text-white">Email</label>
                <div class="input-group mb-1">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input id="EmailInput" class="form-control" type="email" name="email" required>
                </div>

                <!-- Password -->
                <label for="passwordInput" class="form-label text-white">Password</label>
                <div class="input-group mb-1">
                    <span class="input-group-text" onclick="togglePassword()" style="cursor: pointer;">
                        <i id="toggleIcon" class="bi bi-eye"></i>
                    </span>
                    <input id="passwordInput" class="form-control" type="password" name="password" required>
                </div>

                <!-- Tombol login -->
                <div class="d-flex justify-content-center">
                    <button class="btn btn-primary" type="submit" name="btn-register">Register</button>
                </div>
            </form>
            <br>
            <hr>
            <div class="text-center">
                <p class="text-white">
                    Sudah punya akun? 
                    <a href="index.php?mode=login" style="color: #0d6efd; text-decoration: underline;">Login</a>
                </p>
            </div>
        </div>
    </div>
    <?php
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
    
    <link href="style.css" rel="stylesheet">
    <title>Catatan</title>
</head>

<body style="background-color: rgb(31, 31, 31)">

    <?php
        if(!isset($_SESSION['accessToken'])){
            $mode = $_GET['mode'] ?? 'login';
            if($mode === 'login'){
                HalamanLogin(); 
            }else{
                HalamanRegister();
            }
        }else{
            HalamanNote();
        }
    ?>

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

        function togglePassword() {
            const passwordInput = document.getElementById('passwordInput');
            const toggleIcon = document.getElementById('toggleIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('bi-eye-slash');
                toggleIcon.classList.add('bi-eye');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('bi-eye');
                toggleIcon.classList.add('bi-eye-slash');
            }
        }
    </script>

    

</body>

</html>
