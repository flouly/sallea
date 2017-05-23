<?php
require_once('inc/init.inc.php');


// ---------------------------------TRAITEMENT--------------------------
// 1- vérification ADMIN
if (!internauteEstConnecteEtEstAdmin()) {
    header('location:../connexion.php'); // si membre pas ADMIN, alors on le redirige vers la page de connexion qui est à la racine du site (en dehors du dossier admin)
    exit();
    
    
}







//  4- Enregistrement du produit en BDD
if ($_POST) { //  équivalent à !empty($_POST) car si le $_POST est rempli, il vaut TRUE = formulaire posté

    // ici il faudrait mettre les contrôles sur le formulaire

    $photo_bdd = '';
    //  la photo subit un traitement spécifique en BDD. Cette variable contiendra son chemin d'accès


    //  9- Modification de la photo (suite) :
    if (isset($_GET['action']) && $_GET['action'] == 'modification') {
        // si je suis en modification, je mets en base la photo du champ hidden photo_actuelle du formulaire :
        $photo_bdd = $_POST['photo_actuelle'];
    }




    //  5- Traitement de la photo :
    // echo '<pre>'; print_r($_FILES); echo '</pre>';
    if (!empty($_FILES['photo'] ['name'])) {  //  si une image a été uploadée, $_FILES est remplie

    //  on constitue un nom unique pour le fichier photo :
    $nom_photo = $_POST['reference'] . '_' . $_FILES['photo'] ['name'];

    //  on constitue le chemin de la photo enregistrée en BDD :
    $photo_bdd = RACINE_SITE . 'photo/' . $nom_photo;
    //  on obtient ici le nom et le chemin de la photo depuis la racine du similar_text

    //  on constitue le chemin absolu complet de la photo depuis la racine serveur:
    $photo_dossier = $_SERVER['DOCUMENT_ROOT'] . $photo_bdd;

    // echo '<pre>'; print_r($photo_dossier); echo '</pre>';

    // Enregistrement du fichier photo sur le serveur :
    copy($_FILES['photo'] ['tmp_name'], $photo_dossier); //  on copie le fichier temporaire de la photo stockée au chemin indiqué par $_FILES['photo'] ['tmp_name'] dans le chemin $photo_dossier de notre serveur
    

       
    }



    //  4- suite de l'enregistrement en BDD : 
    executeRequete("REPLACE INTO produit (id_salle, titre, description, photo, pays, ville, adresse, cp, capacite, categorie, actions) VALUES (:id_salle, :titre, :description, :photo, :pays, :ville, :adresse, :cp, :capacite, :categorie, :actions)", array('id_salle' => $_POST['id_salle'], 'titre' => $_POST['titre'], 'description' => $_POST['description'], 'photo' => $photo_bdd, 'pays' => $_POST['pays'], 'ville' => $_POST['ville'], 'adresse' => $_POST['adresse'], 'cp' => $_POST['cp'], ':capacite' => $_POST['capacite'], 'categorie' => $_POST['categorie'], 'actions' => $_POST['actions'] ));

    $contenu .= '<div class="bg-success">Le produit a été enregistré</div>';
    $_GET['action'] = 'affichage';
    // on met la valeur 'affichage' dans $_GET['action'] pour afficher automatiquement la table HTML des produits plus loin dans le script (point 6)
    
    

}

// 2- Les liens "Affichage" et "ajout d'un produit" :
$contenu .= '<ul class="nav nav-tabs">
                <li><a href="?action=affichage">Affichage des produits</a></li>
                <li><a href="?action=ajout">Ajout d\'un produit</a></li>
            </ul>';


