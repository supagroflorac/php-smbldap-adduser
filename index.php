<?php
# Copyright © 2013 Florestan Bredow <florestan.bredow@daiko.fr>
# This work is free. You can redistribute it and/or modify it under the
# terms of the Do What The Fuck You Want To Public License, Version 2,
# as published by Sam Hocevar. See the COPYING file for more details.

include("php/model.php");
include("php/view.php");

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

		$view->showAccount();

		break;

	case "getUserList":
		if(!isset($_GET["login"])){
			$_GET["login"] = "";
		}

		$view->getUserList($_GET["login"]);

		break;

	case "changeUserPassword":
		if(!isset($_POST["login"])){
			$view->showError("Changement de mot passe sans préciser l'utilisateur.");
			die();
		}
		// Load user information
		try { $user->loadUser($_POST["login"]); } 
		catch(Exception $e) {
			$view->showError($e->getMessage());
			die();
		}
		// Generate new user password
		try { $user->passwd(); } 
		catch(Exception $e) {
			$view->showError($e->getMessage());
			die();
		}

		// Print user informations
		$view->showAccount();

		break;
	
	// Affiche le formulaire de creation de compte
	default:
		$view->showAddUserForm();
		break;
}
?>
