<?php 

require_once("config.php");

if(isset($_POST['login'])){

    $Email = filter_input(INPUT_POST, 'Email', FILTER_VALIDATE_EMAIL);
    $Password = $_POST["Password"];

    $sql = "SELECT * FROM users WHERE Email=:Email";
    $stmt = $db->prepare($sql);
    
    // bind parameter ke query
    $params = array(
        ":Email" => $Email
    );

    $stmt->execute($params);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // jika user terdaftar
    if($user){
        // verifikasi password
        if(password_verify($Password, $user["Password"])){
            // buat Session
            session_start();
            $_SESSION["user"] = $user;
            // login sukses, alihkan ke halaman timeline
            header("Location: timeline.php");
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login Akun WBS</title>

    <link rel="stylesheet" href="css/bootstrap.min.css" />
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row">
        <div class="col-md-6">

        <p>&larr; <a href="index.php">Home</a>

        <h4>Masuk ke Widyakelana Back to School</h4>
        <p>Belum daftar? <a href="register.php">Daftar di sini</a></p>

        <form action="" method="POST">

            <div class="form-group">
                <label for="Email">Alamat email</label>
                <input class="form-control" type="email" name="Email" placeholder="email" />
            </div>


            <div class="form-group">
                <label for="Password">Password</label>
                <input class="form-control" type="password" name="Password" placeholder="Password" />
            </div>

            <input type="submit" class="btn btn-success btn-block" name="login" value="Masuk" />

        </form>
            
        </div>

        <div class="col-md-6">
            <!-- isi dengan sesuatu di sini -->
        </div>

    </div>
</div>
    
</body>
</html>