

<?php
/** @var $conn mysqli */

// Inscription
if( isset($_POST['signup']))
{
    $ok = true;

    echo "<div class='error'> ";

    if(empty($_POST['user_firstname'])){
        $ok = false;
        echo "<p class='error'>The firstname must not be empty</p>";
    }
    if(empty($_POST['user_lastname'])){
        $ok = false;
        echo "<p class='error'>The lastname must not be empty</p>";
    }
    if(empty($_POST['user_mail'])){
        $ok = false;
        echo "<p class='error'>The email must not be empty</p>";
    }
    if(empty($_POST['user_type'])){
        $ok = false;
        echo "<p class='error'>The user type must completed</p>";
    }
    if(empty($_POST['user_mdp'])){
        $ok = false;
        echo "<p class='error'>The password must not be empty</p>";
    }

    if($_POST['user_mdp'] != $_POST['user_mdp_confirm']){
        $ok = false;
        echo "<p class='error'>The 2 passwords don't match</p>";
    }

    $user_type = intval($_POST['user_type']);

    if($user_type != 1 && $user_type != 2){
        $ok = false;
        echo "<p class='error'>User type ".$_POST['user_type']." not found </p>";
    }
    echo " </div>";

    if($ok){
        $sql = "INSERT INTO user(firstname, lastname, mail, password, idUT) VALUES(?, ?, ?, ?, ?)";
        $req = $conn->prepare($sql);
        $req->bind_param("ssssi", $_POST['user_firstname'], $_POST['user_lastname'], $_POST['user_mail'], $_POST['user_mdp'], $_POST['user_type']);
        try {
            $ok = $req->execute();
        }

        catch(Exception $e){
            die("<h3 class='error'> Error : ".$e->getMessage());
        }

        if($ok){
            echo "<h3 class='success'> You have now been registered </h3>";

        } else {
            echo "<h3 class='error'> This e-mail has already been used </h3>";
        }
    }

}

// Connection

elseif(!empty($_POST['user_mail_c']) && !empty($_POST['user_mdp_c']))
{
    $sql = "
            SELECT u.firstname, u.lastname, u.userId, uT.UTname, T.teamId
            FROM user u join userType uT on uT.idUT = u.idUT
                left join USER_TEAM T on u.userId = T.userId
            WHERE mail = ? AND password = ?
            
        ";
    $req = $conn->prepare($sql);
    $req->bind_param("ss",$_POST['user_mail_c'], $_POST['user_mdp_c']);
    $req->execute();

    $res = $req->get_result();
    $data = $res->fetch_assoc();
    if(empty($data)){
        echo "<p style='color:red'>Wrong mail / password</p>";
    }
    else{
        $_SESSION['ID'] = $data['userId'];
        $_SESSION['name'] = $data['firstname'] . " " . $data['lastname'];
        $_SESSION['type'] = $data["UTname"];
        $_SESSION['team'] = $data["teamId"];

        $req->close();
        $conn->close();
        header("Location: index.php");
        exit();
    }
}
?>
<section class="connect">
    <div class="signup">

        <h2>Sign up</h2>
        <form class="formform" action="index.php?page=signinup" method="post">
            <div>
                <input placeholder="Firstname" class="id" type="text" id="user_firstname" name="user_firstname" value="<?php if(!empty($_POST["user_firstname"])) echo $_POST["user_firstname"]; ?>">
            </div>
            <div>
                <input placeholder="Lastname" class="id" type="text" id="user_lastname" name="user_lastname" value="<?php if(!empty($_POST["user_lastname"])) echo $_POST["user_lastname"]; ?>">
            </div>
            <div class="playground">

                <label for="user_type">Who are you ?</label>

                <select name="user_type" id="user_type">
                    <option value="1" <?php if(!empty($_POST["user_type"]) && $_POST["user_type"]==1) echo "selected"; ?>>A student from this university</option>
                    <option value="2" <?php if(!empty($_POST["user_type"]) && $_POST["user_type"]==2) echo "selected"; ?>>A person outside the university</option>
                </select>
            </div>

            <div>
                <input placeholder="email@school.fr" class="id" type="email" id="user_mail" name="user_mail" value="<?php if(!empty($_POST["user_mail"])) echo $_POST["user_mail"]; ?>">
            </div>

            <div>
                <input placeholder="Password" class="pswd" type="password" id="user_mdp" name="user_mdp">
            </div>

            <div>
                <input placeholder="Confirm Password" class="pswd" type="password" id="user_mdp_confirm" name="user_mdp_confirm">
            </div>

            <input class="submit" type="submit" name="signup" value="Sign up">
        </form>
    </div>
    <div class="login">
    <h2>Sign in</h2>
    <form class="formform" action="index.php?page=signinup" method="post">
        <div>
            <input placeholder="email@school.fr" class="id" type="email" id="mail" name="user_mail_c" value="<?php if(!empty($_POST["user_mail_c"])) echo $_POST["user_mail_c"]; ?>">
        </div>
        <div>
            <input placeholder="Password" class="pswd" type="password" id="mdp" name="user_mdp_c">
        </div>

        <input class="submit" type="submit" name="signin" value="Sign in">
    </form>
    </div>
</section>