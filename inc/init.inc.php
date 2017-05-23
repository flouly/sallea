<?php

// Connexion a la BDD
$pdo = new PDO('mysql:host=localhost;dbname=sallea', 'root', 'root', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));

// Session
session_start();


// Declaration des variables du site
$contenu = '';
$contenu_gauche = '';
$contenu_droit = '';


DEFINE('RACINE', '/../');

//autres inclusions
require_once('fonction.inc.php');
?>