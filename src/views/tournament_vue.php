<?php
/** @var $conn mysqli */
include "../controller/tableController.php";
include "../controller/teamController.php";
include "../controller/matchController.php";
include "../controller/competitionController.php";

if(empty($_GET["compet_id"])){
    die("No competition selected");
}
$competId = intval($_GET["compet_id"]);
$competition = getCompetition($conn, $competId);

function searchIdInArray($id, $teams){
    foreach ($teams as $team){
        if($team["teamId"] == $id){
            return $team;
        }
    }
    return false;
}

//BIG TITLE
echo "<h1 class='big-title'>".$competition["competName"]."</h1><br><p class='title'>
        ".$competition["description"]."</p>
        ";

// Here we display the winner of the competition if it is finished :

if(competHasFinished($conn, $competId)){
    $winner = getCompetitionWinner($conn, $competId);
    echo "
        <p class='title'>The winner of this competition is : ".$winner["teamName"] ."</p>
    ";
}
else {
    echo "<p>This competition is still ongoing</p>";
}

// TEAMS
// Here, we get all the teams in the competition

$teams = getTeamsInCompetition($conn, $competId);
echo "<section class='displayer'>
        <h1 class='title'>ALL TEAMS</h1>
        <div class='all'>";
foreach ($teams as $team){
    echo "<span class='team'> ".$team["teamName"]." </span>";
}
echo "</div></section>";

// MATCHES
// Here, we display all the matches with their scores

$tables = getTablesForCompet($conn,  $competId);
echo "<section class='displayer'>
        <h1 class='title'>ALL TEAMS</h1>
        <div class='all'>";
foreach ($tables as $table){
    $matches = getMatchesFromTable($conn, $table["tableId"]);

    foreach ($matches as $match){
        $t1 = searchIdInArray($match["teamId1"], $teams);
        $t2 = searchIdInArray($match["teamId2"], $teams);

        ?>
        <div class='match'> <?php echo $t1["teamName"]." vs ".$t2["teamName"] . "(" .$table["tour"] .")"; ?>
            <span class='score'>
                    <?php
                    if(!empty($match["score1"]) && !empty($match["score2"])){
                        echo "Scores : ". $match["score1"] . " - " . $match["score2"];
                    }
                    else {
                        echo "No scores available at the moment";
                    }
                    ?>
                </span>
        </div>
        <?php
    }
}
echo "</div>";
echo "</section>";

echo "<div class='all'>";
if(isset($_SESSION["type"]) && $_SESSION["type"] == "admin"){
    echo "<a href='index.php?page=setscores&compet=$competId' class='coollink'>Set scores</a>";
}
echo "</div>";


