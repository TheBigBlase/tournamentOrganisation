<?php

/**
 * Gets all the playgrounds created
 *
 * @param $conn mysqli
 * @return array all the playgrounds
 */
function getAllPlaygrounds($conn){
    $playgroundsRequest = $conn->prepare("
        SELECT pgId, pgName
        FROM playground;
    ");
    $playgroundsRequest->execute();
    $res = $playgroundsRequest->get_result();

    if ($res === false){
        echo $conn->error;
        return [];
    }

    $playgrounds = [];
    while ($row = $res->fetch_assoc()){
        $playgrounds[] = $row;
    }

    return $playgrounds;
}