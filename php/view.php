<?php
# Copyright © 2013 Florestan Bredow <florestan.bredow@daiko.fr>
# This work is free. You can redistribute it and/or modify it under the
# terms of the Do What The Fuck You Want To Public License, Version 2,
# as published by Sam Hocevar. See the COPYING file for more details.

class View {

	private $user;
	
	/************************************************************************
	 * Constructeur
	 ***********************************************************************/
	function __construct($user) {
    	$this->user = $user;
    }

	/************************************************************************
	 * Formulaire de création d'un utilisateur EXTERIEUR
	 ***********************************************************************/
	function showAddUserForm() {
		$pageTitle = "Créer un nouvel utilisateur.";
		include("php/views/header.phtml");
		include("php/views/main.form.phtml");
		include("php/views/footer.phtml");
	}

	/************************************************************************
	 * Formulaire de création d'un utilisateur EXTERIEUR
	 ***********************************************************************/
	function showAccount() {
		$pageTitle = "Compte utilisateur";
		include("php/views/account.phtml");
	}

	/************************************************************************
	 * Formulaire de modification d'un mot de passe
	 ***********************************************************************/
	function showModUserPasswordForm() {

		include("php/views/header.phtml");
		print("Prochainement…");
		include("php/views/footer.phtml");
	}

	/************************************************************************
	 * Formulaire de suppression d'un utilisateur
	 ***********************************************************************/
	function showDelUserForm() {
		include("php/views/header.phtml");
		print("Prochainement…");
		include("php/views/footer.phtml");
	}

	function showError($error) {
		$pageTitle = "Erreur";
		include("php/views/header.phtml");
		print($error);
		include("php/views/footer.phtml");
	}


	function getUserList($filtre){
		$list = $this->user->getUserList($filtre);

		for($i = 0; $i < count($list); ++$i) {

			$login = $list[$i][0];
			$geco = $list[$i][1];
			$java = 'onClick="fill(\''.$login.'\')"';
				
			printf("<li $java>$login ($geco)</li>");
		}
	}

} 
?>
