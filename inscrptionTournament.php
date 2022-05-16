<?php
include('connexion_db.php');
$sql = "SELECT * FROM `competition` WHERE CURRENT_DATE <= competition.endInscription;";

$result = mysqli_query($conn, $sql) or die("Requête invalide: " . mysqli_error($conn) . "\n" . $sql);
if(session_status() === PHP_SESSION_NONE) session_start();

echo session_status();
?>



<a href="index.php">
    INDEX
</a>

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
if(isset($_GET["competid"], $_GET["competName"])){
    $id =  $_SESSION['ID'];
    //echo   $_SESSION['name'];

    $sql = "SELECT * from team where teamId = (select teamId from user_team where userId = $id);";
    $result = mysqli_query($conn, $sql) or die("Requête invalide: " . mysqli_error($conn) . "\n" . $sql);
    $row = mysqli_fetch_assoc($result);
    if($teamName = $row['teamName'] == null){
        header('Location: inscrptionTournament.php?error_tournament=error_tournament');
    };
    $teamName = $row['teamName'];
    $tournamentName = $_GET['competName'];
    $competid = $_GET['competid'];
    $teamId = $row['teamId'];
    echo "
    <div>
        <h1>Register form</h1>
        <form ACTION='inscrptionTournament.php?form=form&team_id=$teamId&compet_id=$competid' METHOD='post'>
            <label>
                Your current team :
                <input type='text' value='$teamName' disabled>
            </label>
            <label>
                The tournament you selected :
                <input type='text' value='$competName' disabled>
            </label>
            <input type='submit' value='Send'>
        </form>
    </div>
    ";
}

if(isset($_GET['compet_id'])){
    session_start();
    $id =  $_SESSION['ID'];
    $sql = "SELECT * from team where teamId = (select teamId from user_team where userId = $id);";
    $result = mysqli_query($conn, $sql) or die("Requête invalide: " . mysqli_error($conn) . "\n" . $sql);
    $row = mysqli_fetch_assoc($result);

    $team_Id = $row['teamId'];
    $compet_id = $_GET['compet_id'];

    $sqm_verif = "SELECT * FROM `team_compet`";
    $result_verif = mysqli_query($conn, $sqm_verif) or die("Requête invalide: " . mysqli_error($conn) . "\n" . $sqm_verif);
    while ($row_verif = mysqli_fetch_assoc($result_verif)) {
        if($row_verif['teamId'] == $team_Id and $row_verif['competId'] == $compet_id ){
            header('Location: inscrptionTournament.php?error=error');
        }
    }


    $sqlInsert = "INSERT INTO `team_compet` (`teamId`, `competId`) VALUES ($team_Id, $compet_id );";
    $result = mysqli_query($conn, $sqlInsert) or die("Requête invalide: " . mysqli_error($conn) . "\n" . $sqlInsert);

    echo 'The form is send successfully, your team are now registered';
}
if(isset($_GET['error'])){
    echo 'Error, Your team is already registered';
}
if(isset($_GET['error_tournament'])){
    echo "Error, Your don't have a team, you need to be in a team to register at this tournament !";
}


?>







