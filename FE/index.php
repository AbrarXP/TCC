

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

                    foreach($notes as $note){
                    ?>
                        <div class="m-2">
                            <h6 class="badge text-bg-primary ms-2">ID | <?=$note['id']?><h6>
                            <div class="card mt-2 mb-2 p-2 overflow-y-scroll text-center" style="width:auto; height:40vh; background-color:rgb(59, 59, 59)">
                                <p class="text-white fw-bold mt-4"><?=$note['title']?></p>
                                <hr style="color:rgb(255,255,255)">
                                <p class="text-white p-3"><?=$note['content']?></p>
                            </div>
                            <div class="d-flex">
                                <button onclick="toggle('<?=$note['id']?>', '<?=htmlspecialchars($note['title'], ENT_QUOTES)?>', '<?=htmlspecialchars($note['content'], ENT_QUOTES)?>')" 
                                    id="<?=$note['id']?>" 
                                    class="btn btn-outliend bg-warning text-white fw-bold">
                                    <i  class='bx bx-pencil'></i> / <i class='bx bx-plus-medical'></i>
                                </button>
                                <a class="btn btn-outliend bg-danger text-white fw-bold" href="delete.php?id=<?=$note['id']?>"><i class='bx bxs-trash' ></i></a>
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
                <form action="add.php" method="POST">
                    <div class="mb-3">
                        <label for="judulcatatan" class="form-label">Judul Catatan</label>
                        <input name="judul" type="textarea" placeholder="Minky momo" class="form-control" id="judulcatatan" aria-describedby="emailHelp">
                    </div>
                    <div class="mb-3">
                        <label for="message">Catatan</label>
                        <textarea class="form-control" placeholder="Suatu hari.." id="message" name="catatan" rows="15" cols="30" style="resize:vertical"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
                </div>
            </div>
        </div>

        <div id="editCard" class=" text-white card p-4" style="width: 20vw; height: 90vh; background-color:rgba(244, 224, 0, 0.21); display:none">
            <p class="fw-bold fs-5 text-center"><i class='bx bx-pencil' ></i> Edit Catatan</p>
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
                        <textarea class="form-control"  id="textfieldCatatanEdit" name="catatan" rows="15" cols="30" style="resize:vertical"></textarea>
                    </div>
                    <button type="submit" class="btn btn-danger">Submit</button>
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
