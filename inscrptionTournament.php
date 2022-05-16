<?php
include('connexion_db.php');
$sql = "SELECT * FROM `competition` WHERE CURRENT_DATE >= competition.endInscription;";

$result = mysqli_query($conn, $sql) or die("Requête invalide: " . mysqli_error($conn) . "\n" . $sql);

?>


<div>
    <table>
        <caption>Inscription tournaments</caption>
        <thead>
        <tr>
            <th>Tournament id</th>
            <th>Tournament Name</th>
            <th>End inscription date</th>
            <th>Register</th>
        </tr>
        </thead>
        <tbody style=" ">
        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            $competid = $row['competId'];
            $competName = $row['competName'];
            $endInscription = $row['endInscription'];
            echo "<tr>";
            echo "<td>".  $competid  . "</td>";
            echo "<td>" .  $competName  . "</td>";
            echo "<td>" .  $endInscription  . "</td>";
            echo "<td><a href='inscrptionTournament.php?competid=$competid&competName=$competName&endInscription=$endInscription'> Register </a></td>";
            echo "</a></tr>";
        }
        ?>
        </tbody>
    </table>
</div>
<br>

<?php

if(isset($_GET["competid"])){
    session_start();
    echo   $_SESSION['ID'];
    echo   $_SESSION['name'];

    //$sql = "select *  from ";
}

?>