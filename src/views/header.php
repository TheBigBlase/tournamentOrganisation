<?php

if(session_status() === PHP_SESSION_NONE) session_start();
if(isset($_GET["out"])){
    session_destroy();
    header("Location: index.php");
    exit();
}

include('../database/connexion_db.php');

var_dump($_SESSION);
?>

<style>
h1{
    text-align: center;
}
</style>
<header>
    <h1><a href="index.php">Tournament</a></h1>

    <!-- signup/signin section -->
    <style>
        td{
            border: black 1px solid;
        }
    </style>
<?php
if(isset($_SESSION["name"])){
    echo "
        <div class='logout'>".$_SESSION["name"]."
            <a href='index.php?out=out'>
                <span>Log out</span>
            </a>
        </div><br>
        ";
}else{
    echo "
        <div class='signinup'> 
            <a href='index.php?page=signinup'>
                <span>Sign in / Sign up</span>
            </a>
        </div><br>
        ";
}

if(empty($_SESSION["team"]) && !empty($_SESSION["ID"])){
    echo "<a href='create_team.php'>Create your own team</a>";
}

?>
</header>
