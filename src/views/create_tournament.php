<?php 
    include('../database/connexion_db.php');

    if(isset($_POST['tournament_name']) && $_POST['tournament_name'] != ''
    && isset($_POST['end_inscription']) && $_POST['end_inscription'] != ''
    && isset($_POST['pg']))
    {
        $sql = "INSERT INTO competition(competName, endInscription, pgId) VALUES(?, ?, ?)";
        $req = $conn->prepare($sql);

        $req->bind_param("ssi", $_POST['tournament_name'], $_POST['end_inscription'], $_POST['pg']);
        try {
            $req->execute();
            echo "<h3 color=green> Vous avez bien été enregistré. </h3>";
        }
        catch(Exception $e){
            die("<h3 color=red> Erreur : ".$e->getMessage());
        }

        $req->close();
    }
?> 
 
<form method="post"> 
    <label> 
        Le nom du tournois mdrrr (*) :
        <input type="text" name="tournament_name" required> 
    </label>

    <label> 
        La data de fin d'inscription (*) :
        <input type="date" name="end_inscription" required>
    </label>

    <label for="pg">Terrain :</label>
    <select name="pg" id="pg">
    <?php
        $query = 'SELECT * FROM playground ORDER BY pgName ASC';
        $res = mysqli_query($conn, $query) or die("Requête invalide: " . mysqli_error($conn) . "\n" . $query);
        while($row = mysqli_fetch_assoc($res)){
    ?>
        <option value="<?php echo $row["pgId"];?>"><?php echo $row["pgName"];?></option>
    <?php
        }
    ?>
    </select>

    <input type="submit"  value="Send">
</form>