<?php

$a = include('config.php');/*Connexion à la base de données*/
$conn = @mysqli_connect($credentials["url"], $credentials["user"], $credentials["pass"]);

if (mysqli_connect_errno()) {
    $msg = "erreur ". mysqli_connect_error();
    echo $msg;
} else {
    $msg = "connecté au serveur " . mysqli_get_host_info($conn);
    /*Sélection de la base de données*/
    mysqli_select_db($conn, $nameDB);

    /*Encodage UTF8 pour les échanges avecla BD*/
    mysqli_query($conn, "SET NAMES UTF8");
    if(session_status() === PHP_SESSION_NONE) session_start();
}
?> 