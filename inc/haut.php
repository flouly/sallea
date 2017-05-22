<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sallea</title>

    <link rel="stylesheet" href="inc/css/style.css">
</head>
<body>
    <header>
        <nav>
            <h1>Sallea</h1>
            <ul>
                <?php
                echo '<li><a href="#">Qui sommes nous</a></li>';
                echo '<li><a href="#">Contact</a></li>';
                
                if(internauteEstConnecte()) {
                    echo 
                    '<li><select name="membre" id="membre">
                    
                    <option value="NULL">Espace membre</option>
                    <option value="connexion"><a href="sallea/connexion.php?action=deconnexion">Se deconnecter</a></option>
                    <option value="profil"><a href="sallea/profil.php">profil</a>Profil</option>
                    
                    </select></li>';   

                } else{
                    echo 
                    '<li><select name="membre" id="membre">
                    
                    <option value="NULL">Espace membre</option>
                    <option value="inscription"><a href="sallea/inscription.php">Inscription</a></option>
                    <option value="connexion"><a href="sallea/connexion.php">Connexion</a></option>
                    
                    </select></li>';   

                }

                if(internauteEstConnecteEtAdmim()){

                    echo '
                    <li>
                        <select name="admin" id="admin">
                            <option value="#"><a href="sallea/admin/salles">Gestion des salles</a></option>
                            <option value="#"><a href="sallea/admin/produits">Gestion des produits</a></option>
                            <option value="#"><a href="sallea/admin/membres">Gestion des membres</a></option>
                            <option value="#"><a href="sallea/admin/commandes">Gestion des avis</a></option>
                            <option value="#"><a href="sallea/admin/avis">Gestion des commandes</a></option>
                            <option value="#"><a href="sallea/admin/statistiques">Statistiques</a></option>               
                        </select>      
                    </li>';
                }

                ?>
            </ul>
           
        </nav>

    
    </header>
    <main class="container">
    
    
    
    
    
    
    
    
    
    
 

