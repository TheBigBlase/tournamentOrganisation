<?php

if(session_status() === PHP_SESSION_NONE) session_start();
if(isset($_GET["out"])){
    session_destroy();
    header("Location: index.php");
    exit();
}

include('../database/connexion_db.php');

?>

<html lang="en">
<head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="style.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Yaldevi:wght@300&display=swap" rel="stylesheet"> 
        <title>Sigma Tournament</title>
</head>
<body>
        <header>
            <!--NAVBAR-->
            <nav>
                <h1 class="name"><a href="index.php" style="text-decoration:none; color:black">Sigma Tournament</a></h1>
                <input type='checkbox' id='toggle'>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="index.php?page=showTournaments">Tournaments</a></li>
                    <li><a href="index.php?page=teams">Teams</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
                <div class="container">

								<?php
								if(isset($_SESSION["name"])){
									echo "
										<div class=\"container\">"
										. $_SESSION["name"] ."
                    <button type='button' onclick=\"window.location.href='index.php?out=out';\"> Logout </button>
										</div>
												";
								}else{
										echo "
												<button type='button' onclick=\"window.location.href='index.php?page=signinup';\">Sign in </button>";
								}

								?>

                </div>
                <label for="toggle"><span class="bars"></span></label>
            </nav>
            <!--BANNER-->
	</header>
