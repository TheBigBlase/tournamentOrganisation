<?php
/** @var $conn mysqli */

if(isset($_POST["nom_contact"])){
    echo '<strong>Thanks for you feedback !</strong>';
}



$sqlTournamentVue = "
SELECT competName,endInscription,description, competId
FROM `competition`
ORDER BY endInscription desc;
";
$resultSqlTournamentVue = mysqli_query($conn, $sqlTournamentVue) or die("Requête invalide: " . mysqli_error($conn) . "\n" . $sqlTournamentVue);
?>


<!-- creation tournament redirect -->
<?php
if(isset($_SESSION["type"]) && $_SESSION["type"] == "admin"){
    ?>
    <div>
        <a href="index.php?page=create_competition"> Creation tournament </a>
    </div>
<?php
}


?>
<!-- inscription tournament redirect -->

<div>
    <a href="index.php?page=inscription"> Inscription tournament </a>
</div>

<!-- Tournament vue section -->
<div>
    <table>
        <caption>Ongoing tournaments</caption>
        <thead>
        <tr>
            <th>Tournament Name</th>
            <th>Tournament Description</th>
            <th>End inscription date</th>
        </tr>
        </thead>
        <tbody style=" ">
        <?php
        while ($row = mysqli_fetch_assoc($resultSqlTournamentVue)) {
            $competid = $row['competId'];
            $competName = $row['competName'];
            $description = $row["description"];
            $endInscription = $row['endInscription'];

            echo "<tr onmouseover='this.style.background=\"#0ff \"' onmouseout='this.style.background=\"#fff\"' onclick=\"location.href='index.php?page=competition&compet_id=$competid'\">";
            echo "<td>" .  $competName  . "</td>";
            echo "<td>" .  $description  . "</td>";
            echo "<td>" .  $endInscription  . "</td>";
            echo "</a></tr>";
        }
        ?>
        </tbody>
    </table>
</div>
<br>
<!-- Contact section -->
<div>
    <h1>Contact</h1>
    <form action="index.php" method="POST">
        <label>
            Your name (Required) :
            <input type="text" name="nom_contact" required>
        </label>
        <label>
            Your email (Required) :
            <input type="email" name="email" required>
        </label>
        <label>
            Who are you ?
            <select name="user_type">
                <option value="">Student</option>
                <option value="">Staff</option>
                <option value="">Normie's</option>
            </select>
        </label>
        <input type="submit"  value="Send">
    </form>
</div>
