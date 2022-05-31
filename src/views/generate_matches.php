<?php
/** @var $conn mysqli */

include "../controller/tableController.php";
include "../controller/teamController.php";
include "../controller/matchController.php";

if($_SESSION["type"] != "admin" && $_SESSION["type"] != "staff"){
    header("Location: index.php");
    exit();
}

if(empty($_POST["compet"])){
    
    die("The competition id is not defined");
}
$competId=  $_POST["compet"];
?>
<a href="index.php?page=setscores&compet=<?php echo $competId ?>">Set scores</a>
<?php
// At first, we check if a table has already been created :
$sql = "
    Select c.competId, t.tableId, t.tour 
    from competition c left join `table` t on c.competId = t.competId 
    where c.competId =?
    ORDER BY tableId DESC";
$req = $conn->prepare($sql);
$req->bind_param("i", $competId);
try{
    $req->execute();
} catch (Exception $e){
    die("<h3> Erreur : ".$e->getMessage());
}
$result = $req->get_result();
$firstRow = $result->fetch_assoc();

if(empty($firstRow)){
    die("This competition doesn't exist");
}

// We NEVER commit before being ABSOLUTELY SURE that it will work flawlessly
$conn->autocommit(FALSE);

$roundNumber = 1;

$tableId = $firstRow["tableId"];

echo "<p> Generating matches ...</p>";


if($firstRow['tableId'] == null){
    // If no tables are created, we need to create the 1 round
    $tableId = createTable($conn, $competId, $roundNumber);

    // Then we add all the players in the said round
    $createTableTeamSQL = "
        INSERT INTO TABLE_TEAM (tableId, teamId) 
        VALUES (?, ?)";
    // As it is the very first round, every team will be there.

    $getAllTeamsSQL = "SELECT teamId from TEAM_COMPET where competId=?";
    $getAllTeamsRequest = $conn->prepare($getAllTeamsSQL);
    $getAllTeamsRequest->bind_param("i", $competId);
    if(!$getAllTeamsRequest->execute()){
        die("Error while trying to get participants of a competition");
    }

    $result = $getAllTeamsRequest->get_result();
    $teamIds = array();
    while ($row = $result->fetch_assoc()){
        $teamIds[] = $row["teamId"];
    }
    // Now there are 2 cases :
    // either we have an even number of teams, and it doesn't pose any problem,
    // or we have an uneven number, and we will have to make a random team win by default

    if(count($teamIds) == 0){
        $conn->rollback();
        die("No teams are currently entered into the competition");
    } elseif (count($teamIds) % 2 == 0) {
        registerTeamsForRound($conn, $teamIds, $tableId);
    } else {
        $randomId = rand(0, count($teamIds) - 1);
        foreach ($teamIds as $k => $teamId) {
            if ($k != $randomId)
                registerTeamInTable($conn, $teamId, $tableId);
        }
        $n = createTable($conn, $competId, $roundNumber + 1);

        registerTeamInTable($conn, $teamIds[$randomId], $n);
        removeTeamFromTable($conn, $teamIds[$randomId], $tableId);
    }
}
else{
    // If tables are already created, we need to create the table for the next round
    $onGoingTable = getOnGoingTable($conn, $competId);
    if($onGoingTable == null){
        die("Unable to generate matches for this competition : $competId");
    }
    $round = getOnGoingTable($conn, $competId)["tour"];
    $roundNumber = $round+1;

    echo "<p> Creating round $roundNumber</p>";


    $winnersIds = getRoundWinners($conn, $competId, $roundNumber-1);

    if (empty($winnersIds))
        die("<p> Previous matches are not over</p>");

    $tableId = createTable($conn, $competId, $roundNumber);
    registerTeamsForRound($conn, $winnersIds, $tableId);

    $teamIds = getTeamsInTable($conn, $tableId);

    if(count($teamIds) == 1){
        // If there is only 1 team inside the teamdIds, it means that it is the winner of the whole competition
        $team = getTeam($conn, $teamIds[0]);
        echo "
            <p> Congrats to ".$team["teamName"].", you won the competition </p>
            <a href='index.php?page=competition&compet_id=$competId'>Go back to the tournament</a>
        ";

        return;
    }

    if (count($teamIds) % 2 != 0) {
        $randomId = rand(0, count($teamIds) - 1);
        $n = createTable($conn, $competId, $roundNumber + 1);

        registerTeamInTable($conn, $teamIds[$randomId], $n);
        removeTeamFromTable($conn, $teamIds[$randomId], $tableId);

    }
}

$nextMatches = generateMatchesForTable($conn, $tableId);

$conn->commit();
$conn->autocommit(TRUE);



echo "<p>New matches : </p>";
// Now we display all the new matches
foreach ($nextMatches as $nextMatch) {
    $team1 = getTeam($conn, $nextMatch[0]);
    $team2 = getTeam($conn, $nextMatch[1]);
    echo "<p> ".$team1["teamName"]." vs ".$team2["teamName"]." </p>";
}

?>
