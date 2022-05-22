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

