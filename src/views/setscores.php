<?php
include "../controller/tableController.php";
include "../controller/matchController.php";
include "../controller/teamController.php";
/** @var $conn mysqli */

if($_SESSION["type"] != "admin" && $_SESSION["type"] != "staff"){
    header("Location: index.php");
    exit();
}

if(!isset($_GET["compet"])){
    die("The competition is not defined");
}

$competId = intval($_GET["compet"]);

$table = getOnGoingTable($conn, $competId);


if(isset($_POST["submitbutton"])){
    $ok = true;
    $teamId1 = intval($_POST["teamId1"]);
    $teamId2 = intval($_POST["teamId2"]);

    $score1 = intval($_POST["score1"]);
    $score2 = intval($_POST["score2"]);

    if($score1 == $score2){
        $ok = false;
        echo "Scores must not be the same";
    }

    $team1 = getTeam($conn, $teamId1);
    $team2 = getTeam($conn, $teamId2);

    if(empty($team1)){
        $ok = false;
        echo "Team ".$teamId1." not recognized";
    }
    if(empty($team2)){
        $ok = false;
        echo "Team ".$teamId2." not recognized";
    }
    if($ok){
        $conn->autocommit(FALSE);

        updateMatchScores($conn , $table["tableId"], $teamId1, $teamId2, $score1, $score2);

        $conn->commit();
        $conn->autocommit(TRUE);

        echo "<div class='success'>Score successfully uploaded for match ".$team1["teamName"]." vs ".$team2["teamName"]. "</div>";
    }

}

$matches = [];
if(!empty($table)){
    $matches = getMatchesFromTable($conn, $table["tableId"]);
}

foreach ($matches as $match){
    $team1 = getTeam($conn, $match["teamId1"]);
    $team2 = getTeam($conn, $match["teamId2"]);

    ?>
    <h1 class="title">For the match : <?php echo $team1["teamName"]." vs ".$team2["teamName"] ?> </h1>
<section class="form">
    <form class="formform" action="index.php?page=setscores&compet=<?php echo $competId ?>" method="post">
        <input type="hidden" name="teamId1" value="<?php echo $team1["teamId"]; ?>">
        <input type="hidden" name="teamId2" value="<?php echo $team2["teamId"]; ?>">
        <div class="descript">
            <label for="score1">Score <?php echo $team1["teamName"]; ?></label>
            <input type="number" name="score1" id="score1" value="<?php echo $match["score1"]?>">
        </div>
        <div class="descript">
            <label for="score2">Score <?php echo $team2["teamName"]; ?></label>
            <input type="number" name="score2" id="score2" value="<?php echo $match["score2"]?>">
        </div>
        <input id="submit" type="submit" name="submitbutton" value="Upload Scores">
    </form>
</section>
<!--SEPARATION-->
<br><br>
<section class="sep"></section>
    <?php
}
?>

<form action="index.php?page=generate_matches" method="post">
    <input type="hidden" name="compet" value="<?php echo $competId ?>">
    <input type="submit" value="Generate next matches">
</form>
