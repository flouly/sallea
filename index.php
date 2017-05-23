<?php
require_once('inc/init.inc.php');

$categorie_des_produits = executeRequete("SELECT DISTINCT categorie FROM salle");

     $contenu_gauche .= '<p>Categorie</p>';

        while($cat = $categorie_des_produits->fetch(PDO::FETCH_ASSOC)){
                 $contenu_gauche .= '<a href="?categorie='. $cat['categorie'] .'">'. $cat['categorie'] .'</a>';
        }

$liste_des_villes = executeRequete("SELECT DISTINCT ville FROM salle");

    $contenu_gauche .= '<p>Ville</p>';

        while($ville = $liste_des_villes->fetch(PDO::FETCH_ASSOC)){
                $contenu_gauche .= '<a href="?ville='. $ville['ville'] .'">'. $ville['ville'] .'</a>';
        }


    $contenu_gauche .= '<form method="post">';

    $contenu_gauche .= '<label for="capacite">Capacite</label>';

$capacite = executeRequete("SELECT DISTINCT capacite FROM salle ");
    $contenu_gauche .= '<select name="capacite" id="capacite">';

                while($cap = $capacite->fetch(PDO::FETCH_ASSOC)){

                    $contenu_gauche .= '<option>'. $cap['capacite'] .'</option>';
                }


    $contenu_gauche .= '</select>';

    $contenu_gauche .= '</form>';

if(isset($_GET['categorie'])){

        $donnees = executerequete("SELECT * FROM salle, produit WHERE salle.id_salle = produit.id_salle AND categorie = :categorie ORDER BY produit.date_arrivée", array(':categorie'=> $_GET['categorie']));        

} else {
    $donnees = executerequete("SELECT salle.titre, salle.description, salle.photo, produit.prix, produit.date_arrivée, produit.date_depart FROM salle, produit WHERE salle.id_salle = produit.id_salle  ORDER BY produit.date_arrivée");

}


    while($produit = $donnees->fetch(PDO::FETCH_ASSOC)){

        debug($produit);

    }








     
















require_once('inc/haut.inc.php');
echo $contenu_gauche;

echo $contenu_droit;




