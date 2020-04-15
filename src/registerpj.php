<?php

require_once("config.php");

if(isset($_POST['register'])){
    if (isset($_POST["Jurusan"])) {
    // filter data yang diinputkan
        if($_POST["Nama"] == "") $error_nama = true;
        else $error_nama = false;
        if($_POST["Email"] == "") $error_email = true;
        else $error_email = false;
        if($_POST["sekolah"] == "") $error_sekolah = true;
        else $error_sekolah = false;
        if($_POST["kontak"] == "") $error_kontak = true;
        else $error_kontak = false;
        if($_POST["Password"] == "") $error_password = true;
        else $error_password = false;
            $Nama = filter_input(INPUT_POST, 'Nama', FILTER_SANITIZE_STRING);
            $Email = filter_input(INPUT_POST, 'Email', FILTER_VALIDATE_EMAIL);
            // enkripsi password
            $Password = password_hash($_POST["Password"], PASSWORD_DEFAULT);
            $Password2 = password_hash($_POST["Password2"], PASSWORD_DEFAULT);
            $sekolah = filter_input(INPUT_POST, 'sekolah', FILTER_SANITIZE_STRING);
            $line  = filter_input(INPUT_POST, 'kontak', FILTER_SANITIZE_STRING);
            $jurusan = $_POST["Jurusan"];
            $PJ = $_POST["PJ"];
            $bayar = "belum";
            $nokursi = 0;
            $user_check_query = $db->prepare("SELECT * FROM users WHERE Email='$Email'");
            $user_check_query->execute();
            $num_of_rows = $user_check_query->fetchColumn();
            if ($num_of_rows == 0 && $_POST["Password"] == $_POST["Password2"] && !$error_nama && !$error_email && !$error_sekolah && !$error_kontak && !$error_password) {
                // menyiapkan query
                $sql = "INSERT INTO users (Nama, Email, Password, sekolah, kontak, Jurusan, PJ, bayar, nokursi) 
                        VALUES (:Nama, :Email, :Password, :sekolah, :kontak, :Jurusan, :PJ, :bayar, :nokursi)";
                $stmt = $db->prepare($sql);
                // select 
                // bind parameter ke query
                $params = array(
                    ":Nama" => $Nama,
                    ":Email" => $Email,
                    ":Password" => $Password,
                    ":sekolah" => $sekolah,
                    ":kontak" => $line,
                    ":Jurusan" => $jurusan,
                    ":PJ" => $PJ,
                    ":bayar" => $bayar,
                    ":nokursi" => $nokursi
                );
                // eksekusi query untuk menyimpan ke database
                $stmt->execute($params);
                // jika query simpan berhasil, maka user sudah terdaftar
                // maka alihkan ke halaman login
                // if($saved) header("Location: login.php");
                // else echo " Ada yang salah dengan input yang kamu masukan silakan ulangi pengisian dengan lebih teliti";
                //header("Location: login.php");
                
                echo "Registrasi berhasil, tunggu kami memverifikasi data diri kamu. Jika dalam 3 hari belum mendapat email dari Kami mohon hubungi CP<br>";
                echo "Sekarang kamu sudah bisa log in ke akun WBS kamu";
            } else {
                echo "Registrasi gagal\r\n<br>";
                if($_POST["Password"] != $_POST["Password2"]) echo "Confirm password tidak sama<br>";
                if($error_nama) echo "Nama tidak boleh kosong<br>";
                if($error_email) echo "Alamat email harus diisi<br>";
                if($error_sekolah) echo "Asal Sekolah tidak boleh kosong<br>";
                if($error_password) echo "Password tidak boleh kosong<br>";
                if($error_kontak) echo "Mohon ID line diisi atau jika tidak mempunyai id line dapat memberikan no. telp yang dapat dihubungi<br>";
            }
    } else {
        echo "Registrasi gagal<br> Anda belum memilih jurusan\r\n";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Registrasi TONAMPTN</title>

    <link rel="stylesheet" href="css/bootstrap.min.css" />
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row">
        <div class="col-md-6">

        <p>&larr; <a href="index.php">Home</a>

        <h4>Form Pendaftaran Try Out WBS 2019 Via Penanggung jawab SMA</h4>
        <p>Jika kamu ingin mendaftar secara mandiri (melalui transfer), <a href="register.php">daftar di sini</a></p>
        <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>

        <form action="" method="POST">

            <div class="form-group">
                <label for="Nama">Nama Lengkap</label>
                <input class="form-control" type="text" name="Nama" placeholder="Nama lengkap kamu" />
            </div>

            <div class="form-group">
                <label for="Email">Alamat Email</label>
                <input class="form-control" type="email" name="Email" placeholder="contoh : (abcde@gmail.com)" />
            </div>

            <div class="form-group">
                <label for="Password">Password</label>
                <input class="form-control" type="password" name="Password" placeholder="Password" />
            </div>

            <div class="form-group">
                <label for="Password2">Confirm Password</label>
                <input class="form-control" type="password" name="Password2" placeholder="Confirm Password" />
            </div>

            <div class="form-group">
                <label for="sekolah">Asal Sekolah</label>
                <input class="form-control" type="text" name="sekolah" placeholder="Contoh penulisan: SMAN 1 Surakarta, SMK 2 Surakarta, MAN 1 Surakarta" />
            </div>

            <div class="form-group">
                <label for="kontak">ID Line (jika tidak punya silakan isi dengan no. telp yang dapat dihubungi)</label>
                <input class="form-control" type="text" name="kontak" placeholder="ID Line" />
            </div>            

            <div class="form-group">
                <label for="Jurusan">Jurusan</label><br>
                <input type="radio" name="Jurusan" value="SAINTEK"> SAINTEK<br>
                <input type="radio" name="Jurusan" value="SOSHUM"> SOSHUM <br>
            </div> 

             <div class="form-group">
                <label for="PJ">Kepada siapa kamu mendaftarkan diri? (Pilih salah satu Penanggung Jawab SMA) </label><br>
                <select name="PJ">
                <option name="PJ" value="SMA1"> Widihanantoro                           (SMAN 1 Surakarta) </option>
                <option name="PJ" value="SMA2"> Anggradhika Kamessywara         (SMAN 2 Surakarta) </option>
                <option name="PJ" value="SMA7"> Bima Subiakto                   (SMAN 7 Surakarta) </option>
                <option name="PJ" value="SMA3"> Caesar Rahadito Wisnu Murti     (SMAN 3 Surakarta) </option>
                <option name="PJ" value="SMA4"> Afra Lana Nurcahya              (SMAN 4 Surakarta) </option>
                <option name="PJ" value="SMA5"> Kevin Putra K.                  (SMAN 5 Surakarta) </option>
                <option name="PJ" value="SMA6"> Hasya                           (SMAN 6 Surakarta) </option>
                <option name="PJ" value="MTA"> Faisyal Miftahul Fahmi          (SMA MTA) </option>
                <option name="PJ" value="WARGA"> Andrianto Rohmat Tulah          (SMA Warga) </option>
                <option name="PJ" value="KARTASURA1"> Rizky Eka Saputri                 (SMAN 1 Kartasura) </option>
                <option name="PJ" value="SUKOHARJO1"> Jane Raihan                       (SMAN 1 Sukoharjo) </option>
                <option name="PJ" value="SUKOHARJO2"> Qoriatul                          (SMAN 2 Sukoharjo) </option>
                <option name="PJ" value="SUKOHARJO3"> Ira Iriyanti                      (SMAN 3 Sukoharjo) </option>
                <option name="PJ" value="IT"> Azura Hasna Aqila                      (SMA IT Nur Hidayah) </option>
                <option name="PJ" value="BOYOLALI1"> Multazam Hanifurrahman            (SMAN 1 Boyolali) </option>
                <option name="PJ" value="BOYOLALI3"> Muhammad Luthfi Imanullah         (SMAN 3 Boyolali) </option>
                <option name="PJ" value="SRAGEN1"> Muhammad Luthfi Shidik             (SMAN 1 Sragen) </option>
                <option name="PJ" value="SRAGEN2"> Kevin Ramadhan Putra               (SMAN 2 Sragen) </option>
                <option name="PJ" value="SRAGEN3"> Yeni Anisa                         (SMAN 3 Sragen) </option>
                <option name="PJ" value="KARANGANYAR1"> Erma Nur Janahwati                (SMAN 1 Karanganyar) </option>
                <option name="PJ" value="PANDAN"> Dewi Agustina Wulandari         (SMAN Karangpandan) </option>
                <option name="PJ" value="WONOGIRI1"> Briyan Ari Santoso                (SMAN 1 Wonogiri) </option>
                <option name="PJ" value="WONOGIRI2"> Abdurachim                        (SMAN 2 Wonogiri) </option>
                <option name="PJ" value="BATIK2"> Rahmadan Enindra Putri          (SMA Batik 2 Surakarta) </option>
                <option name="PJ" value="OTHER"> Lain-lain </option>
            </select>
            </div> 

            <input type="submit" class="btn btn-success btn-block" name="register" value="Daftar" />

        </form>

        </div>
    </div>
</div>

</body>
</html>