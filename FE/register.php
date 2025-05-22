<?php
session_start();
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["btn-register"])) {
    // $url = "http://localhost:5000/auth/register";
    $url = "https://notes-be-970101336895.us-central1.run.app";

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
            header("Location: login.php?msg=$pesan");
            exit();
        } else {
            $error = $result['msg'] ?? "Login gagal dengan status $httpCode.";
        }
    }

    // Tutup koneksi cURL
    curl_close($ch);
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
                <a href="login.php" style="color: #0d6efd; text-decoration: underline;">Login</a>
            </p>
        </div>
    </div>
</div>


<script>
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