// 6- Affichage des produits dans le back-office:
if (isset($_GET['action']) && $_GET['action'] == 'affichage' || !isset($_GET['action'])) {
    // si $_GET contient affichage ou que l'on arrive sur la page la 1ere fois $_GET['action'] n'existe pas

    $resultat = executeRequete("SELECT * FROM produit"); //  on selectionne tous les produits
    

    $contenu .= '<h3>Affichage des produits</h3>';
    $contenu .= '<p>Nombre de produit(s) dans la boutique: ' . $resultat->rowcount() . '</p>';

    $contenu .= '<table class="table">';
    //  la ligne des entêtes
    $contenu .= '<tr>';
        for($i = 0; $i < $resultat->columnCount(); $i++)  {
            $colonne = $resultat->getColumnMeta($i);
            // echo '<pre>'; print_r($colonne); echo '</pre>';
            $contenu .= '<th>' . $colonne['name'] . '</th>'; // getColumnMeta() retourne un array contenant notamment l'indice name contenant le nom de la colonne
            
        }

        $contenu .= '<th>Action</th>'; //on ajoute une colonne "action"
    $contenu .= '</tr>';

    //  Affichage des lignes :
    while ($ligne = $resultat->fetch(PDO::FETCH_ASSOC)) {
        $contenu .= '<tr>';
            // echo '<pre>'; print_r($ligne); echo '</pre>';
            foreach($ligne as $index => $data) { //$index réceptionne les indices, $data les valeurs
                if ($index == 'photo') {
                    $contenu .= '<td><img src="'. $data .'" width="70" height="70"></td>';

                } else {
                    $contenu .= '<td>' . $data . '</td>';
                }

                
        
            }

            $contenu .= '<td>
                            <a href="?action=modification&id_produit='. $ligne['id_produit'] .'">modifier</a> /
                            <a href="?action=suppression&id_produit='. $ligne['id_produit'] .'" onclick="return(confirm(\'Etes-vous certain de vouloir supprimer ce produit ? \'));"  >supprimer</a>
                        </td>';
        $contenu .= '</tr>';
    }


$contenu .= '</table>';


}








// -------------------------------AFFICHAGE------------------------------
require_once('inc/haut.inc.php');
echo $contenu;

// 3- Formulaire HTML
if (isset($_GET['action']) && ($_GET['action'] == 'ajout' || 
$_GET['action'] == 'modification')) :
//  Si on a demandé l'ajout d'un produit ou sa modification, on affiche le formulaire :



    // 8- Formulaire de modification avec présaisie des infos dans le formulaire:
    if (isset($_GET['id_produit'])) {
        // Pour préremplir le formulaire, on requête en BDD les infos du produit passé dans l'url:
        $resultat = executeRequete("SELECT * FROM produit WHERE id_produit = :id_produit", array(':id_produit' => $_GET['id_produit']));

        $produit_actuel = $resultat->fetch(PDO::FETCH_ASSOC); //  pas de while car qu'un seul produit

        
    }



?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Ajouter un contact</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>

    

<div class="container">
    <h1>Backoffice/Gestion des salles</h1>
