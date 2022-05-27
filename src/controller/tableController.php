<?php
/**
 * Creates the table with the round $roundNumber
 *
 * @var $conn mysqli
 * @var $competId int The id of the competition
 * @var $roundNumber int The number of the new round
 * @return int The id of the table created.
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

    return $lastId; // We return the id of the table we juste created
}

/**
 * Sets the team to play in another table
 *
 * @param $conn mysqli
 * @param $teamId int The id of the team
 * @param $tableId int The id of the table the team is going into
 * @return void
 */
function registerTeamInTable($conn, $teamId, $tableId){
    $registerTeamInRoundSql = "
            INSERT INTO TABLE_TEAM (tableId, teamId) 
            VALUES (?, ?)";
    $registerTeamInRoundRequest = $conn->prepare($registerTeamInRoundSql);
    $registerTeamInRoundRequest->bind_param("ii",$tableId, $teamId);

    $registerTeamInRoundRequest->execute();

}

/**
 * Registers multiple teams in a table
 *
 * @param $conn mysqli
 * @param $teamIds array The array of teams that need to go to the next table
 * @param $tableId int The table id we want the teams to go into
 * @return void
 */
function registerTeamsForRound($conn, $teamIds, $tableId){
    foreach ($teamIds as $teamId){
        registerTeamInTable($conn, $teamId, $tableId);
    }

}

/**
 * Gets the very last table of the competition
 *
 * @param $conn mysqli
 * @param $competId int The competition id
 * @return array|null the table in question
 */
function getLastTableFromCompet($conn, $competId){
    $lastTableRequest = $conn->prepare("
        SELECT tableId, competId, tour
        from `table`
        where competId = ?
        ORDER BY tour DESC  
    ");
    $lastTableRequest->bind_param("i", $competId);
    $lastTableRequest->execute();
    $res = $lastTableRequest->get_result();
    return $res->fetch_assoc();
}

/**
 * Gets the ongoing table of matches of a specific competition
 *
 * @param $conn mysqli
 * @param $competId int The competition id
 * @return array|null the table in question
 */
function getOnGoingTable($conn, $competId){
    $currentTableRequest = $conn->prepare("Select t.tableId, t.competId, t.tour
    from competition c join `table` t on c.competId = t.competId
        join match_t mt on t.tableId = mt.tableId
    where c.competId= ?
    ORDER BY t.tableId DESC");
    $currentTableRequest->bind_param("i", $competId);
    $currentTableRequest->execute();
    $res = $currentTableRequest->get_result();
    $r = $res->fetch_assoc();
    if($r == null){
        return getLastTableFromCompet($conn, $competId);
    }
    return $r;
}

/**
 * Removes the Team passed in parameter from the table.
 * If an error is caught, it stops the program
 *
 * @param $conn mysqli
 * @param $teamId int The team we want to remove
 * @param $tableId int The id of the table
 * @return void
 */
function removeTeamFromTable($conn, $teamId, $tableId){
    $removeTeam = $conn->prepare("
            DELETE FROM TABLE_TEAM
            where tableId=? and teamId=?
        ");

    $removeTeam->bind_param("ii", $tableId, $teamId);
    try {
        $removeTeam->execute();
    } catch (Exception $e){
        die($e->getMessage());
    }
}

/**
 * Gets the ids of all the teams that won the round
 *
 * @param $conn mysqli
 * @param $competId int The id of the competition
 * @param $tour int The number of the round
 * @return array|void
 */
function getRoundWinners($conn, $competId, $tour){
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
        // If there are no matches in the selected round, we stop the process
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
 * Gets all the tables present in a
 *
 * @param $conn mysqli
 * @param $competID int the id of the competition we wish to visualize
 * @return array an array of tables
 */
function getTablesForCompet($conn, $competID){
    $tablesRequest = $conn->prepare("
        SELECT t.tableId, t.competId, t.tour
        FROM competition c join `table` t on c.competId = t.competId
        where c.competId = ?
    ");
    $tablesRequest->bind_param("i", $competID);
    $tablesRequest->execute();
    $res = $tablesRequest->get_result();

    $tables = [];
    while ($row = $res->fetch_assoc()){
        $tables[] = $row;

    }
    return $tables;
}