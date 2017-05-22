<?php


function internauteEstConnecte(){
    return(isset($_SESSION['membre']));
}

function internauteEstConnecteEtAdmim(){

    if(internauteEstConnecte() && $_SESSION['membre']['statut'] == 1){

        return true;       
    } else{
        
        return false;
    }
}



function executeRequete($req, $param = array()) { //$param est un arrray vide par default donc est optionnel

    //htmlspecialchars
    if(!empty($param)){//si  on a bien recu un array on le traite
        foreach($param as $indice => $valeur){
            $param[$indice] = htmlspecialchars($valeur, ENT_QUOTES); 
        }
    }

    //requete preparee:
    global $pdo; 
    $r = $pdo->prepare($req);
    $succes = $r->execute($param); //on execute la requete on lui passant  l array $param qui permet d associer chaque marqueur a sa valeur


    //Traitement erreur SQL
    if(!$succes){ //il y a une erreur sur la requete si $succes est false

        die('Erreur sur la requete SQL: ' . $r->errorInfo()[2]); //die arrete le script et affiche un message;  errorInfo renvoie un array avec un message d erreur stocker un indice 2
    }

    return $r;  //retourne un objet PSOStatement qui contient le resultat de la requete

} //Fin de la function