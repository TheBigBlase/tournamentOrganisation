<?php
session_start();

include('connexion_db.php');

if(isset($_POST['user_pseudo']) && isset($_POST['user_mail']) && isset($_POST['user_mdp']))
{
    $sql = "INSERT INTO user(pseudo, mail, mdp) VALUES(" . $_POST['user_pseudo'] . "," . $_POST['user_mail'] . "," . $_POST['user_mdp'] .")";
    echo $sql;

}

?>

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