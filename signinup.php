<?php 
	include('Header.php');

    // Inscription

    if(isset($_POST['user_pseudo']) && $_POST['user_pseudo'] != '' && isset($_POST['user_mail']) && $_POST['user_mail'] != '' && isset($_POST['user_mdp']) && $_POST['user_mdp'] != '')
    {
        $sql = "INSERT INTO user(name, mail, password) VALUES(?, ?, ?)";
        echo $sql;
        $req = $conn->prepare($sql);
        $req->bind_param("sss",$_POST['user_pseudo'], $_POST['user_mail'], $_POST['user_mdp']);
        $req->execute();

        $req->close();
        $conn->close();
    }

    // Connexion

    if(isset($_POST['user_mail_c']) && $_POST['user_mail_c'] != '' && isset($_POST['user_mdp_c']) && $_POST['user_mail_c'] != '')
    {
        $sql = "SELECT * FROM user WHERE mail = ? AND password = ?";
        echo $sql;
        $req = $conn->prepare($sql);
        $req->bind_param("ss",$_POST['user_mail_c'], $_POST['user_mdp_c']);
        $req->execute();

        $res = $req->get_result();
        $data = $res->fetch_assoc();
        
        $_SESSION['ID'] = $data['userId'];
        $_SESSION['name'] = $data['name'];

        echo $_SESSION['ID'];
        echo $_SESSION['name'];

        $req->close();
        $conn->close();
    }
	
?>

<h2>S'inscrire</h2>
<form action="signinup.php" method="post">
    <div>
        <label for="pseudo">Pseudo :</label>
        <input type="text" id="pseudo" name="user_pseudo">
    </div>
    <div>
        <label for="mail">e-mail&nbsp;:</label>
        <input type="email" id="mail" name="user_mail">
    </div>
    <div>
        <label for="mdp">Mot de passe :</label>
        <input id="mdp" name="user_mdp"></input>
    </div>

    <input type="submit" value="Envoyer le formulaire">
</form>

<h2>Lacrimatica</h2>
<form action="signinup.php" method="post">
    <div>
        <label for="mail">e-mail&nbsp;:</label>
        <input type="email" id="mail" name="user_mail_c">
    </div>
    <div>
        <label for="mdp">Mot de passe :</label>
        <input id="mdp" name="user_mdp_c"></input>
    </div>

    <input type="submit" value="Envoyer le formulaire">
</form>