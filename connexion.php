<?php
require_once('inc/init.inc.php');

if(isset($_GET['action'])  && $_GET['action'] == 'deconnexion'){
   session_destroy();
   header('location:index.php');
   exit();
}


if (internauteEstConnecte()){

    header('location:profil.php');
}

if(!empty($_POST)){
    if(empty($_POST['pseudo'])){
       $contenu .= '<p>Mettez votre pseudo</p>';
    }

    if(empty($_POST['mdp'])){
        $contenu .=  '<p>Un mot de passe est requis</p>';
    }

    if(empty($contenu)){
        $mdp = md5($_POST['mdp']);

        $resultat = executeRequete("SELECT * FROM membre   WHERE mdp = :mdp AND pseudo = :pseudo", array(':mdp'=>$mdp, ':pseudo'=>$_POST['pseudo']));

        if($resultat->rowCount() != 0){

            $membre = $resultat->fetch(PDO::FETCH_ASSOC);

            $_SESSION['membre'] = $membre;
                header('location:profil.php');
                exit();//pour arreter le code sinon meme apres la redirection le code continu a etre execute
        } else{
                $contenu .= '<p>Votre mot de passe est errone</p>';
        }

    }

}













//Affichage
require_once('inc/haut.inc.php');
echo $contenu;
?>

<h3>Veuillez renseigner vos identifiants pour vous connecter</h3>

<form method="post" action="">

    <label for="pseudo">Pseudo</label><br>
    <input type="text" id="pseudo"   name="pseudo" value=""    ><br><br>


    <label for="mdp">Mot de passe</label><br>
    <input type="password" id="mdp"   name="mdp"  value=""   ><br><br>

     <input type="submit"   value="Se connecter"  class="btn"   >

</form>

<?php
require_once('inc/bas.inc.php');