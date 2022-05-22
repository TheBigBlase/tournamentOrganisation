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
 * Get the name of the team
 * @param $conn mysqli
 * @param $teamId int The Id of the team
 * @return string the name of the team in question
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



