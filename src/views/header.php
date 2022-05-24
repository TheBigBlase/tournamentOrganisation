<?php

session_start();

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
            <a href='signinup.php'>
                <span>Sign in / Sign up</span>
            </a>
        </div><br>
        ";
}

// todo : check if user is in a team. If it's not the case, we should have the option to create a team / join a team.
?>
</header>
