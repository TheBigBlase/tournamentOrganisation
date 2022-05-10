<?php
include('connexion_db.php');

if(isset($_POST["nom_contact"])){
    echo '<strong>Thanks for you feedback !</strong>';
}
?>

<div>
    <a href="signinsignup.php">
        <span>Sign in / Sign up</span>
    </a>
</div>
<div>
    <h1>Contact</h1>
    <form method="POST">
    <label>
        Your name (Required) :
        <input type="text" name="nom_contact" required>
    </label>
    <label>
        Your email (Required) :
        <input type="email" name="email" required>
    </label>
    <label>
        Who are you ?
        <select name="user_type">
            <option value="">Student</option>
            <option value="">Staff</option>
            <option value="">Normie's</option>
        </select>
    </label>
        <input type="submit"  value="Send">
    </form>
</div>
