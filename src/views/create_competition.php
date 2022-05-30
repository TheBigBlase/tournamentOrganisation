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
        $description = $_POST["description"];

        $createCompetRequest = $conn->prepare("
            INSERT INTO competition (competName, endInscription, pgId, description)
            VALUES (?, ?, ?, ?)
        ");
        $createCompetRequest->bind_param("ssis", $competName, $endInscription, $pgId, $description);
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

<!--BIG TITLE-->
<h2 class="big-title">Create a tournament</h2>

<!--FORM-->
<section class="form">
    <form class="formform" action="index.php?page=create_competition" method="post">
        <div class="name-c">
            <span>Competition name :</span><br>
            <input type="text" id="competName" name="competName" value="<?php if(isset($_POST["competName"])) echo $_POST["competName"]; ?>">
        </div>
        <div class="date">
            <span>End date of registration</span><br>
            <input type="datetime-local" id="endInscription" name="endInscription" value="<?php if(isset($_POST["endInscription"])) echo $_POST["endInscription"]; ?>">
        </div>
        <div class="descript">
            <span>Description</span><br>
            <textarea name="description" id="description" cols="30" rows="10"><?php if(isset($_POST["description"])) echo $_POST["description"]; ?></textarea>
        </div>
        <div class="playground">
            <span>Playground : </span><br>
            <select name="playground" id="playground">
                <?php

                $playgrounds = getAllPlaygrounds($conn);

                foreach ($playgrounds as $p){
                    ?>
                        <option value="<?php echo $p["pgId"]; ?>" <?php if(isset($_POST["playground"]) && $_POST["playground"]==$p["pgId"]) echo "selected"; ?>><?php echo $p["pgName"]; ?></option>
                    <?php
                }
                ?>
            </select>
        </div>
        <input id="submit" type="submit" name="createCompetitionForm" value="Create tournament">
    </form>
</section>

