<?php
    include('connexion_db.php');

    if(isset($_POST["nom_contact"])){
        echo '<strong>Thanks for you feedback !</strong>';
    }


if(isset($_GET["compet_id"])){
    $compet_id = $_GET['compet_id'];

    $sqlTournamentVue = "select team.teamName, `TEAM_POINTS`.points from team
               join `TEAM_POINTS` on `TEAM_POINTS`.`teamId` = team.`teamId` where team.teamId  in (
               select `TEAM_POINTS`.teamId from `TEAM_POINTS`
               join poule on poule.pouleId = `TEAM_POINTS`.pouleId
               join competition on $compet_id = poule.competId);";

    $resultSqlTournamentVue = mysqli_query($conn, $sqlTournamentVue) or die("Requête invalide: " . mysqli_error($conn) . "\n" . $sqlTournamentVue);
}
?>

<div>
    <table>
        <caption>Poule of #### tournaments</caption>
        <thead>
        <tr>
            <th>Tournament Name</th>
            <th>Tournament Points</th>
        </tr>
        </thead>
        <tbody>
        <?php
        while ($row = mysqli_fetch_assoc($resultSqlTournamentVue)) {
            $teamName = $row['teamName'];
            $points = $row['points'];
            echo "<tr>";
                echo "<th>" .  $teamName  . "</th>";
                echo "<th>" .  $points  . "</th>";
            echo "</tr>";
        }
        ?>
        </tbody>
    </table>
</div>
<br>
