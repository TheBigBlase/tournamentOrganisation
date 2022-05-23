<?php
    include('../database/connexion_db.php');

    if(isset($_POST["nom_contact"])){
        echo '<strong>Thanks for you feedback !</strong>';
    }


if(isset($_GET["compet_id"])){
    $compet_id = $_GET['compet_id'];

    $sqlTournamentVue = "SELECT t2.teamName, t.tour from competition c join `table` t on c.competId = t.competId join TABLE_TEAM TT 
    on t.tableId = TT.tableId join team t2 on TT.teamId = t2.teamId where c.competId = $compet_id ORDER BY t.tour;";

    $resultSqlTournamentVue = mysqli_query($conn, $sqlTournamentVue) or die("Requête invalide: " . mysqli_error($conn) . "\n" . $sqlTournamentVue);
}
?>
<a href="index.php">
    INDEX
</a>

<div>
    <table>
        <caption>Team of the ongoing  tournaments</caption>
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
            $tour = $row['tour'];
            echo "<tr>";
                echo "<th>" .  $teamName  . "</th>";
                echo "<th>" .  $tour  . "</th>";
            echo "</tr>";
        }
        ?>
        </tbody>
    </table>
</div>
<br>
