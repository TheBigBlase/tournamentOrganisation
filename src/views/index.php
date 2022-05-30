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
				case "signin_team":
						include "signin_team.html";
						include "contact.html";
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
include("footer.html");
?>
</body>
</html>
