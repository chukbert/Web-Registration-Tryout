<?php if(!isset($_POST["btnSubmit"])) { ?>
<html>
	<head>
		<title>kirim tiket(kartu peserta)</title>
	</head>
	<body>
		<h1> Pengirim tiket(kartu peserta) </h1>
		<form action="" method="POST">
            <div class="form-group">
                <label for="input">Masukkan id</label>
                <input class="form-control" type="number" name="input"  />
            </div>
            <input type="submit" name="btnSubmit"/>
        </form>
	</body>
</html>

<?php
} else {

require_once("config.php");
require('fpdf181/fpdf.php');
require 'vendor/autoload.php';

$id = $_POST['input'];
$user_id = $db->prepare("SELECT * FROM users WHERE id='$id'");
$user_id->execute();
$result = $user_id->fetch(PDO::FETCH_ASSOC);

$Jurusan = $result['Jurusan'];
$user_id_2 = $db->prepare("SELECT * FROM users WHERE Jurusan='$Jurusan'");
$user_id_2->execute();
$result_2 = $user_id_2->fetchAll(PDO::FETCH_ASSOC);
$num_of_rows = count($result_2);
$max = $result['nokursi'];
if ($max == 0) {
	for ($i = 0; $i < $num_of_rows; $i++) {
		if ($result_2[$i]['nokursi'] > $max) {
			$max = $result_2[$i]['nokursi'];
		}
	}
	$max++;
}
$user_id_3 = $db->prepare("UPDATE users SET nokursi='$max' WHERE id='$id'");
$user_id_3->execute();
$sudah = "sudah";
$user_id_4 = $db->prepare("UPDATE users SET Bayar='$sudah' WHERE id='$id'");
$user_id_4->execute();


$Name = $result['Nama'];
if($result['Jurusan'] == "SAINTEK") {
	$bidang = "SAINTEK";
	$nopeserta = 550000001 + (10*$max);
	$ruang = fmod($max,24);
	if($ruang == 0) $ruang = 24;
	if(fmod($ruang,2) == 1) $nopeserta += 10000;
	else $nopeserta += 20000;
} else {
	$bidang = "SOSHUM";
	$nopeserta = 550000002 + (10*$max);
	$ruang = fmod($max,6);
	if($ruang == 0) $ruang = 6;
	$ruang = $ruang + 24;
	if(fmod($ruang,2) == 1) $nopeserta += 10000;
	else $nopeserta += 20000;
}

$no_peserta = (string) $nopeserta;
$no_ruang = (string) $ruang;



$pdf = new FPDF('L','mm',array(210,105));


$pdf->SetMargins(2,2,2);
$pdf->AddPage();
$pdf->SetAutoPageBreak(TRUE,0);
$pdf->AddFont('Roof','','ufonts.com_roof-runners-active.php');
$pdf->SetFont('Roof');
$pdf->setFontSize(14);
$pdf->Image('img/kartupeserta.png',0,0,210);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetXY(9,56);
$pdf->Write(1.5, $Name);
$pdf->setFontSize(16);
$pdf->SetXY(102, 56);
$pdf->Write(1.5, $bidang);
$pdf->setFontSize(18);
$pdf->SetXY(30,88);
$pdf->Write(1.5, $no_peserta);
$pdf->SetXY(109,88);
$pdf->Write(1.5, $no_ruang);
$pdf->Output($id . ".pdf", 'F');


$email = new \SendGrid\Mail\Mail(); 
$email->setFrom("13517016@std.stei.itb.ac.id", "TONAMPTN 2019");
$email->setSubject("TIKET TONAMPTN");
$email->addTo($result['Email'], $result['Nama']);
$email->addContent("text/plain", "and easy to do anywhere, even with PHP");
$email->addContent(
    "text/html", "<strong>Dear Peserta Try-Out WBS 2019, <br><br>Berikut terlampir Kartu Peserta Try-Out WBS 2019. <br>Dimohon peserta dapat menunjukan kartu peserta tersebut sebagai bukti bahwa Saudara telah melakukan prosedur pendaftaran secara lengkap. <br><br>Peserta dianjurkan untuk mencetak kartu peserta atau dapat menunjukan email ini kepada panitia pada saat daftar ulang hari H.<br><br>Tetap semangat dan jangan lupa belajar.</strong>"
);

$file = (string) $id;
$file .= ".pdf";
$konsumen = $id;
$konsumen .= ".pdf";

$file_encoded = base64_encode(file_get_contents($file));
$email->addAttachment(
    $file_encoded,
    //"application/text",
    $file,
    $konsumen
);

$sendgrid = new \SendGrid('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');
try {
    $response = $sendgrid->send($email);
    print $response->statusCode() . "\n";
    print_r($response->headers());
    print $response->body() . "\n";
} catch (Exception $e) {
    echo 'Caught exception: '.  $e->getMessage(). "\n";
}

}
?>