<table class="table table-bordered table-striped table-condensed table-hover table-responsive">
    <tr>
        <th>id_salle</th>
        <th>titre</th>
        <th>description</th>
        <th>photo</th>
        <th>pays</th>
        <th>ville</th>
        <th>adresse</th>
        <th>code postal</th>
        <th>capacité</th>
        <th>catégorie</th>
        <th>actions</th>
    </tr>

    <tr>
        <td>1</td>
        <td>bureau de passage</td>
        <td><p>Un bureau équipé disponible quand vous le souhaitez pour :
            un rendez-vous,
            une mini réunion,
            recevoir un client, un fournisseur,
            organiser des sessions de recrutement
            ou tout simplement pour travailler dans un environnement professionnel...</p></td>
        <td><img src="/img/bureau de passage.jpg" alt="bureau de passage"></td>
        <td>France</td>
        <td>Paris</td>
        <td>4 Avenue de la grande Armée</td>
        <td>75017</td>
        <td>2</td>
        <td>reunion</td>
        <td><i class="fa fa-search" aria-hidden="true"><i class="fa fa-trash-o" aria-hidden="true"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></i></i></td>
    </tr>

    <tr>
        <td>2</td>
        <td>Salle ovale</td>
        <td><p>Facilement modulable
            en U : maxi 14 personnes
            en classe : 10 personnes
            en table centrale : 12 personnes
            en théâtre/conférence : jusqu'à 25 personnes
            Splendide salle sous les toits, offrant près de 45m² d'espace
            En théâtre, pour des conférence de presse, des lancements de produits.
            Sans mobilier, pour des ventes privées, des show rooms et l'organisation de cocktails professionnels.</p></td>
        <td><img src="/img/salle ovale.jpg" alt="salle ovale"></td>
        <td>France</td>
        <td>Paris</td>
        <td>4 Avenue de la grande Armée</td>
        <td>75017</td>
        <td>10</td>
        <td>formation</td>
        <td><i class="fa fa-search" aria-hidden="true"><i class="fa fa-trash-o" aria-hidden="true"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></i></i></td>
    </tr>

    <tr>
        <td>3</td>
        <td>Salle Louvre</td>
        <td><p>Facilement modulable
            en U : maxi 14 personnes
            en classe : 10 personnes
            en table centrale : 12 personnes
            en théâtre/conférence : jusqu'à 25 personnes
            Salle climatisée et à la lumière du jour
            Idéale pour : formation, réunion de conseil...
            En théâtre, pour des conférence de presse, des lancements de produits.
            Sans mobilier, pour des ventes privées, des show rooms et l'organisation de cocktails professionnels.</p></td>
        <td><img src="/img/salle louvre.jpg" alt="salle louvre"></td>
        <td>France</td>
        <td>Paris</td>
        <td>4 Avenue de la grande Armée</td>
        <td>75017</td>
        <td>14</td>
        <td>reunion</td>
        <td><i class="fa fa-search" aria-hidden="true"><i class="fa fa-trash-o" aria-hidden="true"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></i></i></td>
    </tr>

    <tr>
        <td>4</td>
        <td>Salle conférence</td>
        <td><p>Vous ne disposez peut-être pas de salles de réunion à Paris adaptées ou celles-ci ne sont pas disponibles. Pour une occasion très spéciale vous avez un besoin spécifique de proposer un accueil de bonne qualité aux participants à votre réunion.
        Que vous deviez débattre, négocier ou décider, vous exigez d’être dans de bonnes conditions de travail et il est important de le faire dans un cadre qui le permet.
        Notre centre d’affaires et de conférences, avec son offre de salle de réunion à Lyon est là pour vous accueillir ! Des solutions adaptées vous sont proposées suivant votre demande et vos exigences, comme par exemple de disposer d’un lieu qui respectera la confidentialité de votre réunion en vous proposant un espace fermé, cloisonné et sécurisé !
        Choisir un lieu stratégique pour vos salles de réunion à Lyon
        Le lieu de la réunion est primordial. Il doit être choisi avec soin en tenant compte de différents paramètres : le nombre de participants, la disposition de la salle, l’équipement de la salle…
        Nos centres d’affaires peuvent accueillir l’ensemble de vos événements grâce à leurs espaces modulables : réunions, tables-rondes, assemblées, colloques, conseils d’administration, rencontres d’affaires, formations, etc.
        Notre équipe professionnelle met en place et équipe votre salle réunion selon vos souhaits et s’occupe de l’accueil de vos invités. Vous bénéficiez ainsi d’une prestation complète et adaptée !</p></td>
        <td><img src="/img/salle conference.jpg" alt="salle conference"></td>
        <td>France</td>
        <td>Lyon</td>
        <td>57 rue Président Edouard Herriot</td>
        <td>69002</td>
        <td>30</td>
        <td>reunion</td>
        <td><i class="fa fa-search" aria-hidden="true"><i class="fa fa-trash-o" aria-hidden="true"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></i></i></td>
    </tr>

    <tr>
        <td>5</td>
        <td>Salle de réunion prestige</td>
            <td><p>Salle de réunion  prestigieuse 8- 14 personnes 3 fenêtres sur le boulevard Saint Germain entièrement équipée en vidéo HD ( 2 écrans 150 cm + retroprojecteur HD ). Parquet-Moulures 4.00 m sous plafond Immeuble bourgeois en étage Ascenseur Kitchenette et vaisselle pour collation / Nespresso avec entrée partagée bureaux de chercheurs.</p></td>
        <td><img src="/img/salle prestige.jpg" alt="salle prestige"></td>
        <td>France</td>
        <td>Lyon</td>
        <td>57 rue Président Edouard Herriot</td>
        <td>69002</td>
        <td>15</td>
        <td>reunion</td>
        <td><i class="fa fa-search" aria-hidden="true"><i class="fa fa-trash-o" aria-hidden="true"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></i></i></td>
    </tr>

    <tr>
        <td>6</td>
        <td>Salle conseil administration</td>
        <td><p>La salle de réunion prestigieuse à louer est adaptée pour un conseil                 d'administration, présentation des produits aux clients importants ou une           réunion des équipes de direction. Elle est  lumineuse  et dominer la vue de         Champs Elysées. Vos rendrez -vous vont avoir des succès !</p></td>
        <td><img src="/img/salle conseil d administration.jpg" alt="salle conseil d administration"></td>
        <td>France</td>
        <td>Paris</td>
        <td>57 rue Président Edouard Herriot</td>
        <td>69002</td>
        <td>20</td>
        <td>bureau</td>
        <td><i class="fa fa-search" aria-hidden="true"><i class="fa fa-trash-o" aria-hidden="true"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></i></i></td>
    </tr>

    <tr>
        <td>7</td>
        <td>Salle amphithéâtre Adenauer</td>
        <td><p>Amphithéâtre de 283 places en gradins, extensible jusqu’à 500 places (avec       chaises en parterre) et doté de 2 galeries latérales de 200 m² chacune qui          peuvent accueillir stands, expositions ou prestations de restauration.
 
            Sonorisation, écran et régisseur technique (obligatoire).</p></td>
        <td><img src="/img/salle amphitheatre adenauer.jpg" alt="salle amphitheatre adenauer"></td>
        <td>France</td>
        <td>Paris</td>
        <td>4 Avenue de la grande Armée</td>
        <td>75017</td>
        <td>65</td>
        <td>formation</td>
        <td><i class="fa fa-search" aria-hidden="true"><i class="fa fa-trash-o" aria-hidden="true"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></i></i></td>
    </tr>

    <tr>
        <td>8</td>
        <td>Salon Gulbekian</td>
        <td><p>Doté d’une vue sur la cour d’honneur de la Cité internationale, il peut accueillir jusqu’à 83 personnes en conférence. Il est équipé d’un écran.</p></td>
        <td><img src="/img/salon gulbekian.jpg" alt="salon gulbekian"></td>
        <td>France</td>
        <td>Marseille</td>
        <td>3 place aux Huiles</td>
        <td>13001</td>
        <td>80</td>
        <td>formation</td>
        <td><i class="fa fa-search" aria-hidden="true"><i class="fa fa-trash-o" aria-hidden="true"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></i></i></td>
    </tr>

    <tr>
        <td>9</td>
        <td>Salle de réunion du trêfle </td>
        <td><p>Notre salle de réunion d'une capacité de 100 personnes est parfaite pour vos        séminaires d'entreprises, rencontres associatives ou tout autre évènement.
 
                Elle est équipée d'un mur d'écran d'une diagonale de 92 pouces (2,35m) et peut être sonorisée sur demande (haut-parleur, micro HF).</p></td>
        <td><img src="/img/salle reunion du trêfle.jpg" alt="salle reunion du trêfle"></td>
        <td>France</td>
        <td>Marseille</td>
        <td>3 place aux Huiles</td>
        <td>13001</td>
        <td>100</td>
        <td>formation</td>
        <td><i class="fa fa-search" aria-hidden="true"><i class="fa fa-trash-o" aria-hidden="true"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></i></i></td>
    </tr>

    <tr>
        <td>9</td>
        <td>Salle de réunion Dome</td>
        <td><p>Espace intérieur modulable pouvant répondre à tout type de réunion d'une             capacité de 60 personnes assises et 100 personnes en buffet. 
                Cette salle est équipée d'une cuisine professionnelle pouvant faire intervenir un traiteur pour repas, pose ou apéritif dinatoire. 
                Salle équipée Paper-board, écran blanc, écran LCD 82cm. connexion filaire internet.</p></td>
        <td><img src="/img/salle dome.jpg" alt="salle dome"></td>
        <td>France</td>
        <td>Marseille</td>
        <td>3 place aux Huiles</td>
        <td>13001</td>
        <td>60</td>
        <td>reunion</td>
        <td><i class="fa fa-search" aria-hidden="true"><i class="fa fa-trash-o" aria-hidden="true"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></i></i></td>
    </tr>

