<?php
include('Header.php');

if(isset($_POST["nom_contact"])){
    echo '<strong>Thanks for you feedback !</strong>';
}


$sqlTournamentVue = "SELECT competId,competName,endInscription FROM `competition`;";
$resultSqlTournamentVue = mysqli_query($conn, $sqlTournamentVue) or die("Requête invalide: " . mysqli_error($conn) . "\n" . $sqlTournamentVue);



?>


<!-- signup/signin section -->
<style>
    td{
        border: black 1px solid;
    }
</style>

<div>
    <a href="signinup.php">
        <span>Sign in / Sign up</span>
    </a>
</div>
<br>
<!-- Tournament vue section -->
<div>
    <table>
        <caption>Ongoing tournaments</caption>
        <thead>
        <tr>
            <th>Tournament id</th>
            <th>Tournament Name</th>
            <th>End inscription date</th>
        </tr>
        </thead>
        <tbody style=" ">
        <?php
        while ($row = mysqli_fetch_assoc($resultSqlTournamentVue)) {
            $competid = $row['competId'];
            $competName = $row['competName'];
            $endInscription = $row['endInscription'];
            echo "<tr onmouseover='this.style.background=\"#0ff \"' onmouseout='this.style.background=\"#fff\"' onclick=\"location.href='tournamentVue.php?compet_id=$competid'\">";
                echo "<td>".  $competid  . "</td>";
                echo "<td>" .  $competName  . "</td>";
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
    <h2>Contact</h2>
    <form method="POST">
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
