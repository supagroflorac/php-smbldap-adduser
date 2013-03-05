<?php
# Copyright © 2013 Florestan Bredow <florestan.bredow@daiko.fr>
# This work is free. You can redistribute it and/or modify it under the
# terms of the Do What The Fuck You Want To Public License, Version 2,
# as published by Sam Hocevar. See the COPYING file for more details.

include("php/functions.php");

class Users {
	public $name;
	public $firstname;
	public $password;
	public $login;
	public $ou;
	public $set; //determine si l'utilisateur est completement initialisé
	
	/************************************************************************
	 * Constructeur
	 ***********************************************************************/
	// Nouvel utilisateur
	function __construct() {
		// VIDE
		$set = false; 
		$log = "actions.log";

	}

	// Créé les données utilisateur a partir du nom et prénom.
	function newUser($firstname, $name){
		$this->name = rmAccents($name);
		$this->firstname = rmAccents($firstname);
		$this->ou = "EXTERIEURS "; // Inutile pour eviter de laisser une variable vide.

		$this->set = true;

		// On génère le login
		$this->genLogin();

		// On génere le mot de passe
		$this->genPassword();
	}

	// Charge les données utilisateurs d'un utilisateur déjà existant.
	function loadUser($login){
		$cmd = "/usr/sbin/smbldap-usershow ".$login;
		$result = exec($cmd, $output, $return);
		if($return != 0)
			throw new Exception("Problème lors de la récupération des données utilisateurs.", 1);
			
		foreach ($output as $value) {
			$value_explode = explode(": ", $value);

			switch ($value_explode[0]) {
				case 'uid':
					$this->login = $value_explode[1];
					break;
				
				case 'sn':
					$this->name = $value_explode[1];
					break;

				case 'givenName':
					$this->login = $value_explode[1];
					break;

				case 'dn':
					$dn = $value_explode[1];
					$dn_explode = explode(",", $dn);
					$ou = explode("=", $dn_explode[1]);
					$this->ou = $ou[1];
					break;
			}
		}
		$this->set = true;
	}

	function genPassword() {
		$vowels = "aeuy";
		$consonants = "bdghjmnpqrstvz";
		$specials = "@#$%?.!:;";
		$numbers = "23456789";
	
		$password  = selChar($consonants)
					.selChar($vowels)
					.selChar($consonants)
					.selChar($specials)
					.selChar($numbers)
					.selChar($numbers)
					.selChar($numbers);

		$this->password = $password;
		return $password;	
	}

	function genLogin() {

		if(!$this->set) {
			throw new Exception("Données utilisateurs non initialisées.", 1);
		}

		// Pour supprimer quelqueq caractère indésirable dans les logins
		$firstname 	= preg_replace('#(-| |\')#', '', $this->firstname);
		$name 		= preg_replace('#(-| |\')#', '', $this->name);
		
		$login  = substr($firstname, 0, 1)
				 .substr($name, 0, 7);

		$this->login = strtolower($login);
	}

	function createUser() {

		if(!$this->set) {
			throw new Exception("Données utilisateurs non initialisées.", 1);
		}

		$cmd = "sudo ./py/adduser.php.py '"
			.$this->firstname."' '"
			.$this->name."' '"
			.$this->login."' '"
			.$this->password."'";

		$output = exec($cmd);
		
		if($output!= "OK"){
			throw new Exception($output, 1);
		}
	}

	function getUserList($filtre){
		//Ajoute * devant et derrière.
		$filtre = "*".$filtre."*";
		$cmd = "sudo smbldap-userlist -g -u ".$filtre;

		exec($cmd, $output, $return);

		if($return != 0){
			throw new Exception("Problème avec smbldap-userlist", 1);
		}

		$results = array();

		for($i = 0; $i < count($output); ++$i) {
			$row = explode("|", $output[$i]);
			//Supression des espaces en début et fin de chaine.
			
			if($row[0] >= 1000 && $row[0] < 65534){
				array_push($results, array(trim($row[1]), trim($row[2])));
			}
		}

		return($results);
	}

	function passwd(){

		if(!$this->set) {
			throw new Exception("Données utilisateurs non initialisées.", 1);
		}

		if($this->ou != "EXTERIEURS") {
			if($this->ou == "SUPAGRO")
				throw new Exception("Pas touche aux collègues. :/", 1);
			else
				throw new Exception("pas touche aux étudiants. :/", 1);	
		}

		$this->genPassword();

		$cmd = "sudo ./py/passwd.php.py '"
			.$this->login."' '"
			.$this->password."'";

		$output = exec($cmd, $out, $return);
		echo "<pre>";
		print_r($out);
		echo "</pre>";
		
		if($output != "OK"){
			throw new Exception($output, 1);
		}
	}

} // Fin Class User




?> 
