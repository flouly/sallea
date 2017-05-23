<?php
require_once('inc/init.inc.php');

$inscription = false;
//print_r($_POST);

if(!empty($_POST)){

            if(strlen($_POST['pseudo']) < 4 || strlen($_POST['pseudo']) > 20){

                $contenu .= '<p>Le pseudo doit contenir au moins 4 caracteres<p>';
            }

             if(strlen($_POST['mdp']) < 4 || strlen($_POST['mdp']) > 60){

                $contenu .= '<p>Le mot de passe doit contenir au moins 4 caracteres</p>';
            }

                      if(strlen($_POST['nom']) < 4 || strlen($_POST['nom']) > 20){

                $contenu .= '<p>Le nom doit contenir au moins 4 caracteres</p>';
            }


             if(strlen($_POST['prenom']) < 4 || strlen($_POST['prenom']) > 20){

                $contenu .= '<p>Le prenom doit contenir au moins 4 caracteres</p>';
            }

            if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){

                $contenu .= '<p>Email est invalide </p>';
            }

              if($_POST['civilite'] != 'm' && $_POST['civilite'] != 'f'){
                $contenu .= '<p>La civilite est incorrecte</p>';
            }

            if(empty($contenu)){


                $membre = executeRequete("SELECT id_membre FROM membre  WHERE pseudo = :pseudo", array(':pseudo' => $_POST['pseudo']));

                if($membre->rowCount() > 0){

                    $contenu .= '<p>Le pseudo est deja pris</p>';

                } else {

                    $_POST['mdp'] = md5($_POST['mdp']);

                    executeRequete("INSERT INTO membre (pseudo, mdp, nom, prenom, email, civilite, statut, date_enregistrement) VALUES (:pseudo, :mdp, :nom, :prenom, :email, :civilite, 0, NOW())", array(
                        ':pseudo' => $_POST['pseudo'], 
                        ':mdp' => $_POST['mdp'], 
                        ':nom' => $_POST['nom'], 
                        ':prenom' => $_POST['prenom'], 
                        ':email' => $_POST['email'], 
                        ':civilite' => $_POST['civilite']
                        )
                       );


                    $contenu .= '<p>vous avez ete enregistre</p>';
                    $inscription = true;
                }






            }


}//fin if(!empty($_POST))


/*********************Affichage****************************/
require_once('inc/haut.inc.php');

echo $contenu;
if(!$inscription) :
?>

    <h2>S'inscrire</h2>
    <form method="post" id="inscription">
        <input type="text" name="pseudo" id="pseudo" placeholder="Votre pseudo" value="">
        <input type="text" name="mdp" id="mdp" placeholder="Votre mot de passe" value="">
        <input type="text" name="nom" id="nom" placeholder="Votre nom" value="">
        <input type="text" name="prenom" id="prenom" placeholder="Votre prenom" value="">
        <input type="text" name="email" id="email" placeholder="Votre email" value="">
        <select name="civilite" id="civilite">
            <option value="m">Homme</option>
            <option value="f">Femme</option>
        </select>

        <input type="submit" value="inscription">
        
    </form>

    


<?php
endif;
require_once('inc/bas.inc.php');