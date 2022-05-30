<?php

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
 * Get all the info of a team
 * @param $conn mysqli
 * @param $teamId int The Id of the team
 * @return array the name of the team in question
 */
function getTeam($conn, $teamId){
    $teamNameRequest = $conn->prepare("
        SELECT * From team where teamId=?;
    ");
    $teamNameRequest->bind_param("i", $teamId);
    $teamNameRequest->execute();
    $res = $teamNameRequest->get_result();
    return $res->fetch_assoc();
}

/**
 * Gets all the teams present in a certain competition
 *
 * @param $conn mysqli
 * @param $competId int the Id of te competition
 * @return array All the teams
 */
function getTeamsInCompetition($conn, $competId){
    $teamsRequest = $conn->prepare("
        SELECT t.teamName, t.teamId
        from TEAM_COMPET tt join team t on tt.teamId = t.teamId
        where competId = ?
    ");
    $teamsRequest->bind_param("i", $competId);
    $teamsRequest->execute();
    $res = $teamsRequest->get_result();
    if($res === false){
        return [];
    }
    $teams = [];
    while ($row = $res->fetch_assoc()){
        $teams[] = $row;
    }
    return $teams;
}

/**
 * @param $conn mysqli
 * @param $userId int
 * @return bool
 */
function playerHasTeam($conn, $userId){
    $userRequest = $conn->prepare("
        SELECT * from USER_TEAM 
        where userId = ? 
    ");
    $userRequest->bind_param("i", $userId);
    $userRequest->execute();
    $res = $userRequest->get_result();
    $result = $res->fetch_assoc();
    return !is_null($result);
}