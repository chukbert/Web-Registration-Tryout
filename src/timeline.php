<?php require_once("auth.php"); ?>

<?php
if (isset($_POST['download'])) {
    $URL = $_SESSION["user"]["id"] . '.pdf';
   header("Location: " . $URL);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Widyakelana Back to School</title>

    <link rel="stylesheet" href="css/bootstrap.min.css" />
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row">
        <div class="col-md-4">

            <div class="card">
                <div class="card-body text-center">
                                      
                    <h3><?php echo  $_SESSION["user"]["Nama"] ?></h3>
                    <p><?php echo $_SESSION["user"]["Email"] ?></p>

                    <p><a href="logout.php">Logout</a></p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-body text-center">
                    <p> Status : <?php echo  $_SESSION["user"]["Bayar"] ?> membayar </p>
                </div>
            </div>

        <!--
        </div>
            <form action="" method="POST">
             <input type="submit" class="btn btn-success btn-block" name="download" value="Download tiket" />
            </form>
        </div>
        -->
    </div>
</div>

</body>
</html>