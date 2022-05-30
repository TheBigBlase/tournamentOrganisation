<?php

if(session_status() === PHP_SESSION_NONE) session_start();
if(isset($_GET["out"])){
    session_destroy();
    header("Location: index.php");
    exit();
}

include('../database/connexion_db.php');

?>
<head>
		<link rel="stylesheet" type="text/css" href="style.css">
		<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css2?family=Yaldevi:wght@300&display=swap" rel="stylesheet"> 
</head>
        <header>
            <!--NAVBAR-->
            <nav>
                <h1 class="name"><a href="index.php" style="text-decoration:none; color:black">Sigma Tournament</a></h1>
                <input type='checkbox' id='toggle'>
                <ul>
                    <li><a href="index.html">Home</a></li>
                    <li><a href="tournament.html">Tournaments</a></li>
                    <li><a href="team.html">Teams</a></li>
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

								if(empty($_SESSION["team"]) && !empty($_SESSION["ID"])){
										echo "<a href='index.php?page=create_team'>Create your own team</a>";
								}

								?>

                </div>
                <label for="toggle"><span class="bars"></span></label>
            </nav>
            <!--BANNER-->
	</header>
