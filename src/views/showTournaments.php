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





<!-- Tournament vue section -->
<section class="ongoing">
	<h1 class="title">Ongoing tournaments</h1>
	<div class="tournaments">

					<?php
					while ($row = mysqli_fetch_assoc($resultSqlTournamentVue)) {
							$competid = $row['competId'];
							$competName = $row['competName'];
							$description = $row["description"];
							$endInscription = $row['endInscription'];

							echo "<a href='index.php?page=competition&compet_id=$competid'\">";
								echo "<div class='tournament'>";
                echo "<span class='name-'><p class='title-cat'>name</p><h1>Chess Tournament</h1></span>";
                echo "<span class='descr'><p class='title-cat'>description</p><h1>Chess Tournament. MIN ELO : 1800</h1></span>";
                echo "<span class='end'><p class='title-cat'>end</p><h1>Sam 18 May.</h1></span>";
								echo "</div>";
							echo "</a>";
					}
					?>
	</div>
</section>
<section class="sep"></section>
