<?php

/**
 * Creates all the necessary matches for the current table.
 * It only works when all data has been treated.
 *
 * @param $conn mysqli
 * @param $tableId int The id of the ongoing table
 * @return array|void All the matches that will be created
 */
function generateMatchesForTable($conn, $tableId){
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
    return $nextMatches;
}

/**
 * @param $conn mysqli
 * @param $tableId int
 * @return array
 */
function getMatchesFromTable($conn, $tableId){
    $matchesRequest = $conn->prepare("
        SELECT m.teamId1, m.teamId2, m.tableId, m.score1, m.score2
        from match_t m join `table` t on t.tableId = m.tableId
        where m.tableId = ?
    ");

    $matchesRequest->bind_param("i", $tableId);
    $matchesRequest->execute();
    $res = $matchesRequest->get_result();

    $matches = [];
    while($row = $res->fetch_assoc()){
        $matches[] = $row;
    }

    return $matches;
}

/**
 * Update the match scores
 *
 * @param $conn mysqli
 * @param $tableId int The id of the table
 * @param $teamId1 int The id of the first team
 * @param $teamId2 int The id of the second team
 * @param $score1 int The score of the first team
 * @param $score2 int The score of the second team
 * @return bool True if the changes have been successful
 */
function updateMatchScores($conn, $tableId, $teamId1, $teamId2, $score1, $score2){
    $updateMatches = $conn->prepare("
        UPDATE match_t
        Set score1 = ?, score2 = ?
        where tableId = ? and teamId1 = ? and teamId2 = ?
    ");
    $updateMatches->bind_param("iiiii", $score1, $score2, $tableId, $teamId1, $teamId2);
    return $updateMatches->execute();

}