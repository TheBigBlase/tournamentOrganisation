<?php
include "Header.php";
include "../controller/tableController.php";
include "../controller/matchController.php";
include "../controller/teamController.php";
/** @var $conn mysqli */

// todo : check if the user is an admin

if(!isset($_GET["compet"])){
    die("The competition is not defined");
}

$competId = (int)$_GET["compet"];

$table = getOnGoingTable($conn, $competId);


if(isset($_POST["submitbutton"])){
    $teamId1 = intval($_POST["teamId1"]);
    $teamId2 = intval($_POST["teamId2"]);

    $score1 = intval($_POST["score1"]);
    $score2 = intval($_POST["score2"]);

    $conn->autocommit(FALSE);

    updateMatchScores($conn , $table["tableId"], $teamId1, $teamId2, $score1, $score2);

    $conn->commit();
    $conn->autocommit(TRUE);
}

$matches = getMatchesFromTable($conn, $table["tableId"]);

foreach ($matches as $match){
    $team1 = getTeam($conn, $match["teamId1"]);
    $team2 = getTeam($conn, $match["teamId2"]);

    // todo : in each form, put a hidden input to recognize which match results have been sent
    ?>
<div>
    For the match : <?php echo $team1["teamName"]." vs ".$team2["teamName"] ?>

    <form action="setscores.php?compet=<?php echo $competId ?>" method="post">
        <input type="hidden" name="teamId1" value="<?php echo $team1["teamId"]; ?>">
        <input type="hidden" name="teamId2" value="<?php echo $team2["teamId"]; ?>">
        <p>
            <label for="score1">Score <?php echo $team1["teamName"]; ?></label>
            <input type="number" name="score1" id="score1" value="<?php echo $match["score1"]?>">
        </p>
        <p>
            <label for="score2">Score <?php echo $team2["teamName"]; ?></label>
            <input type="number" name="score2" id="score2" value="<?php echo $match["score2"]?>">
        </p>
        <input type="submit" name="submitbutton" value="Update">
    </form>
</div>

    <?php
}