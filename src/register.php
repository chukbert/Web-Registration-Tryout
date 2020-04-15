<?php
require_once("config.php");
require 'vendor/autoload.php';
// jurusan isset yang lain ""
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
            $PJ = "No";
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
                $user_id = $db->prepare("SELECT * FROM users WHERE Email='$Email'");
                $user_id->execute();
                $result = $user_id->fetch(PDO::FETCH_ASSOC);
                
                $data = "<link href=\"https:\/\/fonts.googleapis.com/css?family=Noto+Sans+TC\" rel=\"stylesheet\" /> <div style=\"background-color: #f1f1f1;padding-top: 20px; padding-bottom: 30px;\"> <h1 style=\"font-size: 24px; text-align: center;font-family: Noto Sans TC, sans-serif;\"> Konfirmasi Pembayaran Tiket Try Out WBS 2019 </h1> <div style=\"width: 90%; margin-left: auto; margin-right: auto; background-color: #fff; padding: 10px 15px;\"> <p style=\"font-size: 16px;font-family: Noto Sans TC, sans-serif;\"> Dear ";
				$data .= $result['Nama'];
				$data .= ", <br><br> Pendaftaranmu hampir selesai! <br> Segera lakukan pembayaran untuk tiket Try-Out Widyakelana Back to School (WBS) 2019 dengan rincian sebagai berikut, <br><br> Biaya yang harus ditransfer : Rp ";
				$data .= (string)(35000 + $result['id']);
				$data .= "<br> Rekening tujuan : <br> - Bank Syariah Mandiri : 7100321771 a.n. FAIZ MUHAMMAD MUFLICH <br> - BNI : 0718818137 a.n. SYARIFUDDIN FAKHRI AL HUSAINI <br> - BNI : 0258943474 a.n. OKUGATA FAHMI NURUL YUDHO FAUZAN <br><br> Cukup transfer ke salah satu rekening di atas. Harap diperhatikan untuk jumlah uang yang ditransfer sampai 3 angka paling belakang, dikarenakan ketidak sesuaian jumlah uang yang ditransfer akan menimbulkan lamanya proses verifikasi. <br><br> Biaya tambahan seperti biaya admin bank ditanggung peserta sendiri. Diharapkan untuk menyimpan bukti transfer agar sewaktu-waktu jika dibutuhkan tersedia. <br><br> Jika dalam 3 hari setelah melakukan pembayaran tidak mendapat email balasan dari panitia harap hubungi: <br><br> ID LINE : faizmuh26 <br><br> Jangan lupa belajar dan tetap semangat! <br><br> Cheers, <br> Panitia WBS 2019";
                $email = new \SendGrid\Mail\Mail(); 
                $email->setFrom("13517016@std.stei.itb.ac.id", "WBS TONAMPTN 2019");
                $email->setSubject("Registrasi Try Out WBS 2019");
                $email->addTo($result['Email'], $result['Nama']);               
                $email->addContent(
                    "text/html", $data
                );
                $sendgrid = new \SendGrid('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');
                try {
                    $response = $sendgrid->send($email);
                    // print $response->statusCode() . "\n";
                    // print_r($response->headers());
                    // print $response->body() . "\n";
                } catch (Exception $e) {
                    echo 'Caught exception: '. $e->getMessage() ."\n";
                }
                echo "Registrasi berhasil silakan cek email kamu untuk tahap registrasi berikutnya <br>";
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
<body class="bg-white">

<div class="container mt-5">
    <div class="row">
        <div class="col-md-6">

        <p>&larr; <a href="index.php">Home</a>

        <h4>Form Pendaftaran Try Out WBS 2019 Jalur Mandiri</h4>
        <p>Jika kamu mendaftar melalui penanggung jawab SMA, <a href="registerpj.php">daftar di sini</a></p>
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

            <input type="submit" class="btn btn-success btn-block" name="register" value="Daftar" />

        </form>
            
        </div>

        <div class="col-md-6">
            <img class="img img-responsive" src="img/logo-wbs2.jpeg" />
        </div>

    </div>
</div>

</body>
</html>