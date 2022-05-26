<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tournaments</title>
</head>
<body>
<?php
include "header.php";
?>
<main>
<?php
if(isset($_GET["page"])){
    switch ($_GET["page"]){
        case "signinup":
            include "signinup.php";
            break;
        case "competition":
            include "tournament_vue.php";
            break;
        case "generate_matches":
            include "generate_matches.php";
            break;
        case "create_team":
            include "create_team.php";
            break;
        case "inscription":
            include "inscription_tournament.php";
            break;
        case "setscores":
            include "setscores.php";
            break;
        case "create_competition":
            include "create_competition.php";
            break;
        default :
            include "welcome.php";
            break;
    }
}
else{
    include "welcome.php";
}
?>
</main>

<footer>
    &copy; Polytech 2021 - 2022
</footer>
</body>
</html>