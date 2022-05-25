<?php
/** @var $conn mysqli */

$sql = "SELECT * FROM `competition` WHERE CURRENT_DATE <= competition.endInscription;";

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
            <?php
            if(isset($_SESSION['ID'])) {
                echo "<th>Register</th>";
            }
            ?>
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
            if(isset($_SESSION['ID'])) {
                echo "<td><a href='index.php?page=inscription&competid=$competid&competName=$competName&endInscription=$endInscription'> Register </a></td>";
            }
            echo "</a></tr>";
        }
        ?>
        </tbody>
    </table>
</div>
<br>

<?php

if(isset($_GET["competid"], $_GET["competName"],$_SESSION['ID'])){
    $id =  $_SESSION['ID'];

    $sql = "SELECT * from team where teamId = (select teamId from user_team where userId = $id);";
    $result = mysqli_query($conn, $sql) or die("Requête invalide: " . mysqli_error($conn) . "\n" . $sql);
    $row = mysqli_fetch_assoc($result);
    if($teamName = $row['teamName'] == null){
        header('Location: inscription_tournament.php?error_tournament=error_tournament');
        exit();
    };
    $teamName = $row['teamName'];
    $tournamentName = $_GET['competName'];
    $competid = $_GET['competid'];
    $teamId = $row['teamId'];
    echo "
    <div>
        <h1>Register form</h1>
        <form ACTION='index.php?page=inscription&form=form&team_id=$teamId&compet_id=$competid' METHOD='post'>
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
if(!isset($_SESSION['ID'])) {
    echo "If you want to access the register form you need to have an account !";
}
if(isset($_GET['error'])){
    echo 'Error, Your team is already registered';
}else{
    if(isset($_GET['compet_id'])){
        $id =  $_SESSION['ID'];
        $sql = "SELECT * from team where teamId = (select teamId from USER_TEAM where userId = $id);";
        $result = mysqli_query($conn, $sql) or die("Requête invalide: " . mysqli_error($conn) . "\n" . $sql);
        $row = mysqli_fetch_assoc($result);

        $team_Id = $row['teamId'];
        $compet_id = $_GET['compet_id'];

        $sqm_verif = "SELECT * FROM `TEAM_COMPET`";
        $x = true;
        $result_verif = mysqli_query($conn, $sqm_verif) or die("Requête invalide: " . mysqli_error($conn) . "\n" . $sqm_verif);
        while ($row_verif = mysqli_fetch_assoc($result_verif)) {
            if($row_verif['teamId'] == $team_Id and $row_verif['competId'] == $compet_id ){
                $x = false;
                header('Location: inscription_tournament.php?error=error');
                exit();
            }
        }
        if($x){
            $sqlInsert_teamCompet = "INSERT INTO `TEAM_COMPET` (`teamId`, `competId`) VALUES ($team_Id, $compet_id );";
            $result = mysqli_query($conn, $sqlInsert_teamCompet) or die("Requête invalide: " . mysqli_error($conn) . "\n" . $sqlInsert_teamCompet);
        }
        echo 'The form is send successfully, your team are now registered';
    }
}
    if(isset($_GET['error_tournament'])){
        echo "Error, Your don't have a team, you need to be in a team to register at this tournament !";
    }
?>







