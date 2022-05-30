<!doctype html>
<html lang="en">
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
            include "index.html";
            break;
    }
}
else{
    include "index.html";
}
?>
</main>
<?php
include("footer.html");
?>
</body>
</html>
