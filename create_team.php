<section>
    <?php
    include('connexion_db.php');

    session_start();

    if(isset($_POST["createTeamForm"])){
        $ok = true;
        if(empty($_POST["name"])){
            echo "<p class='error'> Name must not be empty </p>";
            $ok = false;
        }
        $i = 0;
        foreach ($_POST as $key => $value){
            if(substr($key, 0, 3) == "plr") {
                $i++;
            }
        }
        if($i==0){
            echo "<p class='error'> You need at least 1 player </p>";
            $ok = false;
        }

        if($ok){
            $name = $conn->real_escape_string($_POST["name"]);
            $req = "INSERT INTO team (teamName) VALUES ('".$name."')";

            if($conn->query($req) === true){
                echo("<p> Uploaded successfully  </p>");
            }
            else{
                echo("<p> Error, can't upload this user </p>");
                var_dump($conn->error);
                $ok = false;
            }
            foreach ($_POST as $key => $value){
                if(substr($key, 0, 3) == "plr"){
                    $userId = $conn->real_escape_string($value);
                    $plrreq = "INSERT INTO USER_TEAM (userId, teamId) VALUES ($value, (SELECT teamId from team where teamName='$name'))";
                    if($conn->query($plrreq) === true){
                        echo("<p> Upload réussit </p>");
                    }
                    else{
                        echo("<p> Erreur </p>");
                        $conn->rollback();
                        var_dump($conn->error);
                        $ok = false;
                    }
                }
            }
        }
    }

    $user_req = "SELECT * from user";
    $users = mysqli_query($conn, $user_req) or die("Requête invalide: ". mysqli_error($conn)."\n".$user_req);
    ?>
    <h1>Create you own team</h1>
    <form action="create_team.php" method="post">
        <p>
            <label for="name">Name</label>
            <input type="text" name="name" id="name">
        </p>
        <p id="users">
            <label for="users">People in your teams</label>
            <select name="users" id="users">
                <?php
                while($row = mysqli_fetch_assoc($users)){
                    ?>
                    <option value="<?php echo $row["userId"];?>"><?php echo $row["firstname"]." ".$row["lastname"];?></option>
                    <?php
                }
                ?>
            </select>
            <button type="button" onclick="addUser()" >Add</button>
        </p>

        <p>
            <input type="submit" value="Créer" name="createTeamForm">
        </p>
    </form>
    <script>
        function addUser() {
            let usersTag = document.querySelector("#users");
            let selectTag = usersTag.querySelector("select");

            let valueSelected = selectTag.options[selectTag.selectedIndex].value

            let p = document.createElement("p");

            let label = document.createElement("label");
            label.innerText = selectTag.options[selectTag.selectedIndex].innerText;

            let newChild = document.createElement("input");
            newChild.value = valueSelected;
            newChild.id = valueSelected + "";
            newChild.name = "plr" + valueSelected;
            newChild.hidden = true;

            let deleteButton = document.createElement("button");
            deleteButton.type = "button";
            deleteButton.innerText = " Delete ";
            deleteButton.id;
            deleteButton.onclick = (e) => {
                let parent = e.target.parentNode;
                let input = parent.querySelector("input");
                let label = parent.querySelector("label");

                let newOption = document.createElement("option");
                newOption.value = input.value;
                newOption.innerText = label.innerText;

                let usersTag = document.querySelector("#users");
                let selectTag = usersTag.querySelector("select");

                selectTag.add(newOption)

                e.target.parentNode.remove();
            }

            let selectedOption = document.querySelector("option[value='"+valueSelected+"']");
            console.log(selectedOption);
            selectedOption.remove();

            p.appendChild(label);
            p.appendChild(newChild);
            p.appendChild(deleteButton);

            usersTag.appendChild(p);
        }
    </script>
</section>
