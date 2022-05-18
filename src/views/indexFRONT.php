<?php
include('connexion_db.php');

if(isset($_POST["nom_contact"])){
    echo '<strong>Thanks for you feedback !</strong>';
}

if(isset($_GET["out"])){
    session_destroy();
} else{
    if(session_status() === PHP_SESSION_NONE) session_start();
    echo "Status" . session_status();
}

$sqlTournamentVue = "SELECT competId,competName,endInscription FROM `competition`;";
$resultSqlTournamentVue = mysqli_query($conn, $sqlTournamentVue) or die("Requête invalide: " . mysqli_error($conn) . "\n" . $sqlTournamentVue);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="style.css">
        <title>Sigma Tournament</title>
    </head>
    <body>
        <!--HEADER-->
        <header>
            <!--NAVBAR-->
            <nav>
                <h1 class="name">Sigma Tournament</h1>
                <input type='checkbox' id='toggle'>
                <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Tournaments</a></li>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
                <div class="container">
                    <button type="button">Sign In / Sign Up</button>
                </div>
                <label for="toggle"><span class="bars"></span></label>
            </nav>
            <!--BANNER-->
            <section class="banner">
                <div class="tagline">
                    <h1>the best tournament management platform</h1>
                    <button type="button">begin with us</button>
                </div>
            </section>
        </header>

        <!--ONGOING-->
        <section class="ongoing">
            <h1 class="title">On Going Tournaments</h1>
            <div class="container">
                <div class="tournament">
                    <span class="name"><p class="title-cat">name</p><h1>Chess Tournament</h1></span>
                    <span class="description"><p class="title-cat">description</p><p>Chess Tournament ELO : 1800</p></span>
                    <span class="end"><p class="title-cat">end</p><h1>Sam 18 May.</h1></span>
                </div>
            </div>
        </section>
    </body>
</html>