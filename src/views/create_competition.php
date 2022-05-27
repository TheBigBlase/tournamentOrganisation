<?php
/** @var $conn mysqli */
include "../controller/playgroundController.php";

// We must be an admin or a staff to be able to create a competition
if(isset($_SESSION["type"]) && $_SESSION["type"] != "admin" && $_SESSION["type"] != "staff"){
    header("Location: index.php");
    exit();
}

// Checking & Uploading the new competition
echo  "<p class='error'> ";
if (isset($_POST["createCompetitionForm"])){

    $ok = true;

    if(empty($_POST["competName"])){
        $ok = false;
        echo "You should give a name to the competition <br>";
    }

    $endInscription = false;
    if(empty($_POST["endInscription"])){
        $ok = false;
        echo "You should give an end date for the inscriptions <br>";
    } else {
        //$date = date("jS F, Y h:i:s A",strtotime($_POST["endInscription"]));
        $time = strtotime($_POST["endInscription"]);
        if($time === false){
            $ok = false;
            echo "Please enter a date with the right format <br>";
        }
        $endInscription = date("Y-m-d H:i:s",$time);
        $currentDate = date("Y-m-d H:i:s");
        if($endInscription < $currentDate && $time !== false){
            echo "This date is in the past";
        }
    }

    if(empty($_POST["playground"])){
        $ok = false;
        echo "You need to select a playground<br>";
    }


    if($ok){
        $competName = $_POST["competName"];
        $pgId = intval($_POST["playground"]);

        $createCompetRequest = $conn->prepare("
            INSERT INTO competition (competName, endInscription, pgId)
            VALUES (?, ?, ?)
        ");
        $createCompetRequest->bind_param("ssi", $competName, $endInscription, $pgId);
        if($createCompetRequest->execute()){
            $lastId = $conn->insert_id;
            header("Location: index.php?page=competition&compet_id=$lastId");
            exit();
        } else {
            echo "Couldn't create the competition<br>";
            var_dump($conn->error);
        }


    }
}
echo "</p>";
?>

<h2>Create a competition</h2>

<form action="index.php?page=create_competition" method="post">
    <p>
        <label for="competName">Competition name :</label>
        <input type="text" id="competName" name="competName" value="<?php if(isset($_POST["competName"])) echo $_POST["competName"]; ?>">
    </p>
    <p>
        <label for="endInscription">End date of registration</label>
        <input type="datetime-local" id="endInscription" name="endInscription" value="<?php if(isset($_POST["endInscription"])) echo $_POST["endInscription"]; ?>">
    </p>
    <p>
        <label for="playground">Playground : </label>
        <select name="playground" id="playground">
            <?php

            $playgrounds = getAllPlaygrounds($conn);

            foreach ($playgrounds as $p){
                ?>
                    <option value="<?php echo $p["pgId"]; ?>" <?php if(isset($_POST["playground"]) && $_POST["playground"]==$p["pgId"]) echo "selected"; ?>>"<?php echo $p["pgName"]; ?>"</option>
                <?php
            }
            ?>
        </select>
    </p>
    <input type="submit" name="createCompetitionForm" value="Create tournament">
</form>
