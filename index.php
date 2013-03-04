<?php

// AJOUTER DANS /ETC/SUDOERS : 
// %www-data ALL = NOPASSWD:/root/script/ajouterUtilisateurs.php.py

//Mettre le fichier ajouterUtilisateurs.php.py dans /root/script

include("php/model.php");
include("php/view.php");

/*function getUserList() {
	echo '<h3>requête de test de LDAP</h3>';
	echo 'Connexion ...';
	$ds = ldap_connect("localhost");  // doit être un serveur LDAP valide !
	echo 'Le résultat de connexion est ' . $ds . '<br />';

	if ($ds) { 
	    echo 'Liaison ...'; 
	    $r = ldap_bind($ds);     // connexion anonyme, typique
	                                     // pour un accès en lecture seule.
	    echo 'Le résultat de connexion est ' . $r . '<br />';

	    echo 'Recherchons (sn=S*) ...';
	    // Recherche par nom
	    $sr = ldap_search($ds,"dc=supagro, dc=florac, ou=EXTERIEURS", "sn=*");  
	    echo 'Le résultat de la recherche est ' . $sr . '<br />';

	    echo 'Le nombre d\'entrées retournées est ' . ldap_count_entries($ds,$sr) 
	         . '<br />';

	    echo 'Lecture des entrées ...<br />';
	    $info = ldap_get_entries($ds, $sr);
	    echo 'Données pour ' . $info["count"] . ' entrées:<br />';

	    for ($i=0; $i<$info["count"]; $i++) {
	        echo 'dn est : ' . $info[$i]["dn"] . '<br />';
	        echo 'premiere entree cn : ' . $info[$i]["cn"][0] . '<br />';
	        echo 'premier email : ' . $info[$i]["mail"][0] . '<br />';
	    }

	    echo 'Fermeture de la connexion';
	    ldap_close($ds);

	} else {
	    echo '<h4>Impossible de se connecter au serveur LDAP.</h4>';
	}
}

getUserList();*/


$user = new Users();

$view = new View($user);


if(!array_key_exists('action', $_GET))
	$_GET['action'] = "NULL";

switch ($_GET['action']) {

	// Ajout d'un utilisateur
	case "addUser":
		$user->newUser($_POST['firstname'], $_POST['name']);

		try { $user->createUser(); } 
		catch(Exception $e) {
			$view->showError($e->getMessage());
			die();
		}

		$view->showAccount($user);

		break;

	case "getUserList":
		if(!isset($_GET["login"])){
			$_GET["login"] = "";
		}

		$view->getUserList($_GET["login"]);

		break;

	case "changeUserPassword":
		if(!isset($_GET["login"])){
			$_GET["login"] = "";
		}

		

		break;

	
	// Affiche le formulaire de creation de compte
	default:
		$view->showAddUserForm();
		break;
}
?>