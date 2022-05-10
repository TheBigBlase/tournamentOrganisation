<?php
/*Connexion à la base de données*/
$conn = @mysqli_connect("localhost", "root", "");

if (mysqli_connect_errno()) {
    $msg = "erreur ". mysqli_connect_error();
} else {
    $msg = "connecté au serveur " . mysqli_get_host_info($conn);
    /*Sélection de la base de données*/
    mysqli_select_db($conn, "manga");
    /*mysqli_select_db($conn, "etu"); */ /*sélection de la base sous la VM info642*/

    /*Encodage UTF8 pour les échanges avecla BD*/
    mysqli_query($conn, "SET NAMES UTF8");
}
?>