<!doctype html>
<html lang="en">
<?php
include "header.php";
?>
<body>
<main>
<?php
if(isset($_GET["page"])){
    switch ($_GET["page"]){
        case "signinup":
            include "signinup.php";
            break;
        case "teams":
            include "team.html";
            include "contact.html";
            break;
        case "showTournaments":
            include "tournament.html";
            include "showTournaments.php";
            include "contact.html";
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
            include "contact.html";
            break;
        default :
            include "index.html";
            include "showTournaments.php";
            include "contact.html";
            break;
    }
}
else{
    include "index.html";
    include "showTournaments.php";
    include "contact.html";
}
?>
</main>
<?php
if(isset($_GET["page"])){
    if ($_GET["page"] == "inscription" || $_GET["page"] == "create_team"){
        include "footer_small.html" ;
    }
    else
        include "footer.html";
}
?>
</body>
</html>
