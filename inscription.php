<?php
require_once('inc/init.inc.php');


if(!empty($_POST)){





























}//fin if(!empty($_POST))






























/*********************Affichage****************************/
require_once('inc/haut.php');

echo $contenu;
?>

    <h2>S'inscrire</h2>
    <form method="post" id="inscription">
        <input type="text" name="pseudo" id="pseudo" placeholder="Votre pseudo" value="">
        <input type="text" name="mdp" id="mdp" placeholder="Votre mot de passe" value="">
        <input type="text" name="nom" id="nom" placeholder="Votre nom" value="">
        <input type="text" name="prenom" id="prenom" placeholder="Votre prenom" value="">
        <input type="text" name="email" id="email" placeholder="Votre email" value="">
        <select name="civilite" id="civilite">
            <option value="homme">Homme</option>
            <option value="femme">Femme</option>
        </select>

        <input type="submit" value="inscription">
        
    </form>

    
</body>
</html>

<?php

require_once('inc/bas.php');