</table>
</div>

</body>
</html>




<h3>Formulaire d'ajout ou de modification d'un produit</h3>
<form method="post" enctype="multipart/form-data" action="">
<!-- "multipart/form-data" permet d'uploader un fichier et de générer une superglobale $_FILES -->

        <input type="hidden" id="id_salle" name="id_salle" value="<?php echo $produit_actuel['id_salle'] ?? 0;   ?>">
        <!-- champ caché qui réceptionne l'id_produit nécessaire lors de la modification d'un produit existant -->

        <label for="titre">Titre</label><br>
        <input type="text" id="titre" name="titre" value="<?php echo $produit_actuel['titre'] ?? '';   ?>"><br><br>

        <label for="description">Description</label><br>
        <input type="text" id="description" name="description" value="<?php echo $produit_actuel['description'] ?? '';   ?>"><br><br>

        <label for="photo">Photo</label><br><br>
        <input type="file" id="photo" name="photo"><br><br>
        <!-- coupler avec l'attribut enctype="multipart/form-data" de la balise <form>, le type 'file' permet d'uploader un fichier (ici une photo) -->

        <label for="pays">Pays</label><br>
        <input type="text" id="pays" name="pays" value="<?php echo $produit_actuel['pays'] ?? '';   ?>"><br><br>

        <label>Ville</label>
        <select name="ville" >
            <option value="paris" selected>Paris</option>
            <option value="lyon" <?php if(isset($produit_actuel['ville']) && $produit_actuel['ville'] == 'lyon') echo 'selected';  ?> >Lyon</option>
            <option value="marseille" <?php if(isset($produit_actuel['ville']) && $produit_actuel['ville'] == 'marseille') echo 'selected';  ?>>Marseille</option>
        </select><br><br>


        <label for="adresse">Adresse</label><br>
        <textarea id="adresse" name="adresse"><?php echo $produit_actuel['adresse'] ?? '';   ?></textarea><br><br>

        <label>Code postal</label>
        <select name="code postal" >
            <option value="S" selected>S</option>
            <option value="M" <?php if(isset($produit_actuel['taille']) && $produit_actuel['taille'] == 'M') echo 'selected';  ?> >M</option>
            <option value="L" <?php if(isset($produit_actuel['taille']) && $produit_actuel['taille'] == 'L') echo 'selected';  ?>>L</option>
            <option value="XL" <?php if(isset($produit_actuel['taille']) && $produit_actuel['taille'] == 'XL') echo 'selected';  ?>>XL</option>

        </select><br><br>

        
        <!-- 9- Modification de la photo -->
        <?php
            if (isset($produit_actuel['photo'])) {
            echo '<i>Vous pouvez uploader une nouvelle photo</i><br>';
            // Afficher la photo actuelle :
            echo '<img src="'. $produit_actuel['photo'] .'" width="90" height="90"><br>';
            // Mettre le chemin de la photo dans un champ caché pour l'enregistrer en base :
            echo '<input type="hidden" name="photo_actuelle" value="'. $produit_actuel['photo'] .'">';
            //  ce champ renseigne le $_POST['photo_actuelle'] qui va en base quand on soumet le formulaire de modification. Si on ne remplit pas le formulaire ici, le champ photo de la base est remplacé par un vide, ce qui efface le chemin de la photo

            }






        ?>






        <label for="prix">Prix</label><br>
        <input type="text" id="prix" name="prix" value="<?php echo $produit_actuel['prix'] ?? 0;   ?>"><br><br>

        <label for="stock">Stock</label><br>
        <input type="text" id="stock" name="stock" value="<?php echo $produit_actuel['stock'] ?? 0;   ?>"><br><br>

        <input type="submit" value="enregistrer" class="btn">

    
</form>









<?php
endif;
require_once('inc/bas.inc.php');