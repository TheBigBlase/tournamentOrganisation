<?php
/** @var $conn mysqli */
include('./header.php');
var_dump($_SESSION);
// Inscription
if( isset($_POST['signup']))
{
    $ok = true;

    echo "<div class='error'> ";

    if(empty($_POST['user_firstname'])){
        $ok = false;
        echo "<p>The firstname must not be empty</p>";
    }
    if(empty($_POST['user_lastname'])){
        $ok = false;
        echo "<p>The lastname must not be empty</p>";
    }
    if(empty($_POST['user_mail'])){
        $ok = false;
        echo "<p>The email must not be empty</p>";
    }
    if(empty($_POST['user_type'])){
        $ok = false;
        echo "<p>The user type must completed</p>";
    }
    if(empty($_POST['user_mdp'])){
        $ok = false;
        echo "<p>The password must not be empty</p>";
    }

    if($_POST['user_mdp'] != $_POST['user_mdp_confirm']){
        $ok = false;
        echo "<p>The 2 passwords don't match</p>";
    }

    $user_type = intval($_POST['user_type']);

    if($user_type != 1 && $user_type != 2){
        $ok = false;
        echo "<p>User type ".$_POST['user_type']." not found </p>";
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
            die("<h3 style='color:red'> Error : ".$e->getMessage());
        }

        if($ok){
            echo "<h3 style='color:green'> You have now been registered </h3>";

        } else {
            echo "<h3 style='color:red'> This e-mail has already been used </h3>";
        }
    }

}

// Connection

elseif(!empty($_POST['user_mail_c']) && !empty($_POST['user_mdp_c']))
{
    $sql = "
            SELECT u.firstname, u.lastname, u.userId, uT.UTname
            FROM user u join userType uT on uT.idUT = u.idUT
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

        $req->close();
        $conn->close();
        echo "<h3 style='color:green'> You are now connected </h3>";
    }
}
?>

<h2>Sign up</h2>
<form action="signinup.php" method="post">
    <div>
        <label for="user_firstname">Firstname :</label>
        <input type="text" id="user_firstname" name="user_firstname">
    </div>
    <div>
        <label for="user_lastname">Lastname :</label>
        <input type="text" id="user_lastname" name="user_lastname">
    </div>
    <div>
        <label for="user_type">Who are you ?</label>
        <select name="user_type" id="user_type">
            <option value="1" selected>A student from this university</option>
            <option value="2" >A person outside the university</option>
        </select>
    </div>
    <div>
        <label for="user_mail">e-mail :</label>
        <input type="email" id="user_mail" name="user_mail">
    </div>

    <div>
        <label for="user_mdp">Password :</label>
        <input type="password" id="user_mdp" name="user_mdp">
    </div>

    <div>
        <label for="user_mdp_confirm">Confirm password :</label>
        <input type="password" id="user_mdp_confirm" name="user_mdp_confirm">
    </div>

    <input type="submit" name="signup" value="Sign up">
</form>

<h2>Sign in</h2>
<form action="signinup.php" method="post">
    <div>
        <label for="mail">e-mail :</label>
        <input type="email" id="mail" name="user_mail_c">
    </div>
    <div>
        <label for="mdp">Password :</label>
        <input id="mdp" name="user_mdp_c">
    </div>

    <input type="submit" name="signin" value="Sign in">
</form>