
<?php
require 'vendor/autoload.php'; // If you're using Composer (recommended)
// Comment out the above line if not using Composer
// require("<PATH TO>/sendgrid-php.php");
// If not using Composer, uncomment the above line and
// download sendgrid-php.zip from the latest release here,
// replacing <PATH TO> with the path to the sendgrid-php.php file,
// which is included in the download:
// https://github.com/sendgrid/sendgrid-php/releases
$data = "<link href=\"https:\/\/fonts.googleapis.com/css?family=Noto+Sans+TC\" rel=\"stylesheet\" /> <div style=\"background-color: #f1f1f1;padding-top: 20px; padding-bottom: 30px;\"> <h1 style=\"font-size: 24px; text-align: center;font-family: Noto Sans TC, sans-serif;\"> Bismillah </h1> <div style=\"width: 90%; margin-left: auto; margin-right: auto; background-color: #fff; padding: 10px 15px;\"> <p style=\"font-size: 16px;font-family: Noto Sans TC, sans-serif;\"> blablababal seharga ";
$data .= (string)(35000 + 5);
$data .= " </p> </div> </div>";
$email = new \SendGrid\Mail\Mail(); 
$email->setFrom("tonamptn@wikatonamptn.com", "TONAMPTN 2019");
$email->setSubject("TIKET TONAMPTN");
$email->addTo("faizmuh26@gmail.com", "chukbert");
$email->addContent("text/plain", "and easy to do anywhere, even with PHP");
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