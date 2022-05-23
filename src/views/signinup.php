<?php
/** @var $conn mysqli */
include('./header.php');
var_dump($_SESSION);
// Inscription
if(!empty($_POST['user_firstname']) &&
    !empty($_POST['user_lastname']) &&
    !empty($_POST['user_mail']) &&
    !empty($_POST['user_mdp']) &&
    !empty($_POST['user_mdp_confirm'])
)
{
    $ok = true;

    if($_POST['user_mdp'] != $_POST['user_mdp_confirm']){
        $ok = false;
    }

    $sql = "INSERT INTO user(firstname, lastname, mail, password, idUT) VALUES(?, ?, ?, ?, 3)";
    $req = $conn->prepare($sql);
    $req->bind_param("ssss", $_POST['user_firstname'], $_POST['user_lastname'], $_POST['user_mail'], $_POST['user_mdp']);
    try {
        $req->execute();
    }
    catch(Exception $e){
        die("<h3 style='color:red'> Error : ".$e->getMessage());
    }

    $req->close();
    $conn->close();
    echo "<h3 style='color:green'> You have now been registered </h3>";
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

        echo $_SESSION['name'];

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
        <label for="user_mail">e-mail&nbsp;:</label>
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
    <input type="submit" value="Sign up">
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

    <input type="submit" value="Sign in">
</form>