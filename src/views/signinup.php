<?php 
<<<<<<< HEAD
	include('../views/header.php');

=======
	include('./header.php');
>>>>>>> BACKindex
    // Inscription
	if(isset($_POST['user_firstname']) && $_POST['user_firstname'] != '' && 
	isset($_POST['user_lastname']) && $_POST['user_lastname'] != '' && 
	isset($_POST['user_mail']) && $_POST['user_mail'] != '' && 
	isset($_POST['user_mdp']) && $_POST['user_mdp'] != '')
    {
        $sql = "INSERT INTO user(firstname, lastname, mail, password, idUT) VALUES(?, ?, ?, ?, 3)";
        $req = $conn->prepare($sql);
        $req->bind_param("ssss", $_POST['user_firstname'], $_POST['user_lastname'], $_POST['user_mail'], $_POST['user_mdp']);
				try {
					$req->execute();
				}
				catch(Exception $e){
					die("<h3 color=red> Erreur : ".$e->getMessage());
				}

        $req->close();
        $conn->close();
				echo "<h3 color=green> Vous avez bien été enregistré. </h3>";
    }

    // Connexion

    elseif(isset($_POST['user_mail_c']) && $_POST['user_mail_c'] != '' && isset($_POST['user_mdp_c']) && $_POST['user_mail_c'] != '')
    {
        $sql = "SELECT * FROM user WHERE mail = ? AND password = ?";
        $req = $conn->prepare($sql);
        $req->bind_param("ss",$_POST['user_mail_c'], $_POST['user_mdp_c']);
        $req->execute();

        $res = $req->get_result();
        $data = $res->fetch_assoc();
        
        $_SESSION['ID'] = $data['userId'];
        $_SESSION['name'] = $data['firstname'] . " " . $data['lastname'];

        echo $_SESSION['name'];

        $req->close();
        $conn->close();
				echo "<h3 color=green> Vous etes connecté. </h3>";
    }
	
?>

<h2>S'inscrire</h2>
<form action="signinup.php" method="post">
    <div>
        <label for="prénom">prénom :</label>
        <input type="text" id="prenom" name="user_firstname">
    </div>
    <div>
        <label for="nom">nom :</label>
        <input type="text" id="nom" name="user_lastname">
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

<h2>Se connecter</h2>
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