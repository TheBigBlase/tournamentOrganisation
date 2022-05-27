<?php


/**
 * Checks if all the matches are finished and a winner has been chosen
 *
 * @param $conn mysqli
 * @param $competId
 * @return array|null
 */
function competHasFinished($conn, $competId){
    $isFinishedRequest = $conn->prepare("
        SELECT t.competId, nbteams=count(*)+1 as \"finished\"
        from `table` t
                        join match_t mt on t.tableId = mt.tableId
                        join
                        (
                            SELECT c2.competId, count(*) as \"nbteams\" from competition c2 join TEAM_COMPET TC on c2.competId = TC.competId
                            GROUP BY c2.competId
                        ) b on t.competId = b.competId
        WHERE t.competId = ?
        GROUP BY competId, nbteams
    ");
    $isFinishedRequest->bind_param("i", $competId);
    $isFinishedRequest->execute();
    $res = $isFinishedRequest->get_result();
    $arrResult = $res->fetch_assoc();
    if($arrResult == null){
        //die("Error in competHasFinished");
        return null;
    }
    return $arrResult["finished"];
}


/**
 * Gets the winner of the current competition
 *
 * @param $conn mysqli
 * @param $competId int The id of the competition
 * @return array|bool the winning team or false if no winner is found
 */
function getCompetitionWinner($conn, $competId){

    $winnerRequest = $conn->prepare("
        SELECT t2.teamId, t2.teamName
        from `table` t
            join TABLE_TEAM TT on t.tableId = TT.tableId
            join team t2 on TT.teamId = t2.teamId
        where t.competId = ?
        ORDER BY t.tour DESC
        LIMIT 1;
    ");
    $winnerRequest->bind_param("i", $competId);
    $winnerRequest->execute();
    $res = $winnerRequest->get_result();
    if ($res === false){
        die("Error in getCompetitionWinner");

        //return false;
    }
    return $res->fetch_assoc();
}