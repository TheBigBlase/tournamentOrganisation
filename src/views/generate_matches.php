<?php
/** @var $conn mysqli */

include "Header.php";

if(!isset($_GET["compet"])){
    die("The competition id is not defined");
}
$competId=  $_GET["compet"];

/**
 * Creates the table with the round $roundNumber
 *
 * @var $conn mysqli
 * @var $competId int The id of the competition
 * @var $roundNumber int The number of the new round
 * @return int The dd of the table created.
 */
function createTable($conn, $competId, $roundNumber){
    $createRoundSQL = "
        INSERT INTO `table` (competId, tour) 
        VALUES (?, ?)";
    $createRoundRequest = $conn->prepare($createRoundSQL);
    $createRoundRequest->bind_param("ii", $competId, $roundNumber);

    if($createRoundRequest->execute())
        $lastId = $conn->insert_id;
    else{
        // If an error is thrown, it means that the round has already been created

        $lastRoundRequest = $conn->prepare("
            SELECT  t.tableId
            FROM `table` t
            WHERE t.tour = ?
        ");

        $lastRoundRequest->bind_param("i", $roundNumber);
        $lastRoundRequest->execute();
        $res = $lastRoundRequest->get_result();
        $lastId = $res->fetch_assoc()["tableId"];
    }

    return $lastId;
}

/**
 * Gets the ids of all the teams that won the round
 *
 * @param $conn mysqli
 * @param $competId int The id of the competition
 * @param $tour int The number of the round
 * @return array|void
 */
function getWinners($conn, $competId, $tour){
    $getMatchesSql = "
        SELECT mt.teamId1, mt.teamId2, mt.score1, mt.score2
        from competition c
            join `table` t on c.competId = t.competId
            join match_t mt on t.tableId = mt.tableId
        where c.competId = ? and t.tour = ?";
    $getMatchesRequest = $conn->prepare($getMatchesSql);
    $getMatchesRequest->bind_param("ii", $competId, $tour);
    try {
        $getMatchesRequest->execute();
    } catch (Exception $e){
        die($e->getMessage());
    }

    $result = $getMatchesRequest->get_result();
    if(!$result){
        // If no matches have been created, we stop the process
        return [];
    }

    $winners = array();
    while($row = $result->fetch_assoc()){

        if($row["score1"] === null || $row["score2"] === null){
            // If all the matches aren't finished, we return an empty array
            return [];
        }

        if($row["score1"] > $row["score2"]){
            $winners[] = $row["teamId1"];
        } elseif($row["score1"] < $row["score2"]){
            $winners[] = $row["teamId2"];
        }
    }

    return $winners;
}

/**
 * Sets the team to play in another table
 *
 * @param $conn mysqli
 * @param $teamId int The id of the team
 * @param $tableId int The id of the table the team is going into
 * @return void
 */
function registerTeamForRound($conn, $teamId, $tableId){
    $registerTeamInRoundSql = "
            INSERT INTO TABLE_TEAM (tableId, teamId) 
            VALUES (?, ?)";
    $registerTeamInRoundRequest = $conn->prepare($registerTeamInRoundSql);
    $registerTeamInRoundRequest->bind_param("ii",$tableId, $teamId);

    $registerTeamInRoundRequest->execute();

}


/**
 * @param $conn mysqli
 * @param $teamIds array The array of teams that need to go to the next table
 * @param $tableId int The table id we want the teams to go into
 * @return void
 */
function registerTeamsForRound($conn, $teamIds, $tableId){
    foreach ($teamIds as $teamId){
        registerTeamForRound($conn, $teamId, $tableId);
    }

}

/**
 * Gets all the Ids of the teams playing in a specific table.
 *
 * @param $conn mysqli
 * @param $tableId int
 * @return array
 */
function getTeamsInTable($conn, $tableId){
    $teamsRequest = $conn->prepare("
    SELECT t.teamId
    FROM TABLE_TEAM t
    where tableId = ?");

    $teamsRequest->bind_param("i", $tableId);

    $teamsRequest->execute();
    $result = $teamsRequest->get_result();

    $teams = [];
    while($row = $result->fetch_assoc()){
        $teams[] = $row["teamId"];
    }

    return $teams;
}

/**
 * @param $conn mysqli
 * @param $teamId
 * @return void
 */
function getTeamName($conn, $teamId){
    $teamNameRequest = $conn->prepare("
        SELECT teamName From team where teamId=?;
    ");
    $teamNameRequest->bind_param("i", $teamId);
    $teamNameRequest->execute();
    $res = $teamNameRequest->get_result();
    $row = $res->fetch_assoc();
    return $row["teamName"];
}

/**
 * @param $conn mysqli
 * @param $competId
 * @return int
 */
function getCurrentRound($conn, $competId){
    $currentTableRequest = $conn->prepare("Select t.tour
    from competition c join `table` t on c.competId = t.competId
        join match_t mt on t.tableId = mt.tableId
    where c.competId= ?
    ORDER BY t.tableId DESC");
    $currentTableRequest->bind_param("i", $competId);
    $currentTableRequest->execute();
    $res = $currentTableRequest->get_result();
    $r = $res->fetch_assoc();
    return $r["tour"];
}


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

    if (count($teamIds) % 2 == 0) {
        registerTeamsForRound($conn, $teamIds, $tableId);
    } else {
        $randomId = rand(0, count($teamIds) - 1);
        foreach ($teamIds as $k => $teamId) {
            if ($k != $randomId)
                registerTeamForRound($conn, $teamId, $tableId);
        }
        $n = createTable($conn, $competId, $roundNumber + 1);

        registerTeamForRound($conn, $teamIds[$randomId], $n);
        $removeTeam = $conn->prepare("
            DELETE FROM TABLE_TEAM
            where tableId=? and teamId=?
        ");
        $removeTeam->bind_param("ii", $tableId, $teamIds[$randomId]);
        try {
            $removeTeam->execute();
        } catch (Exception $e){
            die($e->getMessage());
        }
    }
}
else{
    // If tables are already created, we need to create the table for the next round
    $round = getCurrentRound($conn, $competId);
    $roundNumber = $round+1;

    $winnersIds = getWinners($conn, $competId, $roundNumber-1);

    if (empty($winnersIds))
        die("The previous matches aren't finished yet");

    $tableId = createTable($conn, $competId, $roundNumber);
    registerTeamsForRound($conn, $winnersIds, $tableId);

    $teamIds = getTeamsInTable($conn, $tableId);

    if (count($teamIds) % 2 != 0) {
        $randomId = rand(0, count($teamIds) - 1);
        $n = createTable($conn, $competId, $roundNumber + 1);

        registerTeamForRound($conn, $teamIds[$randomId], $n);

        $removeTeam = $conn->prepare("
            DELETE FROM TABLE_TEAM
            where tableId=? and teamId=?
        ");

        $removeTeam->bind_param("ii", $tableId, $teamIds[$randomId]);
        try {
            $removeTeam->execute();
        } catch (Exception $e){
            die($e->getMessage());
        }
    }
}

// Once the table is correctly created, we can create all the matches :
$teamIds = getTeamsInTable($conn, $tableId);
$nextMatches = [];
for($i=0; $i < count($teamIds)-1 ; $i = $i + 2){
    $teamId1 = $teamIds[$i];
    $teamId2 = $teamIds[$i+1];

    $nextMatches[] = [$teamId1, $teamId2];

    $createMatchRequest = $conn->prepare("
        INSERT INTO match_t (teamId1, teamId2, tableId)
        VALUES (?, ?, ?)
    ");

    $createMatchRequest->bind_param("iii",$teamId1, $teamId2,$tableId);
    try{
        $createMatchRequest->execute();
    } catch (Exception $e){
        $conn->rollback();
        die($e->getMessage());
    }

}

$conn->commit();
$conn->autocommit(TRUE);

// Now we display all the new matches :
foreach ($nextMatches as $nextMatch) {
    echo "<p> ".getTeamName($conn, $nextMatch[0])." vs ".getTeamName($conn, $nextMatch[1])." </p>";
}
