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
function searchIdInArray($id, $teams){
    foreach ($teams as $team){
        if($team["teamId"] == $id){
            return $team;
        }
    }
    return false;
}

// Here we display the winner of the competition if it is finished :

if(competHasFinished($conn, $competId)){
    $winner = getCompetitionWinner($conn, $competId);
    echo "
        <p>The winner of this competition is : ".$winner["teamName"] ."</p>
    ";
}
else {
    echo "<p>This competition is still ongoing</p>";
}
// Here, we get all the teams in the competition

$teams = getTeamsInCompetition($conn, $competId);

foreach ($teams as $team){
    echo "<div class='team'> ".$team["teamName"]." </div>";
}

// Here, we display all the matches with their scores

$tables = getTablesForCompet($conn,  $competId);

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

if(isset($_SESSION["type"]) && $_SESSION["type"] == "admin"){
    echo "<a href='index.php?page=setscores&compet=$competId'>Set scores</a>";
}