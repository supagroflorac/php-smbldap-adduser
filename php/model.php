<?php

include("php/functions.php");

class Users {
	public $name;
	public $firstname;
	public $password;
	public $login;
	public $set;
	private $log;

	/************************************************************************
	 * Constructeur
	 ***********************************************************************/
	// Nouvel utilisateur
	function __construct() {
		// VIDE
		$set = false; 
		$log = "actions.log";

	}

	function newUser($firstname, $name){
		$this->name = rmAccents($name);
		$this->firstname = rmAccents($firstname);

		$set = true;

		// On génère le login
		$this->genLogin();

		// On génere le mot de passe
		$this->genPassword();

		
	}

	function loadUser($login){
		$cmd = "smbldap-userlist -g -u ".$login;
		$output = exec($cmd);

		print($output);
	}

	//A partir d'un utilisateur existant
	/*function __construct($login) {

	}*/

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

		/*if(!$set) {
			throw new Exception("Utilisateur indéterminé.", 1);
		}*/

		// Pour supprimer quelqueq caractère indésirable dans les logins
		$firstname 	= preg_replace('#(-| |\')#', '', $this->firstname);
		$name 		= preg_replace('#(-| |\')#', '', $this->name);
		
		$login  = substr($firstname, 0, 1)
				 .substr($name, 0, 7);

		$this->login = strtolower($login);
	}

	function createUser() {

		/*if(!$set) {
			throw new Exception("Utilisateur indéterminé.", 1);
		}*/

		$cmd = "sudo /root/script/ajouterUtilisateurs.php.py '"
			.$this->firstname."' '"
			.$this->name."' '"
			.$this->login."' '"
			.$this->password."'";
		
		$output = exec($cmd);
		
		if($output!= "OK"){
			throw new Exception($output, 1);
		}

		$this->log("Création utilisateur ".$this->firstname." ".$this->name." (".$this->login.")");
	}

	function changePassword($login){



		$cmd = "sudo /usr/sbin/smbldap-passwd".$filtre;
	}

	function getUserList($filtre){
		//Ajoute * devant et derrière.
		$filtre = "*".$filtre."*";
		$cmd = "sudo /usr/sbin/smbldap-userlist -g -u ".$filtre;

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

		//TODO : Ajouter un filtre des résultats pour éviter de

		return($results);
	}

	function log($string){
		//Ajout de l'horodatage :
		$string = date("D, d/M/Y H:i:s")." : ".$string."\n";

		file_put_contents($this->file, $string);
	}
	
} // Fin Class User




?> 
